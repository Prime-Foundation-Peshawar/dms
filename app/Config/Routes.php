<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public home (CI4 Controllers + Views)
$routes->get('/', 'Home::index');

// ACP auth
$routes->get('acp/login', 'Acp\Auth::login');
$routes->post('acp/login', 'Acp\Auth::attempt');
$routes->get('acp/logout', 'Acp\Auth::logout');

// ACP protected
$routes->group('acp', ['filter' => 'acpauth'], static function ($routes) {
    $routes->get('/', 'Acp\Dashboard::index');
    $routes->get('analytics', 'Acp\Analytics::index');
    $routes->post('analytics', 'Acp\Analytics::save');
    $routes->get('reports', 'Acp\Reports::index');
    $routes->post('reports/contributions', 'Acp\Reports::saveContribution');
    $routes->post('reports/contributions/(:num)', 'Acp\Reports::saveContribution/$1');
    $routes->post('reports/activities', 'Acp\Reports::saveActivity');
    $routes->post('reports/integrity', 'Acp\Reports::saveIntegrity');
    $routes->post('reports/suggestions', 'Acp\Reports::saveSuggestion');
    $routes->get('faculty/submissions', 'Acp\Faculty::submissions');
    $routes->get('faculty/submissions/(:num)', 'Acp\Faculty::submission/$1');
    $routes->post('faculty/submissions/(:num)/approve', 'Acp\Faculty::approve/$1');
    $routes->post('faculty/submissions/(:num)/reject', 'Acp\Faculty::reject/$1');
    $routes->get('faculty/submissions/(:num)/file/(:segment)', 'Acp\Faculty::downloadFile/$1/$2');
    $routes->get('faculty/profiles', 'Acp\Faculty::profiles');
    $routes->get('faculty/profiles/(:num)', 'Acp\Faculty::editProfile/$1');
    $routes->post('faculty/profiles/(:num)', 'Acp\Faculty::saveProfile/$1');
    $routes->get('departments', 'Acp\Departments::index');
    $routes->get('departments/(:num)', 'Acp\Departments::edit/$1');
    $routes->post('departments/(:num)', 'Acp\Departments::save/$1');

    $routes->get('news', 'Acp\News::index');
    $routes->get('news/new', 'Acp\News::create');
    $routes->post('news', 'Acp\News::save');
    $routes->get('news/(:num)', 'Acp\News::edit/$1');
    $routes->post('news/(:num)', 'Acp\News::save/$1');

    $routes->get('events', 'Acp\Events::index');
    $routes->get('events/new', 'Acp\Events::create');
    $routes->post('events', 'Acp\Events::save');
    $routes->get('events/(:num)', 'Acp\Events::edit/$1');
    $routes->post('events/(:num)', 'Acp\Events::save/$1');

    $routes->get('slider', 'Acp\Slider::index');
    $routes->get('slider/new', 'Acp\Slider::create');
    $routes->post('slider', 'Acp\Slider::save');
    $routes->get('slider/(:num)', 'Acp\Slider::edit/$1');
    $routes->post('slider/(:num)', 'Acp\Slider::save/$1');

    $routes->get('vacant-seats', 'Acp\VacantSeats::index');
    $routes->get('vacant-seats/new', 'Acp\VacantSeats::create');
    $routes->get('vacant-seats/settings', 'Acp\VacantSeats::settings');
    $routes->post('vacant-seats/settings', 'Acp\VacantSeats::saveSettings');
    $routes->post('vacant-seats', 'Acp\VacantSeats::save');
    $routes->get('vacant-seats/(:num)', 'Acp\VacantSeats::edit/$1');
    $routes->post('vacant-seats/(:num)', 'Acp\VacantSeats::save/$1');

    $routes->get('newsletters', 'Acp\Newsletters::index');
    $routes->get('newsletters/new', 'Acp\Newsletters::create');
    $routes->post('newsletters', 'Acp\Newsletters::save');
    $routes->get('newsletters/(:num)', 'Acp\Newsletters::edit/$1');
    $routes->post('newsletters/(:num)', 'Acp\Newsletters::save/$1');

    $routes->get('gallery', 'Acp\Gallery::index');
    $routes->get('gallery/new', 'Acp\Gallery::create');
    $routes->post('gallery', 'Acp\Gallery::save');
    $routes->get('gallery/(:num)', 'Acp\Gallery::edit/$1');
    $routes->post('gallery/(:num)', 'Acp\Gallery::save/$1');

    $routes->get('pages', 'Acp\Pages::index');
    $routes->get('pages/new', 'Acp\Pages::create');
    $routes->post('pages', 'Acp\Pages::save');
    $routes->get('pages/(:num)', 'Acp\Pages::edit/$1');
    $routes->post('pages/(:num)', 'Acp\Pages::save/$1');

    $routes->get('modules/(:segment)', 'Acp\Modules::show/$1');
});

// Public module routes (CI4 Controllers + Views)
$routes->get('all-news', 'News::index');
$routes->get('single-news', 'News::single');
$routes->get('events', 'Events::index');
$routes->get('event-single', 'Events::single');
$routes->get('departments', 'Departments::index');
$routes->get('department', 'Departments::show');
$routes->get('department-activity', 'Departments::activity');
$routes->get('faculty', 'Faculty::index');
$routes->get('faculty-all', 'Faculty::all');
$routes->get('faculty-profile', 'Faculty::profile');
$routes->get('faculty-research', 'Faculty::research');
$routes->get('faculty-profiles-api', 'Faculty::profilesApi');
$routes->get('faculty-proxy', 'Faculty::proxy');
$routes->get('faculty_api', 'Faculty::apiPage');
$routes->get('faculty-update', 'FacultyUpdate::index');
$routes->post('faculty-update-submit', 'FacultyUpdate::submit');
$routes->get('gallery', 'Media::gallery');
$routes->get('vacant-seats', 'Media::vacantSeats');
$routes->get('newsletter', 'Media::newsletter');
$routes->get('robots', 'Site::robots');
$routes->get('robots.txt', 'Site::robots');
$routes->get('sitemap', 'Site::sitemap');
$routes->get('sitemap.xml', 'Site::sitemap');
$routes->get('cms-page', 'Pages::cms');

// Static + CMS catch-all (must stay above any remaining legacy fallback)
$routes->get('(:segment)', 'Pages::show/$1');
$routes->post('(:segment)', 'Pages::show/$1');
