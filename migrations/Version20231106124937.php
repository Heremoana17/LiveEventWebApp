<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106124937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, music_link VARCHAR(255) DEFAULT NULL, featured_image VARCHAR(200) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, programme_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E5A0299062BB7AEE (programme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, artiste_id INT DEFAULT NULL, scene_id INT DEFAULT NULL, day_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, hour TIME NOT NULL, INDEX IDX_DDAA1CDA21D25844 (artiste_id), INDEX IDX_DDAA1CDA166053B4 (scene_id), INDEX IDX_DDAA1CDA9C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, gpspt_lat VARCHAR(255) NOT NULL, gpspt_lng VARCHAR(255) NOT NULL, featured_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, artiste_id INT DEFAULT NULL, link VARCHAR(50) NOT NULL, INDEX IDX_36AC99F121D25844 (artiste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scene (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, featured_image VARCHAR(200) DEFAULT NULL, gpspt_lat VARCHAR(255) NOT NULL, gpspt_lng VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE view (id INT AUTO_INCREMENT NOT NULL, header_image_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, header_text LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FEFDAB8E8C782417 (header_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A0299062BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F121D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E8C782417 FOREIGN KEY (header_image_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A0299062BB7AEE');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA21D25844');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA166053B4');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA9C24126');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F121D25844');
        $this->addSql('ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E8C782417');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE scene');
        $this->addSql('DROP TABLE view');
    }
}
