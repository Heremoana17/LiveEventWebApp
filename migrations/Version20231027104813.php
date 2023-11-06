<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027104813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA96E1EAF4');
        $this->addSql('DROP INDEX IDX_DDAA1CDA96E1EAF4 ON episode');
        $this->addSql('ALTER TABLE episode CHANGE artistes_id artiste_id INT NOT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA21D25844 ON episode (artiste_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA21D25844');
        $this->addSql('DROP INDEX IDX_DDAA1CDA21D25844 ON episode');
        $this->addSql('ALTER TABLE episode CHANGE artiste_id artistes_id INT NOT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA96E1EAF4 FOREIGN KEY (artistes_id) REFERENCES artiste (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA96E1EAF4 ON episode (artistes_id)');
    }
}
