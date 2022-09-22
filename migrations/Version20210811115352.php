<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210811115352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teams (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subscription_price_id VARCHAR(255) DEFAULT NULL, subscription_plan_name VARCHAR(255) DEFAULT NULL, subscription_payment_schedule VARCHAR(255) DEFAULT NULL, subscription_active BOOLEAN DEFAULT NULL, subscription_status VARCHAR(255) DEFAULT NULL, subscription_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subscription_valid_until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subscription_updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subscription_ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subscription_payment_id VARCHAR(255) DEFAULT NULL, subscription_checkout_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN teams.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users DROP subscription_price_id');
        $this->addSql('ALTER TABLE users DROP subscription_plan_name');
        $this->addSql('ALTER TABLE users DROP subscription_payment_schedule');
        $this->addSql('ALTER TABLE users DROP subscription_active');
        $this->addSql('ALTER TABLE users DROP subscription_status');
        $this->addSql('ALTER TABLE users DROP subscription_started_at');
        $this->addSql('ALTER TABLE users DROP subscription_valid_until');
        $this->addSql('ALTER TABLE users DROP subscription_ended_at');
        $this->addSql('ALTER TABLE users DROP subscription_payment_id');
        $this->addSql('ALTER TABLE users DROP subscription_checkout_id');
        $this->addSql('ALTER TABLE users DROP subscription_updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE teams');
        $this->addSql('ALTER TABLE users ADD subscription_price_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_plan_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_payment_schedule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_active BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_valid_until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_payment_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_checkout_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD subscription_updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }
}
