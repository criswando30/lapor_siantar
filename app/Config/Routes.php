<?php

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

/*
 * Load the system's routing file first, so that the app and ENVIRONMENT
 * can override as needed.
 */
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * Router Setup
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true); // boleh true kalau kamu mau, tapi aman kalau false + route manual

/*
 * Routes Definitions
 */
$routes->get('/', 'Home::index');
$routes->get('status', 'Home::status');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');

