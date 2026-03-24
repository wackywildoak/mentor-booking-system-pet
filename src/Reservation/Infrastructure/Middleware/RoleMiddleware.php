<?php

namespace App\Reservation\Infrastructure\Middleware;

use App\Reservation\Domain\ValueObject\UserRole;
use App\Reservation\Presentation\Http\Request\Request;

class RoleMiddleware
{
    /**
     * @param UserRole[] $allowedRoles
     */
    public function handle(Request $request, array $allowedRoles): void
    {
        $user = $request->getUser();

        if (!in_array($user->role, $allowedRoles, true)) {
            header('Content-Type: application/json');
            header('HTTP/1.1 401 Forbidden');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
    }   
}