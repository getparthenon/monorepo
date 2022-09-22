<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422211654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE parthenon_rule_engine_rule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE parthenon_rule_engine_rule_execution_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE parthenon_rule_engine_rule (id INT NOT NULL, entity VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, comparison VARCHAR(255) NOT NULL, field VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, options JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE parthenon_rule_engine_rule_execution_log (id INT NOT NULL, entity_name VARCHAR(255) NOT NULL, field_name VARCHAR(255) NOT NULL, entity_id VARCHAR(255) NOT NULL, value JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE team_invite_codes ADD role VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP is_admin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE parthenon_rule_engine_rule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE parthenon_rule_engine_rule_execution_log_id_seq CASCADE');
        $this->addSql('DROP TABLE parthenon_rule_engine_rule');
        $this->addSql('DROP TABLE parthenon_rule_engine_rule_execution_log');
        $this->addSql('ALTER TABLE team_invite_codes DROP role');
        $this->addSql('ALTER TABLE users ADD is_admin BOOLEAN NOT NULL');
    }
}
