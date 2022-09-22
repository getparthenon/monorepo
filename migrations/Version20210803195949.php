<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803195949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forgot_password_code (id UUID NOT NULL, user_id UUID DEFAULT NULL, code VARCHAR(255) NOT NULL, used BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, used_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B30A7571A76ED395 ON forgot_password_code (user_id)');
        $this->addSql('COMMENT ON COLUMN forgot_password_code.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN forgot_password_code.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invite_codes (id UUID NOT NULL, user_id UUID DEFAULT NULL, invited_user_id UUID DEFAULT NULL, code VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, used BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, used_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8D89FB2A76ED395 ON invite_codes (user_id)');
        $this->addSql('CREATE INDEX IDX_E8D89FB2C58DAD6E ON invite_codes (invited_user_id)');
        $this->addSql('COMMENT ON COLUMN invite_codes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite_codes.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite_codes.invited_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE notification (id UUID NOT NULL, message_template VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, read_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_read BOOLEAN NOT NULL, link_url_name VARCHAR(255) NOT NULL, link_url_variables JSON NOT NULL, link_is_raw BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, confirmation_code VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, activated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_confirmed BOOLEAN NOT NULL, is_admin BOOLEAN NOT NULL, is_deleted BOOLEAN NOT NULL, roles TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE forgot_password_code ADD CONSTRAINT FK_B30A7571A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invite_codes ADD CONSTRAINT FK_E8D89FB2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invite_codes ADD CONSTRAINT FK_E8D89FB2C58DAD6E FOREIGN KEY (invited_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE forgot_password_code DROP CONSTRAINT FK_B30A7571A76ED395');
        $this->addSql('ALTER TABLE invite_codes DROP CONSTRAINT FK_E8D89FB2A76ED395');
        $this->addSql('ALTER TABLE invite_codes DROP CONSTRAINT FK_E8D89FB2C58DAD6E');
        $this->addSql('DROP TABLE forgot_password_code');
        $this->addSql('DROP TABLE invite_codes');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE users');
    }
}
