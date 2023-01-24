<?php

declare(strict_types=1);

namespace Neo\Plugins\Dashboard\App\Modules\Auth;

use Neo\Core\App\Modules\DB\DB;
use Neo\Core\App\Modules\Http\Request;
use Neo\Core\App\Modules\Protector\Protector;
use Neo\Core\App\Neo;

// @todo ограничить до 5 попыток входа в час, с помощью нового поля attempts и last_login
// @todo реализовать авторизацию, ограничивающие разделы админки с помощью нового поля role_id
// @todo шифрование паролей с солью
// @todo сепарация модуля авторизации от плагина dashboard в независимый кор модуль
// @todo рефакторинг полученных изменений

/*
 * Messages list:
 * - token expired
 * - bad password
 * - user not found
 * - empty login
 * - empty password
 * - empty csrf
 */
class Auth
{
    private Neo $neo;

    public function __construct(Neo $neo)
    {
        $this->neo = $neo;
    }

    public function bootRoutes()
    {
        $router = $this->neo->module('router')->get();
        $router->get('^\/admin', [$this::class, 'routeGetAdmin']);
        $router->get('^\/auth\/logout$', [$this::class, 'routeGetLogout']);
        $router->post('^\/auth\/login$', [$this::class, 'routePostLogin']);
    }

    private function loadDashboard(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        $tokenUser = DB::query("select * from users where token = ?;", [Protector::str($request->get('Cookies')['userToken'])])
            ->toArray();

        if (!empty($tokenUser)) {
            $args = [
                'login' => $tokenUser[0]['login'],
                'url' => $request->get('url'),
                'menuHtml' => $this->neo['plugins']['dashboard']['html']['menu'],
                'pageHtmlPath' => isset($request->get('url')['split'][1]) ? abspath('/themes/' . env('THEME_ACTIVE') . '/html/admin/' . $request->get('url')['split'][1] . '.php') : '',
            ];

            $view = $this->neo->module('view')->get();
            $view->viewPlugin('dashboard', 'dashboard.php', $args);

            return;
        }

        header('Location: /auth/logout');

        echo json_encode([
            'message' => 'token expired'
        ]);

        die;
    }

    public function routeGetAdmin(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        if (isset($request->get('Cookies')['userToken'])) {
            $this->loadDashboard($neo);

            return;
        }

        $view = $this->neo->module('view')->get();
        $view->viewPlugin('dashboard', 'login.php');
    }

    public function routeGetLogout(Neo $neo)
    {
        setcookie('userToken', '', time()-3600, '/');

        header('Location: /admin');
    }

    public function routePostLogin(Neo $neo)
    {
        $request = $neo->module('http')->get('request');

        $this->validateRoutePostLogin($request);
        $user = $this->selectUser($request);

        if ($request->get('POST')['password'] === $user[0]['password']) {
            if (isset($request->get('Cookies')['userToken'])) {
                $this->loadDashboard($request);
            }

            $login = $request->get('POST')['login'];
            $token = bin2hex(random_bytes(32));

            DB::query("update users set token=? where login = ?", [$token, $login]);

            setcookie('userToken', $token, time()+60*60*24, '/');

            header('Location: /admin');
        } else {
            header('HTTP/1.1 403 Forbidden');

            echo json_encode([
                'message' => 'bad password'
            ]);

            die;
        }
    }

    private function selectUser(Request $request)
    {
        $user = DB::query("select * from users where login = ?;", [Protector::str($request->get('POST')['login'])])
            ->toArray();

        if (empty($user)) {
            header('HTTP/1.1 403 Forbidden');

            echo json_encode([
                'message' => 'user not found'
            ]);

            die;
        }

        return $user;
    }

    private function validateRoutePostLogin(Request $request)
    {
        if (!isset($request->get('POST')['login'])) {
            header('HTTP/1.1 403 Forbidden');

            echo json_encode([
                'message' => 'empty login'
            ]);

            die;
        }

        if (!isset($request->get('POST')['password'])) {
            header('HTTP/1.1 403 Forbidden');

            echo json_encode([
                'message' => 'empty password'
            ]);

            die;
        }

        if (!Protector::isCsrf()) {
            header('HTTP/1.1 403 Forbidden');

            echo json_encode([
                'message' => 'empty csrf'
            ]);

            die;
        }
    }
}