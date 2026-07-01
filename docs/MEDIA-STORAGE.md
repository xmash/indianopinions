# Media storage (gallery + videos)

Uploads use **Cloudflare R2** when configured, otherwise the **`public` disk** (local / Railway volume).

## R2 (recommended for production)

Set on Railway:

```env
R2_ACCOUNT_ID=
R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=
R2_URL=https://pub-….r2.dev
```

Gallery images → `gallery/Y/m/`  
Videos → `videos/Y/m/`

## Railway persistent volume (alternative)

1. Railway → Laravel service → **Volumes** → Add volume  
2. Mount path: `/app/storage/app/public`  
3. Do **not** set `R2_BUCKET` (falls back to `public` disk)  
4. Files are served at `https://admin.indianopinions.com/storage/…` after `php artisan storage:link` (entrypoint runs this on boot)

Entrypoint ensures:

- `storage/app/public/gallery`
- `storage/app/public/videos`

## Admin

- **Gallery** — images (`manage_gallery`)
- **Videos** — media library (`manage_media`) → public `/media` page

## Public API

- `GET /api/media/videos`
- `GET /api/media/videos/{id}`
