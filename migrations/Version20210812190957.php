<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812190957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_invite_codes (id UUID NOT NULL, user_id UUID DEFAULT NULL, invited_user_id UUID DEFAULT NULL, team_id UUID DEFAULT NULL, code VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, used BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, used_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, cancelled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E05E9270A76ED395 ON team_invite_codes (user_id)');
        $this->addSql('CREATE INDEX IDX_E05E9270C58DAD6E ON team_invite_codes (invited_user_id)');
        $this->addSql('CREATE INDEX IDX_E05E9270296CD8AE ON team_invite_codes (team_id)');
        $this->addSql('COMMENT ON COLUMN team_invite_codes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team_invite_codes.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team_invite_codes.invited_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team_invite_codes.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team_invite_codes ADD CONSTRAINT FK_E05E9270A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_invite_codes ADD CONSTRAINT FK_E05E9270C58DAD6E FOREIGN KEY (invited_user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_invite_codes ADD CONSTRAINT FK_E05E9270296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invite_codes ADD cancelled BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE users ADD team_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9296CD8AE ON users (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE team_invite_codes');
        $this->addSql('ALTER TABLE invite_codes DROP cancelled');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9296CD8AE');
        $this->addSql('DROP INDEX IDX_1483A5E9296CD8AE');
        $this->addSql('ALTER TABLE users DROP team_id');
    }
}
