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
final class Version20230407074518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_subscription (payment_id UUID NOT NULL, subscription_id UUID NOT NULL, PRIMARY KEY(payment_id, subscription_id))');
        $this->addSql('CREATE INDEX IDX_C536D3C94C3A3BB ON payment_subscription (payment_id)');
        $this->addSql('CREATE INDEX IDX_C536D3C99A1887DC ON payment_subscription (subscription_id)');
        $this->addSql('COMMENT ON COLUMN payment_subscription.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment_subscription.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT FK_C536D3C94C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT FK_C536D3C99A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d9a1887dc');
        $this->addSql('DROP INDEX idx_6d28840d9a1887dc');
        $this->addSql('ALTER TABLE payment DROP subscription_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT FK_C536D3C94C3A3BB');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT FK_C536D3C99A1887DC');
        $this->addSql('DROP TABLE payment_subscription');
        $this->addSql('ALTER TABLE payment ADD subscription_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN payment.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d9a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6d28840d9a1887dc ON payment (subscription_id)');
    }
}
