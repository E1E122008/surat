<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = _DIR_.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require _DIR_.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once _DIR_.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());