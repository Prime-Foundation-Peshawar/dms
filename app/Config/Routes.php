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
$routes->get('faculty-all', 'Faculty::redirectAll');
$routes->get('medical-education', 'PageController::redirectMedicalEducation');

$routes->get('robots.txt', 'SeoController::robots');
$routes->get('sitemap.xml', 'SeoController::sitemap');

// Static / content pages (DB view_key + Views/pages/{slug}.php)
$static = [
    'about', 'admissions', 'vision-mission', 'contact', 'pmc',
    'curriculum', 'examinations', 'faculty-research', 'newsletter',
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
