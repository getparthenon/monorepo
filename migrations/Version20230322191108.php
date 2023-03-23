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
final class Version20230322191108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_plan DROP CONSTRAINT fk_ea664b63bab8fb66');
        $this->addSql('DROP INDEX idx_ea664b63bab8fb66');
        $this->addSql('ALTER TABLE subscription_plan DROP limits_id');
        $this->addSql('ALTER TABLE subscription_plan_limit ADD limit_number INT NOT NULL');
        $this->addSql('ALTER TABLE subscription_plan_limit DROP "limit"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription_plan_limit ADD "limit" VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE subscription_plan_limit DROP limit_number');
        $this->addSql('ALTER TABLE subscription_plan ADD limits_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN subscription_plan.limits_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_plan ADD CONSTRAINT fk_ea664b63bab8fb66 FOREIGN KEY (limits_id) REFERENCES subscription_plan_limit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ea664b63bab8fb66 ON subscription_plan (limits_id)');
    }
}
