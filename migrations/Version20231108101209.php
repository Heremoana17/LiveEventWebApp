<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108101209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA166053B4');
        $this->addSql('DROP TABLE scene');
        $this->addSql('DROP INDEX IDX_DDAA1CDA166053B4 ON episode');
        $this->addSql('ALTER TABLE episode CHANGE scene_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA6AB213CC ON episode (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scene (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, featured_image VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, gpspt_lat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, gpspt_lng VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA6AB213CC');
        $this->addSql('DROP INDEX IDX_DDAA1CDA6AB213CC ON episode');
        $this->addSql('ALTER TABLE episode CHANGE lieu_id scene_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA166053B4 ON episode (scene_id)');
    }
}
