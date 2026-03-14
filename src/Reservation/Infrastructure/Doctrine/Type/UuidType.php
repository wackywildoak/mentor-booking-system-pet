<?php

declare(strict_types=1);

namespace App\Reservation\Infrastructure\Doctrine\Type;

use App\Reservation\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UuidType extends StringType
{
    public const NAME = 'app_uuid';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if ($value === null) {
            return null;
        }

        return new Uuid((string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value->value;
        }

        return (string) $value;
    }
}
