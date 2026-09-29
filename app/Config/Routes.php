<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public home via legacy until fully ported
$routes->get('/', 'LegacyPage::show/index');

// ACP auth
$routes->get('acp/login', 'Acp\Auth::login');
$routes->post('acp/login', 'Acp\Auth::attempt');
$routes->get('acp/logout', 'Acp\Auth::logout');

// ACP protected
$routes->group('acp', ['filter' => 'acpauth'], static function ($routes) {
    $routes->get('/', 'Acp\Dashboard::index');
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

// Legacy public pages (extensionless)
$routes->get('(:segment)', 'LegacyPage::show/$1');
$routes->post('(:segment)', 'LegacyPage::show/$1');
