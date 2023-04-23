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
final class Version20230423182456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_subscription (invoice_id UUID NOT NULL, subscription_id UUID NOT NULL, PRIMARY KEY(invoice_id, subscription_id))');
        $this->addSql('CREATE INDEX IDX_1C014BA72989F1FD ON invoice_subscription (invoice_id)');
        $this->addSql('CREATE INDEX IDX_1C014BA79A1887DC ON invoice_subscription (subscription_id)');
        $this->addSql('COMMENT ON COLUMN invoice_subscription.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice_subscription.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice_subscription ADD CONSTRAINT FK_1C014BA72989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_subscription ADD CONSTRAINT FK_1C014BA79A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_subscription DROP CONSTRAINT FK_1C014BA72989F1FD');
        $this->addSql('ALTER TABLE invoice_subscription DROP CONSTRAINT FK_1C014BA79A1887DC');
        $this->addSql('DROP TABLE invoice_subscription');
        $this->addSql('ALTER TABLE payment DROP description');
    }
}
