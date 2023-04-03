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
final class Version20230403113902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_details ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6CC295FD77153098 ON subscription_feature (code)');
        $this->addSql('ALTER TABLE subscription_plan ADD product_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription_plan ADD has_trial BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription_plan ADD trial_length_days INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription_plan RENAME COLUMN external_referencelink TO payment_provider_details_url');
        $this->addSql('COMMENT ON COLUMN subscription_plan.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_plan ADD CONSTRAINT FK_EA664B634584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EA664B634584665A ON subscription_plan (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_details DROP name');
        $this->addSql('DROP INDEX UNIQ_6CC295FD77153098');
        $this->addSql('ALTER TABLE subscription_plan DROP CONSTRAINT FK_EA664B634584665A');
        $this->addSql('DROP INDEX IDX_EA664B634584665A');
        $this->addSql('ALTER TABLE subscription_plan DROP product_id');
        $this->addSql('ALTER TABLE subscription_plan DROP has_trial');
        $this->addSql('ALTER TABLE subscription_plan DROP trial_length_days');
        $this->addSql('ALTER TABLE subscription_plan RENAME COLUMN payment_provider_details_url TO external_referencelink');
    }
}
