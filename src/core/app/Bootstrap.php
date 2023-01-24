<?php

declare(strict_types=1);

namespace Neo\Core\App;

use Neo\Core\App\Modules\DB\DB;
use Neo\Core\App\Modules\Event\Event;
use Neo\Core\App\Modules\Http\Request;
use Neo\Core\App\Modules\Protector\Protector;
use Neo\Core\App\Modules\Router\Router;
use Neo\Core\App\Modules\View\View;
use Neo\Core\App\Modules\Yaml\YAML;

class Bootstrap
{
    protected Neo $neo;

    public function init(): void
    {
        session_start();

        DB::setup();

        $this->loadNeo();
        $this->loadPlugins();
        $this->loadTheme();

        $this->neo->module('router')->get()->process($this->neo);
    }

    private function loadNeo(): void
    {
        $providers = include abspath('/providers.php');

        $this->neo = new Neo();
        $this->neo['providers'] = $providers;
        $this->neo['modules']['router'] = new Router();
        $this->neo['modules']['view'] = new View;
        $this->neo['modules']['http']['request'] = $this->makeRequest();
    }

    private function loadPlugins(): void
    {
        $plugins = $this->neo['providers']['plugins'];

        foreach ($plugins as $plugin) {
            $expPlugin = explode('\\', $plugin);
            $className = $expPlugin[array_key_last($expPlugin)];

            $this->neo['plugins'][$className] = create_class($plugin, $this->neo);
        }
    }

    private function loadTheme(): void
    {
        $themeBootstrap = $this->neo['providers']['activeTheme'];

        create_class($themeBootstrap, $this->neo);
    }

    private function makeRequest(): Request
    {
        $url = Protector::str($_SERVER['REQUEST_URI']);
        $url = rtrim($url, '/');

        $request = new Request();
        $request->set(Request::URL, [
            'full' => $_SERVER['HTTP_HOST'] . $url,
            'relative' => $url,
            'split' => $this->splitUri($url)
        ]);
        $request->set(Request::METHOD, $_SERVER['REQUEST_METHOD']);
        $request->set(Request::HEADERS, headers_list());
        $request->set(Request::GET, $_GET);
        $request->set(Request::POST, $_POST);
        $request->set(Request::COOKIES, $_COOKIE);
        $request->set(Request::SESSION, $_SESSION);

        return $request;
    }

    private function splitUri(string $uri): array
    {
        $expUri = explode('/', $uri);
        unset($expUri[0]);

        return array_values($expUri);
    }
}