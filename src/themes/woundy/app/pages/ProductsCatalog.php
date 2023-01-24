<?php

declare(strict_types=1);

namespace Neo\Themes\Woundy\App\Pages;

use Neo\Core\App\Modules\Http\Request;
use Neo\Themes\Woundy\App\Woundy;

class ProductsCatalog extends Woundy
{
    public function render(Request $request)
    {

        $args = [
            'page' => 1
        ];

        if (isset($request->get('url')['split'][1])) {
            $args['page'] = $request->get('url')['split'][1];
        }

        //$request->getInput();
        // взять из реквеста номер странички
        // получить из БД 25 товаров
        $products = [];

        $args[] = [
            'pageTitle' => 'Catalog',
            'products' => $products,
        ];

        $view = $this->neo->module('view')->get();

        return $view->render('products-catalog.php', $args);
    }
}