# Deployment Guide

## Requirements
- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.4+
- Composer 2.x
- Node.js 20+ & npm
- Redis (optional, for queues/cache)

## Server Setup

### 1. Clone & install dependencies
```bash
git clone <repo-url> bike-rental
cd bike-rental
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

### 2. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Configure these `.env` values:
```
APP_URL=https://your-domain.com
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bike_rental
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Bike Rental"
```

### 3. Database
```bash
php artisan migrate --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=BikeCategorySeeder --force
```

### 4. Storage
```bash
php artisan storage:link
```
Ensure `public/storage` is symlinked to `storage/app/public`.

### 5. Cache (production)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Web server

#### Apache
Place the project in your web root. Ensure `mod_rewrite` is enabled and the `public/` directory is the document root. The included `.htaccess` handles URL rewriting.

#### Nginx
```nginx
server {
    listen 443 ssl;
    server_name your-domain.com;
    root /var/www/bike-rental/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Post-Deployment
1. Create an admin user: `php artisan tinker` then `User::create([...])` and assign the `Admin` role.
2. Visit `https://your-domain.com/admin` to log into the Filament admin panel.
3. Companies and customers can self-register via the public registration forms.

## Email
Password reset and email verification require a working mail configuration. If mail is not set up, these features will silently fail (no error, but emails won't send). Notifications use the `database` channel by default and work without mail.

## File Permissions (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Maintenance
- Monitor `storage/logs/laravel.log` for errors.
- Schedule the task scheduler via cron: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`
- Clear caches after config changes: `php artisan optimize:clear`
