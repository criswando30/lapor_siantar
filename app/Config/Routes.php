<?php

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('User\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true); // disarankan false + route manual

/*
|--------------------------------------------------------------------------
| USER (Public pages)
|--------------------------------------------------------------------------
*/
$routes->get('/', 'User\Home::index');
$routes->get('status', 'User\Status::index');
$routes->get('tentang', 'User\Tentang::index');
$routes->get('berita', 'User\Berita::index');

// Profil & Riwayat (umumnya butuh login)
$routes->get('profil', 'User\Profil::index', ['filter' => 'authfilter']);
$routes->get('riwayat', 'User\Riwayat::index', ['filter' => 'authfilter']);

/*
|--------------------------------------------------------------------------
| AUTH (Login/Register/Logout)
|--------------------------------------------------------------------------
*/
$routes->get('register', 'Auth\Register::index');
$routes->post('register', 'Auth\Register::store');

$routes->post('login', 'Auth\Login::authenticate');
$routes->get('logout', 'Auth\Logout::index');

/*
|--------------------------------------------------------------------------
| LAPORAN (Submit pengaduan)
|--------------------------------------------------------------------------
*/
$routes->post('lapor', 'User\Home::submitLaporan', ['filter' => 'authfilter']);

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
$routes->group('admin', ['filter' => 'authfilter'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('pengaduan', 'Admin\Pengaduan::index');
    $routes->get('pengaduan/(:num)', 'Admin\Pengaduan::detail/$1');          // ✅ detail
    $routes->post('pengaduan/(:num)/status', 'Admin\Pengaduan::updateStatus/$1'); // ✅ update status

    $routes->get('users', 'Admin\Users::index');
    $routes->get('berita', 'Admin\Berita::index');
    $routes->get('analitik', 'Admin\Analitik::index');
});


    // Auth admin kalau kamu pakai halaman login admin terpisah:
    // $routes->get('login', 'Admin\Auth\Login::index');
    // $routes->post('login', 'Admin\Auth\Login::authenticate');
    // $routes->get('logout', 'Admin\Auth\Logout::index');
