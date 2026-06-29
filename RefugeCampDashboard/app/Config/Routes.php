<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');
$routes->get('/', 'DashboardController::index');
$routes->get('dashboard', 'DashboardController::index');

service('auth')->routes($routes);
