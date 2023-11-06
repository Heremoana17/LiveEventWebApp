<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031110832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E8C782417');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E8C782417 FOREIGN KEY (header_image_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E8C782417');
        $this->addSql('DROP TABLE figure');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E8C782417');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E8C782417 FOREIGN KEY (header_image_id) REFERENCES image (id)');
    }
}
