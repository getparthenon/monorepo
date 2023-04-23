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
final class Version20230423152910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id UUID NOT NULL, customer_id UUID DEFAULT NULL, valid BOOLEAN NOT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, biller_address_company_name VARCHAR(255) DEFAULT NULL, biller_address_street_line_one VARCHAR(255) DEFAULT NULL, biller_address_street_line_two VARCHAR(255) DEFAULT NULL, biller_address_city VARCHAR(255) DEFAULT NULL, biller_address_region VARCHAR(255) DEFAULT NULL, biller_address_country VARCHAR(255) DEFAULT NULL, biller_address_postcode VARCHAR(255) DEFAULT NULL, payee_address_company_name VARCHAR(255) DEFAULT NULL, payee_address_street_line_one VARCHAR(255) DEFAULT NULL, payee_address_street_line_two VARCHAR(255) DEFAULT NULL, payee_address_city VARCHAR(255) DEFAULT NULL, payee_address_region VARCHAR(255) DEFAULT NULL, payee_address_country VARCHAR(255) DEFAULT NULL, payee_address_postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id)');
        $this->addSql('COMMENT ON COLUMN invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice_payment (invoice_id UUID NOT NULL, payment_id UUID NOT NULL, PRIMARY KEY(invoice_id, payment_id))');
        $this->addSql('CREATE INDEX IDX_9FF1B2DE2989F1FD ON invoice_payment (invoice_id)');
        $this->addSql('CREATE INDEX IDX_9FF1B2DE4C3A3BB ON invoice_payment (payment_id)');
        $this->addSql('COMMENT ON COLUMN invoice_payment.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_payment.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invoice_line (id UUID NOT NULL, invoice_id UUID DEFAULT NULL, currency VARCHAR(255) NOT NULL, total INT NOT NULL, sub_total INT NOT NULL, vat_total INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D3D1D6932989F1FD ON invoice_line (invoice_id)');
        $this->addSql('COMMENT ON COLUMN invoice_line.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_line.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_payment ADD CONSTRAINT FK_9FF1B2DE2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_payment ADD CONSTRAINT FK_9FF1B2DE4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6932989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449395C3F3');
        $this->addSql('ALTER TABLE invoice_payment DROP CONSTRAINT FK_9FF1B2DE2989F1FD');
        $this->addSql('ALTER TABLE invoice_payment DROP CONSTRAINT FK_9FF1B2DE4C3A3BB');
        $this->addSql('ALTER TABLE invoice_line DROP CONSTRAINT FK_D3D1D6932989F1FD');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_payment');
        $this->addSql('DROP TABLE invoice_line');
    }
}
