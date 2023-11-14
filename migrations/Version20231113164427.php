<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113164427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59ADA40271');
        $this->addSql('DROP INDEX UNIQ_2F577D59ADA40271 ON lieu');
        $this->addSql('ALTER TABLE lieu DROP link_id');
        $this->addSql('ALTER TABLE link ADD lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F16AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_36AC99F16AB213CC ON link (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu ADD link_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59ADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F577D59ADA40271 ON lieu (link_id)');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F16AB213CC');
        $this->addSql('DROP INDEX IDX_36AC99F16AB213CC ON link');
        $this->addSql('ALTER TABLE link DROP lieu_id');
    }
}
