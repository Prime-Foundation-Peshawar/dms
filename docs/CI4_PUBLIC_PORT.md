# CI4 public port (DMS / DDS)

## Architecture
- Public routes → Controllers under `app/Controllers/` (`Home`, `Pages`, `News`, `Events`, `Departments`, `Faculty`, `FacultyUpdate`, `Media`, `Site`)
- Markup → `app/Views/pages/*` extending `layouts/public`
- Chrome → `app/Views/partials/header.php` + `footer.php` (from master design)
- Data helpers still live in `legacy/includes/*` (cms-content, faculty-lib, departments-data) until later Libraries migration
- ACP extras unchanged under `app/Controllers/Acp/*`

## Parity
- Page bodies extracted from `legacy/*.php` (sourced from master flat PHP)
- Design/CSS remain under `public/assets/`
- Faculty-update, ACP, CMS kept as extras

## Tools
- `php tools/port_legacy_to_ci4.php` — extract views
- `php tools/repair_views.php` — rebuild dynamic page views + pre-header CSS

## Retired
- `LegacyPage` is no longer routed (file may remain for reference)
