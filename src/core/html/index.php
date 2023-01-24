<?php

/*
|--------------------------------------------------------------------------
| Register Autoloader
|--------------------------------------------------------------------------
*/

require_once '../../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Debugging errors
|--------------------------------------------------------------------------
*/
if(env('APP_DEBUG')) {
    if (function_exists('ini_set')) {
       ini_set('display_errors', '1');
    }
}

/*
|--------------------------------------------------------------------------
| Bootstrap NEO
|--------------------------------------------------------------------------
*/
try {
    $bootstrap = new Neo\Core\App\Bootstrap();
    $bootstrap->init();
} catch (Throwable $exception) {
    handle_errors($exception);
}