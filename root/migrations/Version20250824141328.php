<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250824141328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add source column to user table with default value app';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE user ADD source VARCHAR(255) NOT NULL DEFAULT 'app'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP source');
    }
}
