<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260315071357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('refresh_tokens');
        $table->addColumn('id', 'app_uuid', ['notnull' => true, 'length' => 36]);
        $table->addColumn('user_id', 'app_uuid', ['notnull' => true, 'length' => 36]);
        $table->addColumn('token_hash', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('family_id', 'app_uuid', ['notnull' => true, 'length' => 36]);
        $table->addColumn('is_revoked', 'boolean', ['notnull' => true]);
        $table->addColumn('expires_at', 'datetime', ['notnull' => true]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('refresh_tokens');
    }
}
