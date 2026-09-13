<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120090620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD img LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE product ADD evaluation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_categorie ADD img LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD type VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP img');
        $this->addSql('ALTER TABLE product DROP evaluation');
        $this->addSql('ALTER TABLE sous_categorie DROP img');
        $this->addSql('ALTER TABLE user DROP type');
    }
}
