<?php

use App\Core\Router;

session_start();

ini_set("display_errors", true);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
//const SITE_PATH = 'http://localhost/shared-gallery';
define('SITE_PATH', 'http://localhost/shared-gallery');

spl_autoload_register(function ($className) {
    $class = lcfirst($className);
    $filename = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($filename)) {
        require $filename;
    }
});

$router = new Router();