# Railway Laravel deploy fixes

Portable guide for fixing common Renpresso-style Laravel deploy failures on Railway.  
Implemented in this repo in commits `5e2d1cd` and `f980745`.

---

## 1. `Invalid URI: Host is malformed` (container boot / healthcheck)

### Symptom

Deploy logs show errors during entrypoint, before nginx serves traffic:

```
==> Running php artisan optimize
In Request.php line 364:
  Invalid URI: Host is malformed.
WARN: optimize failed or timed out
==> Running migrations
  Invalid URI: Host is malformed.
```

Healthcheck may fail because `php artisan optimize` / `migrate` never complete.

### Root cause

Laravel 11 bootstraps a **fake HTTP request for every Artisan command** via `Illuminate\Foundation\Bootstrap\SetRequestForConsole`:

```php
// vendor/laravel/framework/.../SetRequestForConsole.php
$uri = $app->make('config')->get('app.url', 'http://localhost');
$app->instance('request', Request::create($uri, 'GET', [], [], [], $server));
```

That passes **`config('app.url')`** (= `APP_URL`) into Symfony `Request::create()`. If the host part is invalid, Symfony throws **Host is malformed**.

### Common Railway misconfigurations

| Bad value | Problem |
|-----------|---------|
| `APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}` | Unexpanded template; host contains `${{` |
| `APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}` | Same |
| `APP_URL=renpresso.com,www.renpresso.com` | Commas — belongs in `APP_ALLOWED_HOSTS` |
| `APP_URL=renpresso.com` (no scheme) | Usually OK after normalization; prefer full URL |
| `APP_ALLOWED_HOSTS=https://renpresso.com,...` | Full URLs in allowed hosts — sanitize to hostnames only |

### Fix A — Railway variables (do this first)

On the **web service**, set **literal** values:

```env
APP_URL=https://yourdomain.com
APP_ALLOWED_HOSTS=yourdomain.com,www.yourdomain.com,your-app.up.railway.app
```

Rules:

- **`APP_URL`** = one full URL with `https://` — **never** a `${{…}}` reference.
- **`APP_ALLOWED_HOSTS`** = comma-separated **hostnames only** (no `https://`, no commas in `APP_URL`).
- Keep **`APP_URL`** as your canonical domain (emails, default links). Railway hostname goes in **`APP_ALLOWED_HOSTS`**.

Redeploy after changing variables.

### Fix B — Code hardening (copy to other Laravel projects)

#### 1. Add `app/Support/AppUrl.php`

Copy from this repo: [`app/Support/AppUrl.php`](../app/Support/AppUrl.php)

Responsibilities:

- Trim quotes/whitespace from `APP_URL`
- Reject unexpanded `${{` / `${` templates
- Ensure URL has `https://` when scheme missing
- Validate hostname characters
- Fall back to first valid host in `APP_ALLOWED_HOSTS`
- Parse allowed hosts list (strip accidental `https://` prefixes)

#### 2. Wire into `config/app.php`

```php
use App\Support\AppUrl;

return [
    // ...
    'url' => AppUrl::normalize(env('APP_URL'), env('APP_ALLOWED_HOSTS')),
];
```

After deploy, run `php artisan config:clear` once if config was previously cached with a bad URL (or rebuild the container).

#### 3. Use `AppUrl::parseAllowedHosts()` for multi-domain support

In `AppServiceProvider` (optional but recommended for custom domain + Railway URL):

```php
use App\Support\AppUrl;

private function allowedAppHosts(): array
{
    return AppUrl::parseAllowedHosts(env('APP_ALLOWED_HOSTS'));
    // or fallback from config('app.url') — see AppServiceProvider in this repo
}
```

Skip `URL::forceRootUrl()` when `app()->runningInConsole()` — console already uses normalized `app.url`.

#### 4. Entrypoint guard (`docker/entrypoint.sh`)

Fail fast with a clear message:

```bash
if printf '%s' "${APP_URL:-}" | grep -qE '\$\{\{|\$\{'; then
  echo "ERROR: APP_URL looks like an unexpanded Railway template (${APP_URL})."
  echo "       Set APP_URL to a literal URL, e.g. https://yourdomain.com"
  exit 1
fi
```

Also require `APP_KEY`:

```bash
if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is not set."
  exit 1
fi
```

### Verification

```bash
# After deploy — logs should show optimize + migrate completing
railway logs

# Local simulation of bad APP_URL (should still boot after Fix B)
APP_URL='https://${{RAILWAY_PUBLIC_DOMAIN}}' \
APP_ALLOWED_HOSTS='yourdomain.com' \
php artisan optimize
```

---

## 2. `/up` returns 200 but all pages return 500 (Redis)

### Symptom

```
GET /index.php 200   # healthcheck /up
GET /index.php 500   # /, /login, etc.
```

Logs may show `Class "Redis" not found` locally, or Redis connection errors in production.

### Root cause

`.env` / Railway variables set `SESSION_DRIVER=redis` and `CACHE_STORE=redis` but **no Redis service** is attached.

Web routes use session middleware; `/up` does not.

### Fix A — Railway variables (minimal deploy)

```env
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Remove or unset `REDIS_URL` references unless you add a Redis plugin.

### Fix B — Entrypoint auto-fallback

In `docker/entrypoint.sh`, before `php artisan optimize`:

```bash
if [ -z "${REDIS_URL:-}" ]; then
  echo "==> No REDIS_URL — forcing file cache/session and sync queue"
  export CACHE_STORE=file
  export SESSION_DRIVER=file
  export QUEUE_CONNECTION=sync
fi
```

Default `.env.example` to file/sync, not redis, for projects without Redis.

---

## 3. Recommended entrypoint order

```bash
1. Check APP_KEY
2. Check APP_URL (no ${{ templates }})
3. Force file drivers if REDIS_URL unset
4. php artisan optimize
5. php artisan migrate --force  (exit 1 on failure in production)
6. php artisan storage:link
7. Start nginx + php-fpm
```

---

## 4. Checklist for a new Railway instance

- [ ] Postgres service added; `DATABASE_PRIVATE_URL` or `DB_*` refs on web service
- [ ] `APP_KEY` set (`php artisan key:generate --show`)
- [ ] `APP_URL=https://…` literal URL (not `${{…}}`)
- [ ] `APP_ALLOWED_HOSTS=domain.com,…,….up.railway.app` (hostnames only)
- [ ] `CACHE_STORE=file`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync` (unless Redis added)
- [ ] `AppUrl` helper + `config/app.php` normalization (Fix B above)
- [ ] Entrypoint guards for `APP_KEY`, `APP_URL`, Redis fallback
- [ ] Healthcheck path `/up` in `railway.json`
- [ ] Redeploy and confirm logs: optimize + migrate succeed

---

## 5. Files changed in Renpresso (reference)

| File | Purpose |
|------|---------|
| `app/Support/AppUrl.php` | Normalize `APP_URL`, parse `APP_ALLOWED_HOSTS` |
| `config/app.php` | `url` uses `AppUrl::normalize()` |
| `app/Providers/AppServiceProvider.php` | Multi-host URLs via parsed allowed hosts |
| `docker/entrypoint.sh` | APP_KEY, APP_URL, Redis, migrate hardening |
| `.env.example` | File drivers default; document Railway vars |
| `README.md` | Deploy section warnings |

Copy these patterns into any Laravel 11 app using Docker + Railway with the same entrypoint style.
