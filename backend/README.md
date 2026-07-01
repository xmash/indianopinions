# Indian Opinions — Laravel Backend

Headless CMS and PostgreSQL-backed publishing API for the Next.js editorial site in `../studio`.

Scaffolded from [SpaceGapsSite](https://github.com/xmash/spacegapssite) with articles, categories, tags, gallery, and newsletter support.

## Stack

- Laravel 13 + PHP 8.3
- PostgreSQL (production on Railway)
- SQLite (local dev default)
- Blade admin panel at `/admin`
- Read-only JSON API at `/api/*` for the Next.js frontend

## Local setup

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite   # if using SQLite
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan serve
```

Admin login: **editor@indianopinions.com** / **password** (editor) or **writer@indianopinions.com** / **password** (writer). Change after first login.

## Editorial MVP

Two roles with a mandatory approval workflow:

| Role | Can do |
|------|--------|
| **Writer** | Create drafts, edit own drafts/revisions, submit for review |
| **Editor** | Everything writers can do, plus review queue, approve/publish, request changes, manage categories/tags/staff/gallery |

**Article statuses:** `draft` → `submitted` → `published` (or `changes_requested` → back to writer → resubmit).

Editors must approve via the **Review Queue** — writers cannot publish directly.

### Permissions matrix

Defined in `config/permissions.php`. Live view: **Admin → Permissions**.

| Area | Writer | Editor |
|------|--------|--------|
| Dashboard, own articles | Yes | Yes |
| Review queue, publish | — | Yes |
| Categories, tags, gallery, staff | — | Yes |
| Homepage & hub orchestration | — | Yes |

### Page orchestration

Editors curate slots at **Admin → Homepage** and **Admin → Hub Pages**. Empty slots fall back to latest published articles.

## API endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/articles` | Paginated published articles (`?category=politics&featured=1`) |
| GET | `/api/articles/{slug}` | Single article |
| GET | `/api/layout/homepage` | Resolved homepage layout |
| GET | `/api/layout/hubs/{slug}` | Resolved hub page layout |
| GET | `/api/categories` | All editorial hubs |
| GET | `/api/categories/{slug}` | Hub with its articles |
| POST | `/api/login` | Staff sign-in (Sanctum session; used by `/sign-in`) |
| GET | `/api/media/videos` | Published videos for `/media` |
| GET | `/api/media/videos/{id}` | Single video |
| POST | `/api/newsletter/subscribe` | `{ "email": "..." }` |

## Deployment (Railway)

1. Create a Railway project with PostgreSQL + web service.
2. **Settings → Source**
   - **Root Directory** = `backend`
   - **Config file path** = `/backend/railway.toml`
   - **Watch Paths** — empty (do not use `backend/**`; omitted from `railway.toml`)
   - **Start Command** — `/entrypoint.sh` (set in `railway.toml` + Dockerfile `CMD`)
   - **Healthcheck timeout** — `300` (set in `railway.toml`)
3. Set variables:

```env
APP_KEY=                    # php artisan key:generate --show
APP_URL=https://admin.indianopinions.com
ADMIN_URL=https://admin.indianopinions.com/admin
FRONTEND_URL=https://indianopinions.com
APP_ALLOWED_HOSTS=admin.indianopinions.com,api.indianopinions.com,indianopinions.com,www.indianopinions.com
SESSION_DOMAIN=.indianopinions.com
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=indianopinions.com,www.indianopinions.com,admin.indianopinions.com,api.indianopinions.com
CORS_ALLOWED_ORIGINS=https://indianopinions.com,https://www.indianopinions.com,https://admin.indianopinions.com,https://api.indianopinions.com
CACHE_STORE=database
SESSION_DRIVER=database
```

Custom domains on Railway: `api.indianopinions.com` and `admin.indianopinions.com` (port **8080**). DNS CNAMEs live in Netlify DNS.

`FRONTEND_URL` drives “back to site” and unauthenticated admin redirects to `/sign-in`.

5. Link Postgres — Railway injects `DATABASE_URL`.

### Deploy vs Redeploy (important)

| Action | Pulls latest Git? | Rebuilds Docker? |
|--------|-------------------|------------------|
| **Push to `main`** | Yes | Yes (if backend files changed) |
| **Deploy → latest commit** | Yes | Yes |
| **Redeploy** | **No** | **No** — restarts old image |
| **Sync** (repo reconnect) | Maybe | Only if a new build is triggered |

**Redeploy never pulls new code.** If the old image fails boot, Redeploy will fail again.

To ship new code: push to `main`, or **Deployments → Deploy** the latest commit, optionally **Clear build cache** first.

If pushes do not trigger builds: confirm Root Directory is `backend`, clear any **Watch Paths** saved in the UI (or temporarily remove Config file path to unlock the field), and reconnect GitHub if webhooks are stale.

**UI fields greyed out?** Railway is using `backend/railway.toml`. Change settings in that file and push — do not fight the dashboard. To edit in the UI only: remove Config file path, change settings, redeploy (not recommended long-term).

See also: [`docs/RAILWAY-LARAVEL-DEPLOY.md`](../docs/RAILWAY-LARAVEL-DEPLOY.md) for `Host is malformed` and Redis issues.

## Next.js integration

Production (Netlify):

```env
API_URL=https://api.indianopinions.com
NEXT_PUBLIC_API_URL=https://api.indianopinions.com
NEXT_PUBLIC_ADMIN_URL=https://admin.indianopinions.com/admin
```

Staff sign-in: `https://indianopinions.com/sign-in` → `https://admin.indianopinions.com/admin`

Fetch layout from `${API_URL}/api/layout/homepage` and hub pages from `/api/layout/hubs/{slug}`.

See [`docs/STAFF.md`](../docs/STAFF.md) for staff URLs.

## Editorial categories (seeded)

Politics, Economy, Foreign Affairs, Society, Technology, Diaspora, Opinion, Analysis
