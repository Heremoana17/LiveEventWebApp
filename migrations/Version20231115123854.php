<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115123854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE page_section (id INT AUTO_INCREMENT NOT NULL, view_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_D713917A31518C7 (view_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page_section ADD CONSTRAINT FK_D713917A31518C7 FOREIGN KEY (view_id) REFERENCES view (id)');
        $this->addSql('ALTER TABLE figure ADD page_section_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AD3C3D2E4 FOREIGN KEY (page_section_id) REFERENCES page_section (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37AD3C3D2E4 ON figure (page_section_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AD3C3D2E4');
        $this->addSql('ALTER TABLE page_section DROP FOREIGN KEY FK_D713917A31518C7');
        $this->addSql('DROP TABLE page_section');
        $this->addSql('DROP INDEX IDX_2F57B37AD3C3D2E4 ON figure');
        $this->addSql('ALTER TABLE figure DROP page_section_id');
    }
}
