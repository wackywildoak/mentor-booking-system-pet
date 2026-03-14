<?php

namespace App\Reservation\Presentation\Http\Controller;

use App\Reservation\Domain\Repository\UserRepositoryInterface;
use App\Reservation\Presentation\Http\Shared\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function listUsers()
    {
        $users = $this->userRepository->all();
        $this->response($users);
    }

    public function getUserByEmail($email): void
    {
        $user = $this->userRepository->findByEmail($email);
        $this->response($user);
    }
}