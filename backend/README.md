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

Admin login: **admin@indianopinions.com** / **password** (change after first login)

## API endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/articles` | Paginated published articles (`?category=politics&featured=1`) |
| GET | `/api/articles/{slug}` | Single article |
| GET | `/api/categories` | All editorial hubs |
| GET | `/api/categories/{slug}` | Hub with its articles |
| POST | `/api/newsletter/subscribe` | `{ "email": "..." }` |

## Deployment (Railway)

1. Create a Railway project with PostgreSQL + web service from this `backend/` directory.
2. Set `APP_KEY` (`php artisan key:generate --show`).
3. Link Postgres — Railway injects `DATABASE_URL`.
4. Set `FRONTEND_URL` to your Netlify site URL for CORS.
5. Deploy — Docker runs migrations and seeds categories on boot.

## Next.js integration

In Netlify, set:

```
API_URL=https://your-backend.up.railway.app
```

Fetch articles from `${API_URL}/api/articles` in the Next.js app (not yet wired).

## Editorial categories (seeded)

Politics, Economy, Foreign Affairs, Society, Technology, Diaspora, Opinion, Analysis
