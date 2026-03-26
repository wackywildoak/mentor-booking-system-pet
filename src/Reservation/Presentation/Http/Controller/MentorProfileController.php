<?php

namespace App\Reservation\Presentation\Http\Controller;

use App\Reservation\Application\DTO\MentorProfileResponse;
use App\Reservation\Domain\Repository\MentorProfileRepositoryInterface;
use App\Reservation\Presentation\Http\Request\Request;
use App\Reservation\Presentation\Http\Shared\AbstractController;

class MentorProfileController extends AbstractController
{
    public function __construct(
        protected Request $request,
        private MentorProfileRepositoryInterface $mentorProfileRepository,

    ) {}

    public function index(): void
    {
        $request = $this->getRequest();

        $user = $request->getUser();

        $profile = $this->mentorProfileRepository->findByUserId($user->id->value);

        $mentorProfileRespose = new MentorProfileResponse(
            name: $user->name,
            email: $user->email->value,
            hourlyRate: $profile->hourlyRate,
            balance: $profile->balance,
            company: $profile->company,
            position: $profile->position,
            about: $profile->about,
            industry: $profile->industry,
            status: $profile->status->value()
        );

        $this->response(data: [$mentorProfileRespose]);
    }
}