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
final class Version20230322134207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_plan_limit (id UUID NOT NULL, subscription_limit_id UUID DEFAULT NULL, subscription_plan_id UUID DEFAULT NULL, "limit" VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EED5EDF9C6F31ED6 ON subscription_plan_limit (subscription_limit_id)');
        $this->addSql('CREATE INDEX IDX_EED5EDF99B8CE200 ON subscription_plan_limit (subscription_plan_id)');
        $this->addSql('COMMENT ON COLUMN subscription_plan_limit.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription_plan_limit.subscription_limit_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription_plan_limit.subscription_plan_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_plan_limit ADD CONSTRAINT FK_EED5EDF9C6F31ED6 FOREIGN KEY (subscription_limit_id) REFERENCES subscription_limit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_limit ADD CONSTRAINT FK_EED5EDF99B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan ADD limits_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN subscription_plan.limits_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_plan ADD CONSTRAINT FK_EA664B63BAB8FB66 FOREIGN KEY (limits_id) REFERENCES subscription_plan_limit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EA664B63BAB8FB66 ON subscription_plan (limits_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription_plan DROP CONSTRAINT FK_EA664B63BAB8FB66');
        $this->addSql('ALTER TABLE subscription_plan_limit DROP CONSTRAINT FK_EED5EDF9C6F31ED6');
        $this->addSql('ALTER TABLE subscription_plan_limit DROP CONSTRAINT FK_EED5EDF99B8CE200');
        $this->addSql('DROP TABLE subscription_plan_limit');
        $this->addSql('DROP INDEX IDX_EA664B63BAB8FB66');
        $this->addSql('ALTER TABLE subscription_plan DROP limits_id');
    }
}
