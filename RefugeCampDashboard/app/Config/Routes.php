<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');
//dashboard routes
$routes->get('/', 'DashboardController::index');
$routes->get('dashboard', 'DashboardController::index');

//authentication routes
service('auth')->routes($routes);

//activities routes
$routes->get('activities', 'ActivitiesController::index');
$routes->get('activities/create', 'ActivitiesController::create');
$routes->post('activities/store', 'ActivitiesController::store');
$routes->get('activities/edit/(:num)', 'ActivitiesController::edit/$1');
$routes->post('activities/update/(:num)', 'ActivitiesController::update/$1');
// $routes->get('activities/delete/(:num)', 'ActivitiesController::delete/$1');
$routes->post('activities/delete/(:num)', 'ActivitiesController::delete/$1');

//residents routes
$routes->get('residents', 'ResidentsController::index');
$routes->get('residents/create', 'ResidentsController::create');
$routes->post('residents/store', 'ResidentsController::store');
$routes->get('residents/edit/(:num)', 'ResidentsController::edit/$1');
// $routes->post('residents/update Pis/(:num)', 'ResidentsController::update/$1');
$routes->post('residents/update/(:num)', 'ResidentsController::update/$1');
$routes->post('residents/delete/(:num)', 'ResidentsController::delete/$1');