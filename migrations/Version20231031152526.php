<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031152526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scene ADD gpspt_lat VARCHAR(255) NOT NULL, ADD gpspt_lng VARCHAR(255) NOT NULL, DROP gpspoint');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scene ADD gpspoint DOUBLE PRECISION NOT NULL, DROP gpspt_lat, DROP gpspt_lng');
    }
}
