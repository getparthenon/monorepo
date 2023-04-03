<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403131403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription (id UUID NOT NULL, price_id UUID DEFAULT NULL, subscription_plan_id UUID DEFAULT NULL, customer_id UUID DEFAULT NULL, plan_name VARCHAR(255) DEFAULT NULL, payment_schedule VARCHAR(255) DEFAULT NULL, main_subscription BOOLEAN NOT NULL, seats INT DEFAULT NULL, active BOOLEAN DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, amount INT DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, external_reference VARCHAR(255) DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3C664D3D614C7E7 ON subscription (price_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D39B8CE200 ON subscription (subscription_plan_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D39395C3F3 ON subscription (customer_id)');
        $this->addSql('COMMENT ON COLUMN subscription.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription.price_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription.subscription_plan_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D39B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D39395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3D614C7E7');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D39B8CE200');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D39395C3F3');
        $this->addSql('DROP TABLE subscription');
    }
}
