<?php

declare(strict_types=1);

namespace Neo\Core\App\modules\http;

class Response
{
    private array $responseCodes = [
        'info' => [

        ],
        'successful' => [
            200 => '',
            201 => 'HTTP/1.1 201 Accepted'
        ],
        'redirect' => [
            300 => '',
            301 => 'HTTP/1.1 301 Moved Permanently',
            302 => '',
        ],
        'clientError' => [
            400 => 'HTTP/1.1 400 Bad Request',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            405 => 'HTTP/1.1 405 Method Not Allowed',
        ],
        'serverError' => [

        ],
    ];

    private array $headers = [];

    public function __construct(int $responseCode)
    {
        foreach ($this->responseCodes as $key) {
            foreach ($key as $code => $header) {
                if ($responseCode === $code) {
                    $this->headers[] = $header;
                }
            }
        }
    }

    public function makeHeader(string $header)
    {
        $this->headers[] = $header;
    }

    public function redirect(string $to)
    {

    }

    public function toJson()
    {
        $this->headers[] = 'Content-Type: application/json; charset=utf-8';
    }

    public function execute()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
    }
}