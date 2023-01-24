<?php

declare(strict_types=1);

namespace Neo\Themes\Woundy\App;

use Neo\Core\App\Neo;
use Neo\Plugins\Dashboard\App\Dashboard;
use Neo\Themes\Woundy\App\Pages\About;
use Neo\Themes\Woundy\App\Pages\Index;
use Neo\Themes\Woundy\App\Pages\ProductsCatalog;
use Neo\Themes\Woundy\App\Pages\SingleProduct;

class Woundy
{
    protected Neo $neo;

    public function __construct(Neo $neo)
    {
        $this->neo = $neo;
        $this->defineRoutes();

        $dashboard = new Dashboard($neo);
        $dashboard->addItems([
            [],
            [
                'name' => 'Заказы',
                'href' => '/admin/orders',
                'icon' => 'fas fa-shopping-cart'
            ],
            [
                'name' => 'Товары',
                'icon' => 'fas fa-box',
                'items' => [
                    [
                        'name' => 'Список товаров',
                        'href' => '/admin/products',
                    ],
                    [
                        'name' => 'Категории',
                        'href' => '/admin/categories',
                    ],
                    [
                        'name' => 'Бренды',
                        'href' => '/admin/brands',
                    ],
                    [
                        'name' => 'Акции',
                        'href' => '/admin/discounts',
                    ],
                ],
            ],
            [
                'name' => 'Меню',
                'icon' => 'fas fa-th-large',
                'href' => '/admin/menu',
            ],
            [],
            [
                'name' => 'Настройки',
                'href' => '/admin/settings',
                'icon' => 'fas fa-cog',
            ],
        ]);
        $dashboard->init();

    }

    protected function defineRoutes()
    {
        $router = $this->neo->module('router')->get();

        $router->get('^\/$', [Index::class, 'render']);
        $router->get('^\/about$', [About::class, 'view']);
        $router->get('^\/(catalog)\/([0-9]+)$|^\/(catalog)$', [ProductsCatalog::class, 'render']);
        $router->get('^\/product\/([a-zA-Z0-9-]+)$', [SingleProduct::class, 'render']);
    }
}