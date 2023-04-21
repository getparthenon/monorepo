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
final class Version20230421192233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge_back (id UUID NOT NULL, payment_id UUID DEFAULT NULL, customer_id UUID DEFAULT NULL, external_reference VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_250DACE84C3A3BB ON charge_back (payment_id)');
        $this->addSql('CREATE INDEX IDX_250DACE89395C3F3 ON charge_back (customer_id)');
        $this->addSql('COMMENT ON COLUMN charge_back.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN charge_back.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN charge_back.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE charge_back ADD CONSTRAINT FK_250DACE84C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE charge_back ADD CONSTRAINT FK_250DACE89395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE charge_back DROP CONSTRAINT FK_250DACE84C3A3BB');
        $this->addSql('ALTER TABLE charge_back DROP CONSTRAINT FK_250DACE89395C3F3');
        $this->addSql('DROP TABLE charge_back');
    }
}
