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
final class Version20230424125201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receipt (id UUID NOT NULL, customer_id UUID DEFAULT NULL, valid BOOLEAN NOT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, biller_address_company_name VARCHAR(255) DEFAULT NULL, biller_address_street_line_one VARCHAR(255) DEFAULT NULL, biller_address_street_line_two VARCHAR(255) DEFAULT NULL, biller_address_city VARCHAR(255) DEFAULT NULL, biller_address_region VARCHAR(255) DEFAULT NULL, biller_address_country VARCHAR(255) DEFAULT NULL, biller_address_postcode VARCHAR(255) DEFAULT NULL, payee_address_company_name VARCHAR(255) DEFAULT NULL, payee_address_street_line_one VARCHAR(255) DEFAULT NULL, payee_address_street_line_two VARCHAR(255) DEFAULT NULL, payee_address_city VARCHAR(255) DEFAULT NULL, payee_address_region VARCHAR(255) DEFAULT NULL, payee_address_country VARCHAR(255) DEFAULT NULL, payee_address_postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5399B6459395C3F3 ON receipt (customer_id)');
        $this->addSql('COMMENT ON COLUMN receipt.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN receipt.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE receipt_payment (receipt_id UUID NOT NULL, payment_id UUID NOT NULL, PRIMARY KEY(receipt_id, payment_id))');
        $this->addSql('CREATE INDEX IDX_7E6221F32B5CA896 ON receipt_payment (receipt_id)');
        $this->addSql('CREATE INDEX IDX_7E6221F34C3A3BB ON receipt_payment (payment_id)');
        $this->addSql('COMMENT ON COLUMN receipt_payment.receipt_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN receipt_payment.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE receipt_subscription (receipt_id UUID NOT NULL, subscription_id UUID NOT NULL, PRIMARY KEY(receipt_id, subscription_id))');
        $this->addSql('CREATE INDEX IDX_32952A5C2B5CA896 ON receipt_subscription (receipt_id)');
        $this->addSql('CREATE INDEX IDX_32952A5C9A1887DC ON receipt_subscription (subscription_id)');
        $this->addSql('COMMENT ON COLUMN receipt_subscription.receipt_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN receipt_subscription.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE receipt_line (id UUID NOT NULL, receipt_id UUID DEFAULT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_476F8F7A2B5CA896 ON receipt_line (receipt_id)');
        $this->addSql('COMMENT ON COLUMN receipt_line.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN receipt_line.receipt_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B6459395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT FK_7E6221F32B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT FK_7E6221F34C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT FK_32952A5C2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT FK_32952A5C9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_line ADD CONSTRAINT FK_476F8F7A2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk_906517449395c3f3');
        $this->addSql('ALTER TABLE invoice_subscription DROP CONSTRAINT fk_1c014ba72989f1fd');
        $this->addSql('ALTER TABLE invoice_subscription DROP CONSTRAINT fk_1c014ba79a1887dc');
        $this->addSql('ALTER TABLE invoice_line DROP CONSTRAINT fk_d3d1d6932989f1fd');
        $this->addSql('ALTER TABLE invoice_payment DROP CONSTRAINT fk_9ff1b2de2989f1fd');
        $this->addSql('ALTER TABLE invoice_payment DROP CONSTRAINT fk_9ff1b2de4c3a3bb');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_subscription');
        $this->addSql('DROP TABLE invoice_line');
        $this->addSql('DROP TABLE invoice_payment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE invoice (id UUID NOT NULL, customer_id UUID DEFAULT NULL, valid BOOLEAN NOT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, biller_address_company_name VARCHAR(255) DEFAULT NULL, biller_address_street_line_one VARCHAR(255) DEFAULT NULL, biller_address_street_line_two VARCHAR(255) DEFAULT NULL, biller_address_city VARCHAR(255) DEFAULT NULL, biller_address_region VARCHAR(255) DEFAULT NULL, biller_address_country VARCHAR(255) DEFAULT NULL, biller_address_postcode VARCHAR(255) DEFAULT NULL, payee_address_company_name VARCHAR(255) DEFAULT NULL, payee_address_street_line_one VARCHAR(255) DEFAULT NULL, payee_address_street_line_two VARCHAR(255) DEFAULT NULL, payee_address_city VARCHAR(255) DEFAULT NULL, payee_address_region VARCHAR(255) DEFAULT NULL, payee_address_country VARCHAR(255) DEFAULT NULL, payee_address_postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_906517449395c3f3 ON invoice (customer_id)');
        $this->addSql('COMMENT ON COLUMN invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice_subscription (invoice_id UUID NOT NULL, subscription_id UUID NOT NULL, PRIMARY KEY(invoice_id, subscription_id))');
        $this->addSql('CREATE INDEX idx_1c014ba79a1887dc ON invoice_subscription (subscription_id)');
        $this->addSql('CREATE INDEX idx_1c014ba72989f1fd ON invoice_subscription (invoice_id)');
        $this->addSql('COMMENT ON COLUMN invoice_subscription.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_subscription.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice_line (id UUID NOT NULL, invoice_id UUID DEFAULT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d3d1d6932989f1fd ON invoice_line (invoice_id)');
        $this->addSql('COMMENT ON COLUMN invoice_line.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_line.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice_payment (invoice_id UUID NOT NULL, payment_id UUID NOT NULL, PRIMARY KEY(invoice_id, payment_id))');
        $this->addSql('CREATE INDEX idx_9ff1b2de4c3a3bb ON invoice_payment (payment_id)');
        $this->addSql('CREATE INDEX idx_9ff1b2de2989f1fd ON invoice_payment (invoice_id)');
        $this->addSql('COMMENT ON COLUMN invoice_payment.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_payment.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk_906517449395c3f3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_subscription ADD CONSTRAINT fk_1c014ba72989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_subscription ADD CONSTRAINT fk_1c014ba79a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT fk_d3d1d6932989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_payment ADD CONSTRAINT fk_9ff1b2de2989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_payment ADD CONSTRAINT fk_9ff1b2de4c3a3bb FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt DROP CONSTRAINT FK_5399B6459395C3F3');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT FK_7E6221F32B5CA896');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT FK_7E6221F34C3A3BB');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT FK_32952A5C2B5CA896');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT FK_32952A5C9A1887DC');
        $this->addSql('ALTER TABLE receipt_line DROP CONSTRAINT FK_476F8F7A2B5CA896');
        $this->addSql('DROP TABLE receipt');
        $this->addSql('DROP TABLE receipt_payment');
        $this->addSql('DROP TABLE receipt_subscription');
        $this->addSql('DROP TABLE receipt_line');
    }
}
