<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211030100143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Security question (PostgreSQL)';
    }

    public function up(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE security_answer (id UUID NOT NULL, user_id UUID DEFAULT NULL, question_id UUID DEFAULT NULL, answer VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC32BC30A76ED395 ON security_answer (user_id)');
        $this->addSql('CREATE INDEX IDX_EC32BC301E27F6BF ON security_answer (question_id)');
        $this->addSql('COMMENT ON COLUMN security_answer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN security_answer.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN security_answer.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE security_question (id UUID NOT NULL, name VARCHAR(255) NOT NULL, i18n JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D59CCE9C5E237E06 ON security_question (name)');
        $this->addSql('COMMENT ON COLUMN security_question.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE security_answer ADD CONSTRAINT FK_EC32BC30A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE security_answer ADD CONSTRAINT FK_EC32BC301E27F6BF FOREIGN KEY (question_id) REFERENCES security_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE security_answer DROP CONSTRAINT FK_EC32BC301E27F6BF');
        $this->addSql('DROP TABLE security_answer');
        $this->addSql('DROP TABLE security_question');
    }
}
