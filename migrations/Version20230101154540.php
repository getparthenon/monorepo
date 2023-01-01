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
final class Version20230101154540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_details (id UUID NOT NULL, customer_id UUID DEFAULT NULL, provider VARCHAR(255) DEFAULT NULL, stored_customer_reference VARCHAR(255) DEFAULT NULL, stored_payment_reference VARCHAR(255) DEFAULT NULL, "default" BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B6F05609395C3F3 ON payment_details (customer_id)');
        $this->addSql('COMMENT ON COLUMN payment_details.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment_details.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment_details ADD CONSTRAINT FK_6B6F05609395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests ADD data_provider_parameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests DROP parameters');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests RENAME COLUMN filename TO name');
        $this->addSql('ALTER TABLE teams ADD billing_address_company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_street_line_one VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_street_line_two VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_region VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD billing_address_postcode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams DROP subscription_price_id');
        $this->addSql('ALTER TABLE teams DROP subscription_payment_id');
        $this->addSql('ALTER TABLE teams DROP subscription_checkout_id');
        $this->addSql('ALTER TABLE teams DROP subscription_customer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_details DROP CONSTRAINT FK_6B6F05609395C3F3');
        $this->addSql('DROP TABLE payment_details');
        $this->addSql('ALTER TABLE teams ADD subscription_price_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD subscription_payment_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD subscription_checkout_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams ADD subscription_customer_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teams DROP billing_address_company_name');
        $this->addSql('ALTER TABLE teams DROP billing_address_street_line_one');
        $this->addSql('ALTER TABLE teams DROP billing_address_street_line_two');
        $this->addSql('ALTER TABLE teams DROP billing_address_city');
        $this->addSql('ALTER TABLE teams DROP billing_address_region');
        $this->addSql('ALTER TABLE teams DROP billing_address_country');
        $this->addSql('ALTER TABLE teams DROP billing_address_postcode');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests ADD parameters TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests DROP data_provider_parameters');
        $this->addSql('ALTER TABLE parthenon_export_background_export_requests RENAME COLUMN name TO filename');
        $this->addSql('COMMENT ON COLUMN parthenon_export_background_export_requests.parameters IS \'(DC2Type:array)\'');
    }
}
