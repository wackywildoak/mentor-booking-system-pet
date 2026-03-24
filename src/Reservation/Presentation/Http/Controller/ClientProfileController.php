<?php

namespace App\Reservation\Presentation\Http\Controller;

use App\Reservation\Application\DTO\ClientProfileResponse;
use App\Reservation\Domain\Repository\ClientProfileRepositoryInterface;
use App\Reservation\Presentation\Http\Request\Request;
use App\Reservation\Presentation\Http\Shared\AbstractController;

class ClientProfileController extends AbstractController
{
    public function __construct(
        protected Request $request,
        private ClientProfileRepositoryInterface $clientProfileRepository,

    ) {}

    public function index(): void
    {
        $request = $this->getRequest();

        $user = $request->getUser();

        $profile = $this->clientProfileRepository->findByUserId($user->id->value);

        $clientProfileRespose = new ClientProfileResponse(
            name: $user->name,
            email: $user->email->value,
            balance: $profile->balance,
            company: $profile->company,
            position: $profile->position,
            about: $profile->about,
            industry: $profile->industry
        );

        $this->response(data: [$clientProfileRespose]);
    }
}