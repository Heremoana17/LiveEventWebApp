<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027104624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, artistes_id INT NOT NULL, scene_id INT NOT NULL, name VARCHAR(50) NOT NULL, hour TIME NOT NULL, INDEX IDX_DDAA1CDA96E1EAF4 (artistes_id), INDEX IDX_DDAA1CDA166053B4 (scene_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA96E1EAF4 FOREIGN KEY (artistes_id) REFERENCES artiste (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('DROP TABLE evenement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hour TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA96E1EAF4');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA166053B4');
        $this->addSql('DROP TABLE episode');
    }
}
