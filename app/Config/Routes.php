<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();

$routes->get('/', 'Home::index');
$routes->get('departments', 'Departments::index');
$routes->get('department', 'Departments::show');
$routes->get('department-activity', 'Departments::activity');

$routes->get('faculty', 'Faculty::index');
$routes->get('faculty-proxy', 'Faculty::proxy');
$routes->get('faculty-profile', 'Faculty::profile');
$routes->get('faculty-profiles-api', 'Faculty::profilesApi');
$routes->get('faculty-all', 'Faculty::redirectAll');
$routes->get('faculty-update', 'FacultyUpdate::index');
$routes->post('faculty-update-submit', 'FacultyUpdate::submit');
$routes->get('update-activity', 'ActivityUpdate::index');
$routes->post('update-activity-submit', 'ActivityUpdate::submit');
$routes->post('update-activity-draft', 'ActivityUpdate::saveDraft');
$routes->get('medical-education', 'PageController::redirectMedicalEducation');
$routes->get('newsletter', 'PageController::redirectNewsletter');

$routes->get('robots.txt', 'SeoController::robots');
$routes->get('sitemap.xml', 'SeoController::sitemap');

// Static / content pages (DB view_key + Views/pages/{slug}.php)
$static = [
    'about', 'admissions', 'vision-mission', 'contact', 'pmc',
    'curriculum', 'examinations', 'faculty-research',
    'all-news', 'single-news', 'events', 'event-single', 'gallery',
    'e-health', 'clinical-skill-labs', 'education-literature',
    'literary-society', 'social-welfare', 'sports-society',
    'student-exchange', 'student-guide', 'student-research', 'umr',
    'vacant-seats', 'virtual-museum', 'pg-medical-education',
    'pg-surgery-residency', 'portal', 'portal-login', 'sitemap-page',
];
foreach ($static as $slug) {
    $routes->get($slug, 'PageController::show/' . $slug);
}

// Admin Control Panel
$routes->group('acp', ['namespace' => 'App\Controllers\Acp'], static function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attempt');
    $routes->get('logout', 'Auth::logout');

    $routes->group('', ['filter' => 'acpAuth'], static function ($routes) {
        $routes->get('/', 'Dashboard::index');

        $routes->get('pages', 'Pages::index');
        $routes->get('pages/create', 'Pages::create');
        $routes->post('pages/store', 'Pages::store');
        $routes->get('pages/edit/(:num)', 'Pages::edit/$1');
        $routes->post('pages/update/(:num)', 'Pages::update/$1');
        $routes->post('pages/delete/(:num)', 'Pages::delete/$1');

        $routes->get('news', 'News::index');
        $routes->get('news/create', 'News::create');
        $routes->post('news/store', 'News::store');
        $routes->get('news/edit/(:num)', 'News::edit/$1');
        $routes->post('news/update/(:num)', 'News::update/$1');
        $routes->post('news/delete/(:num)', 'News::delete/$1');
        $routes->post('news/media-delete/(:num)', 'News::deleteMedia/$1');
        $routes->post('news/media-reorder', 'News::reorderMedia');

        $routes->get('events', 'Events::index');
        $routes->get('events/create', 'Events::create');
        $routes->post('events/store', 'Events::store');
        $routes->get('events/edit/(:num)', 'Events::edit/$1');
        $routes->post('events/update/(:num)', 'Events::update/$1');
        $routes->post('events/delete/(:num)', 'Events::delete/$1');
        $routes->post('events/media-delete/(:num)', 'Events::deleteMedia/$1');
        $routes->post('events/media-reorder', 'Events::reorderMedia');

        $routes->get('departments', 'Departments::index');
        $routes->get('departments/edit/(:num)', 'Departments::edit/$1');
        $routes->post('departments/update/(:num)', 'Departments::update/$1');
        $routes->post('departments/activity-store/(:num)', 'Departments::storeActivity/$1');
        $routes->post('departments/activity-delete/(:num)', 'Departments::deleteActivity/$1');
        $routes->post('departments/activities-reorder', 'Departments::reorderActivities');

        $routes->get('faculty', 'Faculty::index');
        $routes->get('faculty/create', 'Faculty::create');
        $routes->post('faculty/store', 'Faculty::store');
        $routes->get('faculty/edit/(:num)', 'Faculty::edit/$1');
        $routes->post('faculty/update/(:num)', 'Faculty::update/$1');
        $routes->post('faculty/delete/(:num)', 'Faculty::delete/$1');
        $routes->post('faculty/set-dept-source', 'Faculty::setDeptSource');
        $routes->get('faculty/submissions', 'Faculty::submissions');
        $routes->get('faculty/submissions/(:num)', 'Faculty::submission/$1');
        $routes->post('faculty/submissions/(:num)/approve', 'Faculty::approve/$1');
        $routes->post('faculty/submissions/(:num)/reject', 'Faculty::reject/$1');
        $routes->get('faculty/submissions/(:num)/file/(:segment)', 'Faculty::downloadFile/$1/$2');

        $routes->get('gallery', 'Gallery::index');
        $routes->post('gallery/album-store', 'Gallery::storeAlbum');
        $routes->post('gallery/album-update/(:num)', 'Gallery::updateAlbum/$1');
        $routes->post('gallery/album-delete/(:num)', 'Gallery::deleteAlbum/$1');
        $routes->post('gallery/albums-reorder', 'Gallery::reorderAlbums');
        $routes->get('gallery/album/(:num)', 'Gallery::album/$1');
        $routes->post('gallery/items-store/(:num)', 'Gallery::storeItems/$1');
        $routes->post('gallery/item-update/(:num)', 'Gallery::updateItem/$1');
        $routes->post('gallery/item-delete/(:num)', 'Gallery::deleteItem/$1');
        $routes->post('gallery/items-reorder', 'Gallery::reorderItems');

        $routes->get('vacant-seats', 'VacantSeats::index');
        $routes->post('vacant-seats/save', 'VacantSeats::save');

        $routes->get('settings', 'Settings::index');
        $routes->post('settings/save', 'Settings::save');

        $routes->get('users', 'Users::index');
        $routes->get('users/create', 'Users::create');
        $routes->post('users/store', 'Users::store');
        $routes->get('users/edit/(:num)', 'Users::edit/$1');
        $routes->post('users/update/(:num)', 'Users::update/$1');
        $routes->post('users/delete/(:num)', 'Users::delete/$1');
    });
});
