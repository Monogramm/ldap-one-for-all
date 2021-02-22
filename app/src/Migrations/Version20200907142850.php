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
final class Version20200907142850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Release 0.1 initial migration.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE currency (id UUID NOT NULL, name VARCHAR(50) NOT NULL, iso_code VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN currency.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE api_token (id UUID NOT NULL, user_id UUID DEFAULT NULL, token TEXT NOT NULL, expired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BA2F5EBA76ED395 ON api_token (user_id)');
        $this->addSql('COMMENT ON COLUMN api_token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN api_token.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE parameter (id UUID NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(4096) NOT NULL, description TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN parameter.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, language VARCHAR(10) NOT NULL, roles JSON NOT NULL, is_verified BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE verification_code (id UUID NOT NULL, user_id UUID DEFAULT NULL, code VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E821C39FA76ED395 ON verification_code (user_id)');
        $this->addSql('COMMENT ON COLUMN verification_code.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN verification_code.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE background_job (id UUID NOT NULL, name VARCHAR(255) NOT NULL, last_execution TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN background_job.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE password_reset_code (id UUID NOT NULL, code TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN password_reset_code.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE verification_code ADD CONSTRAINT FK_E821C39FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('INSERT INTO currency(id, name, iso_code, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\', \'EURO\', \'EUR\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\'))');

        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'APP_SUPPORT_EMAIL\',\'\', \'string\', \'Support email address which will receive technical notifications\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'APP_PUBLIC_URL\',\'http://localhost:8000\', \'string\', \'Public URL for backend generated links\', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
        $this->addSql('INSERT INTO parameter(id, name, value, type, description, created_at, updated_at) VALUES (\''.Uuid::uuid4().'\',\'LDAP_USER_DEFAULT_ROLE\',\'ROLE_ADMIN\', \'string\', \'LDAP default role on first login. Valid values are: "ROLE_ADMIN" and no value for "ROLE_USER". \', (NOW() at time zone \'utc\'), (NOW() at time zone \'utc\') )');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_token DROP CONSTRAINT FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE verification_code DROP CONSTRAINT FK_E821C39FA76ED395');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE password_reset_code');
        $this->addSql('DROP TABLE verification_code');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE background_job');
    }
}
