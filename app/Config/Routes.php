<?php

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

/*
|--------------------------------------------------------------------------
| System Routes
|--------------------------------------------------------------------------
*/
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
|--------------------------------------------------------------------------
| Router Setup
|--------------------------------------------------------------------------
*/
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('User\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true); // disarankan false + route manual

/*
|--------------------------------------------------------------------------
| USER (Public)
|--------------------------------------------------------------------------
*/
$routes->get('/', 'User\Home::index');
$routes->get('subkategori', 'User\Home::subkategori');
$routes->post('lapor', 'User\Home::submitLaporan', ['filter' => 'authfilter']);

$routes->get('status', 'User\Status::index');
$routes->get('tentang', 'User\Tentang::index');
$routes->get('berita', 'User\Berita::index');
$routes->get('berita/(:segment)', 'User\Berita::detail/$1');

// Profil & Riwayat (butuh login)
$routes->get('profil', 'User\Profil::index', ['filter' => 'authfilter']);
$routes->get('riwayat', 'User\Riwayat::index', ['filter' => 'authfilter']);

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
$routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('register', 'Register::index');
    $routes->post('register', 'Register::store');

    // login via modal
    $routes->post('login', 'Login::authenticate');
    $routes->get('logout', 'Logout::index');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminfilter'], static function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Pengaduan
    $routes->get('pengaduan', 'Pengaduan::index');
    $routes->get('pengaduan/(:num)', 'Pengaduan::detail/$1');
    $routes->post('pengaduan/(:num)/status', 'Pengaduan::updateStatus/$1');

    // ✅ Manajemen Users
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users/store', 'Users::store');
    $routes->get('users/(:num)/edit', 'Users::edit/$1');
    $routes->post('users/(:num)/update', 'Users::update/$1');
    $routes->post('users/(:num)/toggle', 'Users::toggle/$1');

    $routes->get('berita', 'Berita::index');
    $routes->get('berita/create', 'Berita::create');
    $routes->post('berita/store', 'Berita::store');
    $routes->get('berita/(:num)', 'Berita::detail/$1');
    $routes->get('berita/(:num)/edit', 'Berita::edit/$1');
    $routes->post('berita/(:num)/update', 'Berita::update/$1');
    $routes->post('berita/(:num)/delete', 'Berita::delete/$1');
    $routes->post('berita/(:num)/toggle', 'Berita::toggle/$1'); // publish <-> draft


    $routes->get('analitik', 'Analitik::index');
    $routes->get('analitik/export-excel', 'Analitik::exportExcel');
    $routes->get('analitik/export-pdf', 'Analitik::exportPdf');


});

/*
|--------------------------------------------------------------------------
| Environment Routes
|--------------------------------------------------------------------------
*/
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
