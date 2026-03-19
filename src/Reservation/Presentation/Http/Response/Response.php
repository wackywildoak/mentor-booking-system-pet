<?php

declare(strict_types=1);

namespace App\Reservation\Presentation\Http\Response;

class Response
{      
    private int $statusCode = 200;
    private array $headers = [
        'Content-Type' => 'application/json'
    ];
    private string $body = '';

    public function __construct($statusCode = 200, $headers = [], $body = '') {
        $this->statusCode = $statusCode;
        $this->headers = array_merge($this->headers, $headers);
        $this->body = $body;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo $this->body;
        exit;
    }

    public function json($data, $statusCode = 200): void
    {
        $this->statusCode = $statusCode;
        $this->headers['Content-Type'] = 'application/json';

        $response = [
            'status' => $statusCode < 400 ? 'success' : 'error',
            'data' => $data,
        ];

        $this->body = json_encode($response, JSON_UNESCAPED_UNICODE);
        $this->send();
    }
}