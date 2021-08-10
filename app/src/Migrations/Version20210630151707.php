<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630151707 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'LDAP Groups (SQLite)';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_GROUP_ADMIN\',\'\', \'string\', \'LDAP Group DN associated to Administrator role (ROLE_ADMIN).\', date(\'now\'), date(\'now\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_GROUP_USER\',\'\', \'string\', \'LDAP Group DN associated to User role (ROLE_USER).\', date(\'now\'), date(\'now\') )');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DELETE FROM parameter WHERE name IN (\'LDAP_GROUP_ADMIN\', \'LDAP_GROUP_USER\')');
    }
}
