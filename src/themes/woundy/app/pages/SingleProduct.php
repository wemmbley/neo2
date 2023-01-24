<?php

declare(strict_types=1);

namespace Neo\Themes\Woundy\App\Pages;

use Neo\Core\App\Modules\Http\Request;

class SingleProduct
{
    public function render(Request $request)
    {
        $productSlug = $request->get('url')['split'][1];

        $args = [
            'pageTitle' => 'Air Pods 3',
            'mainImage' => 'https://itc.ua/wp-content/uploads/2021/12/01-scaled.jpg',
            'title' => 'AirPods 3',
            'description' => 'lorem ipsum dolor lorem ipsum dolor lorem ipsum dolor lorem ipsum dol',
        ];

        return view('single-product.php', $args);
    }
}