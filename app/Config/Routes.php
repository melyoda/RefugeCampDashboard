<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


//Authentication Routes (shield)
service('auth')->routes($routes);

$routes->group('/r', function($routes) {
    $routes->get('household-register', 'RegisterController::index');
    $routes->post('household-register/save', 'RegisterController::save');

    $routes->get('/', 'PortalController::login');
    $routes->get('login', 'PortalController::login');
    $routes->post('auth', 'PortalController::auth');
    $routes->get('dashboard', 'PortalController::dashboard');
    $routes->get('logout', 'PortalController::logout');

    $routes->post('add-member', 'PortalController::addMember');
    $routes->post('remove-member/(:num)', 'PortalController::removeMember/$1');
});


//Main Dashboard Routes
$routes->get('/portal-manage-x89', 'DashboardController::index');

// Protected Activities Routes
$routes->group('activities', function($routes) {
    $routes->get('/', 'ActivitiesController::index');
    $routes->get('create', 'ActivitiesController::create');
    $routes->post('store', 'ActivitiesController::store');
    $routes->get('edit/(:num)', 'ActivitiesController::edit/$1');
    $routes->post('update/(:num)', 'ActivitiesController::update/$1');
    $routes->post('delete/(:num)', 'ActivitiesController::delete/$1');

    // Activity Details and Distribution Tracking
    $routes->get('show/(:num)', 'ActivitiesController::show/$1');
    $routes->post('save-distribution/(:num)', 'ActivitiesController::saveDistribution/$1');
});

// Protected Residents Routes
$routes->group('residents', function($routes) {
    $routes->get('/', 'ResidentsController::index');
    $routes->get('create', 'ResidentsController::create');
    $routes->post('store', 'ResidentsController::store');
    $routes->get('edit/(:num)', 'ResidentsController::edit/$1');
    $routes->post('update/(:num)', 'ResidentsController::update/$1');
    $routes->post('delete/(:num)', 'ResidentsController::delete/$1');

    $routes->post('approve/(:num)', 'ResidentsController::approve/$1');
    $routes->post('reject/(:num)', 'ResidentsController::reject/$1');
});

//Protected Donations Ledger Routes
$routes->group('donations', function($routes) {
    $routes->get('/', 'DonationsController::index');
    $routes->get('create', 'DonationsController::create');
    $routes->post('store', 'DonationsController::store');
});