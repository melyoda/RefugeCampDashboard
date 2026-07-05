<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');
//dashboard routes

//public routes for registration
// $routes->group('household-register', ['namespace' => 'App\Controllers'], function($routes) {
//     $routes->get('/', 'RegisterController::index');
//     $routes->post('save', 'RegisterController::save');
// });
$routes->get('household-register', 'RegisterController::index');
$routes->post('household-register/save', 'RegisterController::save');

$routes->group('/', ['filter' => 'session'], function($routes){
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
});

//authentication routes
service('auth')->routes($routes);

//activities routes
$routes->group('activities', ['filter' => 'session'], function($routes) {
    $routes->get('/', 'ActivitiesController::index');
    $routes->get('create', 'ActivitiesController::create');
    $routes->post('store', 'ActivitiesController::store');
    $routes->get('edit/(:num)', 'ActivitiesController::edit/$1');
    $routes->post('update/(:num)', 'ActivitiesController::update/$1');
    // $routes->get('delete/(:num)', 'ActivitiesController::delete/$1');
    $routes->post('delete/(:num)', 'ActivitiesController::delete/$1');

    // Activity Details and Distribution Tracking
    $routes->get('show/(:num)', 'ActivitiesController::show/$1');
    $routes->post('save-distribution/(:num)', 'ActivitiesController::saveDistribution/$1');
});

// $routes->get('activities', 'ActivitiesController::index');
// $routes->get('activities/create', 'ActivitiesController::create');
// $routes->post('activities/store', 'ActivitiesController::store');
// $routes->get('activities/edit/(:num)', 'ActivitiesController::edit/$1');
// $routes->post('activities/update/(:num)', 'ActivitiesController::update/$1');
// // $routes->get('activities/delete/(:num)', 'ActivitiesController::delete/$1');
// $routes->post('activities/delete/(:num)', 'ActivitiesController::delete/$1');
// // Activity Details and Distribution Tracking
// $routes->get('activities/show/(:num)', 'ActivitiesController::show/$1');
// $routes->post('activities/save-distribution/(:num)', 'ActivitiesController::saveDistribution/$1');

//residents routes
$routes->group('residents', ['filter' => 'session'], function($routes) {
    $routes->get('/', 'ResidentsController::index');
    $routes->get('create', 'ResidentsController::create');
    $routes->post('store', 'ResidentsController::store');
    $routes->get('edit/(:num)', 'ResidentsController::edit/$1');
    $routes->post('update/(:num)', 'ResidentsController::update/$1');
    $routes->post('delete/(:num)', 'ResidentsController::delete/$1');
});

// $routes->get('residents', 'ResidentsController::index');
// $routes->get('residents/create', 'ResidentsController::create');
// $routes->post('residents/store', 'ResidentsController::store');
// $routes->get('residents/edit/(:num)', 'ResidentsController::edit/$1');
//$routes->post('residents/update Pis/(:num)', 'ResidentsController::update/$1');
// $routes->post('residents/update/(:num)', 'ResidentsController::update/$1');
// $routes->post('residents/delete/(:num)', 'ResidentsController::delete/$1');

// Donations Ledger Routes
$routes->group('donations', ['filter' => 'session'], function($routes) {
   $routes->get('/', 'DonationsController::index');        // Maps to: /donations
    $routes->get('create', 'DonationsController::create');  // Maps to: /donations/create
    $routes->post('store', 'DonationsController::store');    // Maps to: /donations/store
});