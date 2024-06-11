<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611115141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT fk_a3c664d38eec86f7');
        $this->addSql('CREATE TABLE payment_card (id UUID NOT NULL, customer_id UUID DEFAULT NULL, provider VARCHAR(255) DEFAULT NULL, stored_customer_reference VARCHAR(255) DEFAULT NULL, stored_payment_reference VARCHAR(255) DEFAULT NULL, default_payment_option BOOLEAN NOT NULL, brand VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, last_four VARCHAR(255) DEFAULT NULL, expiry_month VARCHAR(255) DEFAULT NULL, expiry_year VARCHAR(255) DEFAULT NULL, is_deleted BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_37970FA79395C3F3 ON payment_card (customer_id)');
        $this->addSql('COMMENT ON COLUMN payment_card.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment_card.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment_card ADD CONSTRAINT FK_37970FA79395C3F3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT fk_6b6f05609395c3f3');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE invite_codes ADD role VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT FK_C536D3C94C3A3BB');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT FK_C536D3C99A1887DC');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT FK_C536D3C94C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT FK_C536D3C99A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE price ADD is_deleted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE price ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE price ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD vat_percentage DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT FK_7E6221F32B5CA896');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT FK_7E6221F34C3A3BB');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT FK_7E6221F32B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT FK_7E6221F34C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT FK_32952A5C2B5CA896');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT FK_32952A5C9A1887DC');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT FK_32952A5C2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT FK_32952A5C9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_line ADD vat_percentage DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE refund RENAME COLUMN started_at TO created_at');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38EEC86F7 FOREIGN KEY (payment_details_id) REFERENCES payment_card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT FK_1E3D64969A1887DC');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT FK_1E3D64964C3A3BB');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT FK_1E3D64969A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT FK_1E3D64964C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan ADD code_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA664B6368C814C7 ON subscription_plan (code_name)');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT FK_63CBB01D9B8CE200');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT FK_63CBB01DA201F81C');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT FK_63CBB01D9B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT FK_63CBB01DA201F81C FOREIGN KEY (subscription_feature_id) REFERENCES subscription_feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_price DROP CONSTRAINT FK_5B8B27409B8CE200');
        $this->addSql('ALTER TABLE subscription_plan_price DROP CONSTRAINT FK_5B8B2740D614C7E7');
        $this->addSql('ALTER TABLE subscription_plan_price ADD CONSTRAINT FK_5B8B27409B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_price ADD CONSTRAINT FK_5B8B2740D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teams ADD billing_email VARCHAR(255) NOT NULL default \'example@example.org\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D38EEC86F7');
        $this->addSql('CREATE TABLE payment_method (id UUID NOT NULL, customer_id UUID DEFAULT NULL, provider VARCHAR(255) DEFAULT NULL, stored_customer_reference VARCHAR(255) DEFAULT NULL, stored_payment_reference VARCHAR(255) DEFAULT NULL, default_payment_option BOOLEAN NOT NULL, brand VARCHAR(255) DEFAULT NULL, last_four VARCHAR(255) DEFAULT NULL, expiry_month VARCHAR(255) DEFAULT NULL, expiry_year VARCHAR(255) DEFAULT NULL, is_deleted BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7b61a1f69395c3f3 ON payment_method (customer_id)');
        $this->addSql('COMMENT ON COLUMN payment_method.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment_method.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT fk_6b6f05609395c3f3 FOREIGN KEY (customer_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_card DROP CONSTRAINT FK_37970FA79395C3F3');
        $this->addSql('DROP TABLE payment_card');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT fk_7e6221f32b5ca896');
        $this->addSql('ALTER TABLE receipt_payment DROP CONSTRAINT fk_7e6221f34c3a3bb');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT fk_7e6221f32b5ca896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_payment ADD CONSTRAINT fk_7e6221f34c3a3bb FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT fk_a3c664d38eec86f7');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT fk_a3c664d38eec86f7 FOREIGN KEY (payment_details_id) REFERENCES payment_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_line DROP vat_percentage');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT fk_32952a5c2b5ca896');
        $this->addSql('ALTER TABLE receipt_subscription DROP CONSTRAINT fk_32952a5c9a1887dc');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT fk_32952a5c2b5ca896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_subscription ADD CONSTRAINT fk_32952a5c9a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt DROP vat_percentage');
        $this->addSql('ALTER TABLE receipt DROP created_at');
        $this->addSql('ALTER TABLE refund RENAME COLUMN created_at TO started_at');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT fk_1e3d64969a1887dc');
        $this->addSql('ALTER TABLE subscription_payment DROP CONSTRAINT fk_1e3d64964c3a3bb');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT fk_1e3d64969a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_payment ADD CONSTRAINT fk_1e3d64964c3a3bb FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT fk_c536d3c94c3a3bb');
        $this->addSql('ALTER TABLE payment_subscription DROP CONSTRAINT fk_c536d3c99a1887dc');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT fk_c536d3c94c3a3bb FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_subscription ADD CONSTRAINT fk_c536d3c99a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX UNIQ_EA664B6368C814C7');
        $this->addSql('ALTER TABLE subscription_plan DROP code_name');
        $this->addSql('ALTER TABLE subscription_plan_price DROP CONSTRAINT fk_5b8b27409b8ce200');
        $this->addSql('ALTER TABLE subscription_plan_price DROP CONSTRAINT fk_5b8b2740d614c7e7');
        $this->addSql('ALTER TABLE subscription_plan_price ADD CONSTRAINT fk_5b8b27409b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_price ADD CONSTRAINT fk_5b8b2740d614c7e7 FOREIGN KEY (price_id) REFERENCES price (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invite_codes DROP role');
        $this->addSql('ALTER TABLE price DROP is_deleted');
        $this->addSql('ALTER TABLE price DROP created_at');
        $this->addSql('ALTER TABLE price DROP deleted_at');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT fk_63cbb01d9b8ce200');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature DROP CONSTRAINT fk_63cbb01da201f81c');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT fk_63cbb01d9b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_plan_subscription_feature ADD CONSTRAINT fk_63cbb01da201f81c FOREIGN KEY (subscription_feature_id) REFERENCES subscription_feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teams DROP billing_email');
    }
}
