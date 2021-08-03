<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803005702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Registration setup (PostgreSQL)';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'APP_REGISTRATION_ENABLED\',\'1\', \'string\', \'Enable/disable user registration. Allowed values are 1 (enabled) and 0 (disabled).\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
    }

    public function down(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
