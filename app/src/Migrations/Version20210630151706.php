<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630151706 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'LDAP Groups (PostgreSQL)';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_GROUP_ADMIN\',\'\', \'string\', \'LDAP Group DN associated to Administrator role (ROLE_ADMIN).\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_GROUP_USER\',\'\', \'string\', \'LDAP Group DN associated to User role (ROLE_USER).\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
    }

    public function down(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM parameter WHERE name IN (\'LDAP_GROUP_ADMIN\', \'LDAP_GROUP_USER\')');
    }
}
