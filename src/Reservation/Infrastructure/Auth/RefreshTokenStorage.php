<?php

declare(strict_types=1);

namespace App\Reservation\Infrastructure\Auth;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

class RefreshTokenStorage
{
    private Connection $connection;
    private string $refreshTokenTable = 'refresh_tokens';

    public function __construct(
        private EntityManager $entityManager
    )
    {
        $this->connection = $entityManager->getConnection();
    }

    public function store(
        string $id,
        string $userId, 
        string $tokenHash,
        string $familyId,
        bool $isRevoked,
    ): void
    {
        $this->connection->insert($this->refreshTokenTable, [
            'id' => $id,
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'family_id' => $familyId,
            'is_revoked' => $isRevoked,
        ]);
    }

    public function find(string $where, string $parameter)
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from($this->refreshTokenTable)
            ->where("{$where}=:value")
            ->setParameter('value', $parameter)
            ->executeQuery()
            ->fetchAssociative();

        return $result;
    }

    public function update(
        string $id,
        ?string $tokenHash = null,
        ?string $familyId = null,
        ?bool $isRevoked = null,
    ): void
    {
        $data = [];

        if ($tokenHash !== null) {
            $data['token_hash'] = $tokenHash;
        }

        if ($familyId !== null) {
            $data['family_id'] = $familyId;
        }

        if ($isRevoked !== null) {
            $data['is_revoked'] = $isRevoked;
        }

        if (empty($data)) {
            return;
        }

        $this->connection->update(
            $this->refreshTokenTable,
            $data,
            ['id' => $id]
        );
    }

    public function delete(string $userId): void
    {
        $this->connection->delete($this->refreshTokenTable, ['user_id' => $userId]);
    }
}

