<?php

declare(strict_types=1);

namespace Neo\Core\App\Modules\Http;

class Request
{
    protected array $userInput = [];

    const URL = 'url';
    const METHOD = 'Method';
    const HEADERS = 'Headers';
    const GET = 'GET';
    const POST = 'POST';
    const COOKIES = 'Cookies';
    const SESSION = 'Session';

    public function get(string $name = ''): mixed
    {
        if(empty($name)) {
            return $this->userInput;
        }

        return $this->userInput[$name];
    }

    public function set(string $name, mixed $value): void
    {
        $this->userInput[$name] = $value;
    }
}