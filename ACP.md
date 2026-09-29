# DMS ACP (Admin Control Panel)

URL (staging): `/acp`

## Server setup

1. PHP **8.1+** (cPanel MultiPHP / ea-php81 or 82).
2. Copy `.env.example` → `.env` and set:
   - `app.baseURL` (staging: `https://staging.riphahpsh.edu.pk/dms/`)
   - database credentials
   - `CMS_ADMIN_EMAIL` / `CMS_ADMIN_PASSWORD` (first admin)
   - `encryption.key` (generate: `php -r "echo 'hex2bin:' . bin2hex(random_bytes(32)), PHP_EOL;"`)
3. Ensure `writable/` is writable by the web user.
4. Run `php db/apply.php` (deploy workflow does this).

No Composer on the server — `vendor/` is committed.

## Phase 1

- Dashboard
- Faculty submission review (approve / reject → live `faculty_profiles`)
- Live profile edit
- Module stubs for News, Events, Gallery, Departments, Vacant seats, Newsletters, Pages

Public pages currently render from `legacy/` through CI4 routing.
