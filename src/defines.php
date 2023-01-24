<?php

/*
|--------------------------------------------------------------------------
| App defines
|--------------------------------------------------------------------------
*/
define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

/*
|--------------------------------------------------------------------------
| Array functions
|--------------------------------------------------------------------------
*/
function is_array_has_keys($requiredKeys, $array) {
    return count(array_intersect_key($requiredKeys, array_keys($array))) === count($requiredKeys);
}
function array_last(array $arr){
    return $arr[count($arr)-1];
}

/*
|--------------------------------------------------------------------------
| Dynamic classes
|--------------------------------------------------------------------------
*/
function create_class(string $class, ...$params)
{
    if(!class_exists($class)) {
        throw new \Exception('Class ' . $class . ' not found');
    }

    return new $class(...$params);
}
function call_method($object, string $method, ...$params): void
{
    if(!method_exists($object, $method)) {
        throw new Exception('Method ' . $method . ' not found in object ' . get_class($object));
    }

    $object->$method(...$params);
}

/*
|--------------------------------------------------------------------------
| Errors handler
|--------------------------------------------------------------------------
*/
function handle_errors($exception)
{
    if (!env('APP_DEBUG')) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        exit;
    }

    $fileLines = [];
    $handle = fopen($exception->getFile(), 'r');

    if ($handle) {
        $i = 0;
        $record = false;

        while (($line = fgets($handle)) !== false) {
            $i++;

            if ($i === $exception->getLine()-2
                || $i === $exception->getLine()-1
                || $i === $exception->getLine()
                || $i === $exception->getLine()+1
                || $i === $exception->getLine()+2
            ) {
                $fileLines[$i] = trim($line, PHP_EOL);
            }
        }

        fclose($handle);
    }

    $params = [
        'message' => $exception->getMessage(),
        'file' => ltrim($exception->getFile(), '/'),
        'fileLines' => $fileLines,
        'line' => $exception->getLine(),
        'trace' => $exception->getTrace(),
    ];

    ob_start();
    extract($params);
    include abspath('/core/html/error.php');
    ob_end_flush();
}

/*
|--------------------------------------------------------------------------
| Absolute root path
|--------------------------------------------------------------------------
*/
function abspath(string $path)
{
    return ROOT . $path;
}

/*
|--------------------------------------------------------------------------
| Turn warnings to exceptions
|--------------------------------------------------------------------------
*/
set_error_handler(function ($severity, $message, $file, $line) {
    throw new \ErrorException($message, $severity, $severity, $file, $line);
});

/*
|--------------------------------------------------------------------------
| Get .env value
|--------------------------------------------------------------------------
*/
function env(string $param)
{
    if (!file_exists(abspath('/.env'))) {
        throw new Exception('.env not found');
    }

    $env = file_get_contents(abspath('/.env'));
    preg_match_all('/(\w+)=(\w+)/', $env, $matches);

    foreach ($matches[0] as $fullParam) {
        $exp = explode('=', $fullParam);

        if ($param === $exp[0]) {
            return $exp[1];
        }
    }

    throw new Exception('Param ' . $param . ' not found in .env');
}