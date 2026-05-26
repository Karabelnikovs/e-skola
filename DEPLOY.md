# Deployment checklist

Run these commands on the server after every deploy:

```bash
# 1. Install/update dependencies
composer install --no-dev --optimize-autoloader

# 2. Run migrations (safe — migrations skip tables/columns that already exist)
php artisan migrate --force

# If migrate still fails on an old DB that never used Laravel migrations, pull latest
# code first (migrations are idempotent). Only if needed, baseline the migrations table:
# php artisan migrate:status
# then mark already-applied migrations manually — contact dev before doing this.

# 3. Create the public storage symlink (fixes 403 on /storage/uploads/*)
php artisan storage:link

# 4. Set correct permissions so the web user can read uploaded files
chmod -R 755 storage/app/public
find storage/app/public -type f -exec chmod 644 {} \;

# 5. Clear caches so config/route/view changes take effect
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Fixing 403 on uploaded images

If `/storage/uploads/filename.jpg` returns 403:

1. Verify the symlink exists: `ls -la public/storage` — it should point to `../storage/app/public`.
   If not: `php artisan storage:link`

2. Check file permissions: uploaded files must be readable by the web user (www-data / apache).
   Run `find storage/app/public -type f -exec chmod 644 {} \;` to fix.

3. On shared hosting (cPanel / WHM), confirm that `.htaccess` under `storage/` does not block
   public access. The default Laravel storage folder does NOT include an `.htaccess`; if one was
   added (e.g. by the host), remove it from `storage/app/public/`.

4. If using ModSecurity, check if hotlink/image-leech protection is blocking `/storage/*` URLs.

## Environment variables to check before go-live

```
APP_ENV=production
APP_URL=https://yourdomain.com        # must match the real URL (no trailing slash)
SESSION_DRIVER=database               # sessions table must exist (migration added)
```
