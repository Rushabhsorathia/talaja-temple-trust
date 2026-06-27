<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Laravel 11 references PDO::MYSQL_ATTR_* constants in config/database.php that
// PHP 8.5 deprecated. The notice fires while config is loaded (before Laravel's
// exception handler is installed), so with display_errors on it gets written
// into the HTTP response body and corrupts Inertia's JSON page object. Stop
// deprecations from being *displayed* here, before bootstrap; all other
// warnings/errors still show in dev, and production has display_errors off.
error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
