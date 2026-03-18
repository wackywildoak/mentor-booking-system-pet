<?php

declare(strict_types=1);

namespace App\Reservation\Presentation\Http\Request;

class Request
{
    public function __construct(
        public array $data,
        public array $query,
        public array $headers,
        public array $cookies,
        public array $files,
    ) {}

    public static function fromGlobals(): self
    {
        return new self(
            data: $_POST,
            query: $_GET,
            headers: getallheaders(),
            cookies: $_COOKIE,
            files: $_FILES,
        );
    }
    
    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }
}