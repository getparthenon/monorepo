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
final class Version20230105090526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_details ADD brand VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_details ADD last_four VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_details ADD expiry_month VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_details ADD expiry_year VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_details DROP brand');
        $this->addSql('ALTER TABLE payment_details DROP last_four');
        $this->addSql('ALTER TABLE payment_details DROP expiry_month');
        $this->addSql('ALTER TABLE payment_details DROP expiry_year');
    }
}
