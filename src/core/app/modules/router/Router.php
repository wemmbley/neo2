<?php

declare(strict_types=1);

namespace Neo\Core\App\Modules\Router;

use Neo\Core\App\Modules\Event\Event;
use Neo\Core\App\Modules\Http\Request;
use Neo\Core\App\Neo;

class Router
{
    protected array $routes = [];

    public function get(string $url, array $action): Router
    {
        $this->routes['GET'][$url] = $action;

        return $this;
    }

    public function post(string $url, array $action): Router
    {
        $this->routes['POST'][$url] = $action;

        return $this;
    }

    public function process(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        switch ($request->get('Method')) {
            case 'GET': {
                Event::trigger('routerGetRequest');

                $this->loadAssets($request);
                $this->validateGet($neo);

                break;
            }
            case 'POST': {
                Event::trigger('routerPostRequest');

                $this->validatePost($neo);

                break;
            }
        }

        return true;
    }

    private function validatePost(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        if(!isset($this->routes['POST']) || empty($this->routes['POST'])) {
            header('HTTP/1.0 404 Not Found');
            die;
        }

        if($request->get('Method') !== 'POST') {
            header('HTTP/1.0 405 Method Not Allowed');
            die;
        }

        if(!$this->foreachRoutes($this->routes['POST'], $neo)) {
            header('HTTP/1.0 404 Not Found');
            echo '404';
            die;
        }
    }

    private function validateGet(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        if(!isset($this->routes['GET']) || empty($this->routes['GET'])) {
            header('HTTP/1.0 404 Not Found');
            die;
        }

        if($request->get('Method') !== 'GET') {
            header('HTTP/1.0 405 Method Not Allowed');
            die;
        }

        if(!$this->foreachRoutes($this->routes['GET'], $neo)) {
            header("HTTP/1.0 404 Not Found");
            echo '404';
            die;
        }
    }

    private function loadAssets(Request $request)
    {
        $htmlFoldersRegex = '/\/(core)\/html\/|\/(plugins)\/(\w+)\/html\/|\/(themes)\/(\w+)\/html\//';

        preg_match($htmlFoldersRegex, $request->get('url')['relative'], $htmlUrl);

        if ($htmlUrl) {

            if (isset($htmlUrl[2]) && $htmlUrl[2] === 'plugins' && isset($htmlUrl[3])
                || isset($htmlUrl[2]) && $htmlUrl[2] === 'themes' && isset($htmlUrl[3])
                || isset($htmlUrl[1]) && $htmlUrl[1] === 'core'
            ) {

                if (!file_exists(abspath($request->get('url')['relative']))) {
                    header('HTTP/1.0 404 Not Found');
                    die;
                }

                $contentType = 'unknown/unknown';
                $fileExtension = pathinfo(abspath($request->get('url')['relative']))['extension'];

                switch ($fileExtension) {
                    case 'css': {
                        $contentType = 'text/css';
                        break;
                    }
                    case 'js': {
                        $contentType = 'text/javascript';
                        break;
                    }
                    default: {
                        $contentType = mime_content_type(abspath($request->get('url')['relative']));
                        break;
                    }
                }

                header('Content-Type: ' . $contentType);
                header('HTTP/1.0 200 OK');

                include abspath($request->get('url')['relative']);

                die;
            }

            header('HTTP/1.0 404 Not Found');
            die;
        }
    }

    private function foreachRoutes(array $array, Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        foreach ($array as $url => $route) {
            if(preg_match('/' . $url . '/', $request->get('url')['relative'], $matches)) {
                $neo['routeMatches'] = $matches;

                [$class, $method] = [$route[0], $route[1]];

                $this->callMethod($neo, $class, $method);

                return true;
            }
        }

        return false;
    }

    private function callMethod(Neo $neo, string $class, string $method)
    {
        $obj = create_class($class, $neo);
        call_method($obj, $method, $neo);
    }
}