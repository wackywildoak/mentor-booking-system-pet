<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ValueObject\Uuid;
use App\Reservation\Domain\ValueObject\Email;
use App\Reservation\Domain\ValueObject\UserRole;

class User
{
    /** @var Uuid Уникальный идентификатор пользователя */
    public Uuid $id {
        get => $this->id;
        set => $value;
    }

    /** @var Email Электронная почта пользователя */
    public Email $email {
        get => $this->email;
        set => $value;
    }

    /** @var string Имя пользователя */
    public string $name {
        get => $this->name;
        set => $value;
    }

    /** @var string Хэш пароля пользователя */
    public string $passwordHash {
        get => $this->passwordHash;
        set => $value;
    }

    /** @var UserRole Роль пользователя (mentor, client, admin) */
    public UserRole $role {
        get => $this->role;
        set => $value;
    }

    /** @var \DateTime Дата создания пользователя */
    public \DateTime $createdAt {
        get => $this->createdAt;
        set => $value;
    }

    public function __construct(
        Uuid $id,
        Email $email,
        string $name,
        string $passwordHash,
        UserRole $role,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    public function isMentor(): bool
    {
        return $this->role->isMentor();
    }

    public function isClient(): bool
    {
        return $this->role->isClient();
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }
}