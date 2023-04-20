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
final class Version20230420142144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_payment (subscription_id UUID NOT NULL, payment_id UUID NOT NULL, PRIMARY KEY(subscription_id, payment_id))');
        $this->addSql('CREATE INDEX IDX_1E3D64969A1887DC ON subscription_payment (subscription_id)');
        $this->addSql('CREATE INDEX IDX_1E3D64964C3A3BB ON subscription_payment (payment_id)');
        $this->addSql('COMMENT ON COLUMN subscription_payment.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription_payment.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT FK_1E3D64969A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT FK_1E3D64964C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ALTER status DROP DEFAULT');
        $this->addSql('ALTER INDEX idx_6b6f05609395c3f3 RENAME TO IDX_7B61A1F69395C3F3');
        $this->addSql('ALTER TABLE subscription ALTER has_trial DROP DEFAULT');
        $this->addSql('ALTER TABLE subscription ALTER has_trial SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT FK_1E3D64969A1887DC');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT FK_1E3D64964C3A3BB');
        $this->addSql('DROP TABLE subscription_payment');
        $this->addSql('ALTER TABLE payment ALTER status SET DEFAULT \'completed\'');
        $this->addSql('ALTER INDEX idx_7b61a1f69395c3f3 RENAME TO idx_6b6f05609395c3f3');
        $this->addSql('ALTER TABLE subscription ALTER has_trial SET DEFAULT false');
        $this->addSql('ALTER TABLE subscription ALTER has_trial DROP NOT NULL');
    }
}
