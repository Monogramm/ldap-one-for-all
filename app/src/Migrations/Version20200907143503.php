<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Message\ServerConfigure;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200907143503 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Release 0.1 initial migration.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE currency (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(50) NOT NULL, iso_code VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE parameter (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, value CLOB NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE api_token (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , token CLOB NOT NULL, expired_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BA2F5EBA76ED395 ON api_token (user_id)');
        $this->addSql('CREATE TABLE "user" (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, language VARCHAR(10) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , is_verified BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE verification_code (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , code VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E821C39FA76ED395 ON verification_code (user_id)');
        $this->addSql('CREATE TABLE background_job (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, last_execution DATETIME NOT NULL, status VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE password_reset_code (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , code CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');

        $this->addSql('INSERT INTO currency(id, name, iso_code, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\', \'EURO\', \'EUR\', date(\'now\'), date(\'now\'))');

        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'APP_SUPPORT_EMAIL\',\'\', \'string\', \'Support email address which will receive technical notifications\', date(\'now\'), date(\'now\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'APP_PUBLIC_URL\',\'http://localhost:8000\', \'string\', \'Public URL for backend generated links\', date(\'now\'), date(\'now\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_USER_DEFAULT_ROLE\',\'ROLE_ADMIN\', \'string\', \'LDAP default role on first login. Valid values are: "ROLE_ADMIN" and no value for "ROLE_USER".\', date(\'now\'), date(\'now\') )');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE password_reset_code');
        $this->addSql('DROP TABLE verification_code');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE background_job');
    }
}
