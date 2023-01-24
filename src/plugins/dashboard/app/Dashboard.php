<?php

declare(strict_types=1);

namespace Neo\Plugins\Dashboard\App;

use Neo\Core\App\Modules\DB\DB;
use Neo\Core\App\Modules\Protector\Protector;
use Neo\Core\App\Neo;
use Neo\Plugins\Dashboard\App\Modules\Auth\Auth;
use Neo\Plugins\Dashboard\App\Modules\Menu\Menu;

class Dashboard
{
    private Neo $neo;
    private Menu $menu;

    public function __construct(Neo $neo)
    {
        $this->neo = $neo;
        $this->menu = new Menu($this->neo);
    }

    public function addItems(array $params)
    {
        foreach ($params as $param) {
            $this->menu->addItem($param);
        }

        $this->menu->setMenuHtml();
        $this->neo['plugins']['dashboard']['html']['menu'] = $this->menu->getMenuHtml();
    }

    public function bootRoutes()
    {
        $auth = new Auth($this->neo);
        $auth->bootRoutes();
    }

    public function init()
    {
        $this->bootRoutes();
    }

    public function isAuth(bool $returnUserAuthId = false)
    {
        $request = $this->neo->module('http')->get('request');

        if (!isset($request->get('Cookies')['userToken'])) {
            return false;
        }

        $tokenUser = DB::query("select id,token from users where token = ?;", [Protector::str($request->get('Cookies')['userToken'])])
            ->toArray();

        if (empty($tokenUser))
            return false;

        if (isset($request->get('Cookies')['userToken']) && $request->get('Cookies')['userToken'] === $tokenUser[0]['token']) {
            if ($returnUserAuthId) {
                return $tokenUser[0]['id'];
            }

            return true;
        }

        return false;
    }

    public function getAuthUser(array $fields = [])
    {
        $userId = $this->isAuth(true);

        if (!empty($userId)) {
            $query = 'select ';

            if (!empty($fields)) {
                for ($i=0; $i<count($fields); $i++) {
                    if ($i-1 === count($fields)) {
                        $query .= $fields[$i];
                    } else {
                        $query .= $fields[$i] . ',';
                    }
                }
            } else {
                $query .= '*';
            }

            $query .= ' from users where id = ?';

            return DB::query($query, [$userId])->toArray();
        }

        return false;
    }
}