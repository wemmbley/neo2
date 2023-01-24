<?php

declare(strict_types=1);

namespace Neo\Themes\Woundy\App\Pages;

use Neo\Core\App\Neo;

class About
{
    public function view(Neo $neo)
    {
        $user = $neo['plugins']['Dashboard']->getAuthUser();

        $args = [
            'pageTitle' => 'About',
            'isAuth' => $neo['plugins']['Dashboard']->isAuth()
        ];

        if($user) {
            $args['login'] = $user[0]['login'];
        }

        $view = $neo->module('view')->get();
        $view->viewTheme('about.php', $args);
    }
}