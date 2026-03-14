<?php

declare(strict_types=1);

namespace App\Reservation\Presentation\Http\Shared;

use App\Reservation\Presentation\Http\Response\Response;

abstract class AbstractController
{
    public function response(
        mixed $data = null,
        int $statusCode = 200,
        array $headers = []
    ): void
    {
        $response = new Response($statusCode, $headers);
        $response->json($data, $statusCode);
    }
}