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
final class Version20230323093443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_feature (id UUID NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN subscription_feature.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE subscription_plan_subscription_feature (subscription_plan_id UUID NOT NULL, subscription_feature_id UUID NOT NULL, PRIMARY KEY(subscription_plan_id, subscription_feature_id))');
        $this->addSql('CREATE INDEX IDX_63CBB01D9B8CE200 ON subscription_plan_subscription_feature (subscription_plan_id)');
        $this->addSql('CREATE INDEX IDX_63CBB01DA201F81C ON subscription_plan_subscription_feature (subscription_feature_id)');
        $this->addSql('COMMENT ON COLUMN subscription_plan_subscription_feature.subscription_plan_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription_plan_subscription_feature.subscription_feature_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT FK_63CBB01D9B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT FK_63CBB01DA201F81C FOREIGN KEY (subscription_feature_id) REFERENCES subscription_feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT FK_63CBB01D9B8CE200');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT FK_63CBB01DA201F81C');
        $this->addSql('DROP TABLE subscription_feature');
        $this->addSql('DROP TABLE subscription_plan_subscription_feature');
    }
}
