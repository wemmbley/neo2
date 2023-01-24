<?php

declare(strict_types=1);

namespace Neo\Themes\Woundy\App\Pages;

use Neo\Core\App\Modules\Http\Request;

class Index
{
    public function render(Request $request)
    {
        $args = [
            'pageTitle' => 'Gusto Ax',
            'userName' => 'Mike'
        ];

        return view('index.php', $args);
    }
}