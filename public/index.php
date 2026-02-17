<?php

use CodeIgniter\Boot;
use Config\Paths;

$minPhpVersion = '8.2';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Paths
require FCPATH . '../app/Config/Paths.php';
$paths = new Paths();

/**
 * ✅ INI YANG KURANG (autoload Composer)
 * Pastikan folder vendor ada di root project (selevel app/system/writable/vendor)
 */
if (is_file(FCPATH . '../vendor/autoload.php')) {
    require FCPATH . '../vendor/autoload.php';
}

// Boot CI
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
