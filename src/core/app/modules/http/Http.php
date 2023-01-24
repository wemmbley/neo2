<?php

declare(strict_types=1);

namespace Neo\Core\App\modules\http;

class Http
{
    public function request()
    {
        $request = new Request();

        return $request;
    }

    public function response()
    {
        $response = new Response();

        return $response;
    }
}