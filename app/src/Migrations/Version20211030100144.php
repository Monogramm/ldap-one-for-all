<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211030100144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Security question (SQLite)';
    }

    public function up(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE security_question (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, i18n CLOB NOT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D59CCE9C5E237E06 ON security_question (name)');
        $this->addSql('CREATE TABLE security_answer (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , question_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , answer VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC32BC30A76ED395 ON security_answer (user_id)');
        $this->addSql('CREATE INDEX IDX_EC32BC301E27F6BF ON security_answer (question_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE security_answer');
        $this->addSql('DROP TABLE security_question');
    }
}
