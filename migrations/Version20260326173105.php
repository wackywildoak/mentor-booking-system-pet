<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260326173105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mentor_profiles (company VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, about VARCHAR(255) NOT NULL, industry VARCHAR(255) NOT NULL, id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, balance_amount DOUBLE PRECISION NOT NULL, balance_currency VARCHAR(3) NOT NULL, hourlyRate_amount DOUBLE PRECISION NOT NULL, hourlyRate_currency VARCHAR(3) NOT NULL, PRIMARY KEY (id, user_id))');
        $this->addSql('DROP TABLE refresh_tokens');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refresh_tokens (id VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, user_id VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, token_hash VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, family_id VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_revoked TINYINT NOT NULL, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE mentor_profiles');
    }
}
