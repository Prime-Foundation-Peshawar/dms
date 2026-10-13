# DMS — Peshawar Medical College (CodeIgniter 4)

Public website for the Department of Medical Sciences / Peshawar Medical College.

## Stack

- CodeIgniter 4.7
- MySQL (staging/production) via `database.default.*` in `.env`
- Live HRMS directory: `https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php`
- Faculty CV / department catalog / page SEO metadata stored in the app database

## Local setup

```bash
composer install
cp .env.example .env   # then set DB + app.baseURL
php spark migrate
php spark db:seed DmsSeeder
php spark serve
```

Document root should be `public/` (root `.htaccess` forwards there).

For quick local smoke tests without MySQL, `.env` may use SQLite3 (`writable/dms.db`).

## Key routes

| Path | Purpose |
|------|---------|
| `/` | Home |
| `/departments` | Department index (hidden depts excluded) |
| `/department?slug=` | Department detail |
| `/faculty` | Faculty directory |
| `/faculty-proxy` | JSON directory API |
| `/faculty-profile?n=` | Faculty profile |

## Notes

- Admin/CMS UI is intentionally out of scope on this branch (schema includes `users` for later).
- Legacy flat PHP is kept under `_legacy/` for reference during the port.
- Do not commit real `.env` passwords.
