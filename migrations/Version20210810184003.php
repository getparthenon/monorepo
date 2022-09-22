<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210810184003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parthenon_ab_experiment_variant (id UUID NOT NULL, experiment_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, percentage INT NOT NULL, is_default BOOLEAN NOT NULL, stats_number_of_sessions INT NOT NULL, stats_number_of_conversions INT NOT NULL, stats_conversion_percentage DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_284E93BDFF444C8 ON parthenon_ab_experiment_variant (experiment_id)');
        $this->addSql('CREATE INDEX search_idx ON parthenon_ab_experiment_variant (name)');
        $this->addSql('COMMENT ON COLUMN parthenon_ab_experiment_variant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN parthenon_ab_experiment_variant.experiment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE parthenon_ab_experiments (id UUID NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, desired_result VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, activated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6C53D6955E237E06 ON parthenon_ab_experiments (name)');
        $this->addSql('COMMENT ON COLUMN parthenon_ab_experiments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE parthenon_ab_experiment_variant ADD CONSTRAINT FK_284E93BDFF444C8 FOREIGN KEY (experiment_id) REFERENCES parthenon_ab_experiments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD subscription_updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users RENAME COLUMN subscription_validuntil TO subscription_valid_until');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE parthenon_ab_experiment_variant DROP CONSTRAINT FK_284E93BDFF444C8');
        $this->addSql('DROP TABLE parthenon_ab_experiment_variant');
        $this->addSql('DROP TABLE parthenon_ab_experiments');
        $this->addSql('ALTER TABLE users ADD subscription_validuntil TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP subscription_valid_until');
        $this->addSql('ALTER TABLE users DROP subscription_updated_at');
    }
}
