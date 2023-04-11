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
final class Version20230411100606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD status VARCHAR(255) NOT NULL DEFAULT \'completed\'');
        $this->addSql('ALTER TABLE subscription ADD payment_details_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD has_trial BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE subscription ADD trial_length_days INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD start_of_current_period TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription DROP payment_external_reference');
        $this->addSql('ALTER TABLE subscription ALTER status SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN subscription.payment_details_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38EEC86F7 FOREIGN KEY (payment_details_id) REFERENCES payment_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A3C664D38EEC86F7 ON subscription (payment_details_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment DROP status');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D38EEC86F7');
        $this->addSql('DROP INDEX IDX_A3C664D38EEC86F7');
        $this->addSql('ALTER TABLE subscription ADD payment_external_reference VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription DROP payment_details_id');
        $this->addSql('ALTER TABLE subscription DROP has_trial');
        $this->addSql('ALTER TABLE subscription DROP trial_length_days');
        $this->addSql('ALTER TABLE subscription DROP start_of_current_period');
        $this->addSql('ALTER TABLE subscription ALTER status DROP NOT NULL');
    }
}
