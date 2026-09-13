<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260727220824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_product (id INT AUTO_INCREMENT NOT NULL, commande_id_id INT DEFAULT NULL, product_id_id INT DEFAULT NULL, INDEX IDX_25F1760D462C4194 (commande_id_id), INDEX IDX_25F1760DDE18E50B (product_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D462C4194 FOREIGN KEY (commande_id_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760DDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE commande ADD commande_number VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D462C4194');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760DDE18E50B');
        $this->addSql('DROP TABLE commande_product');
        $this->addSql('ALTER TABLE commande DROP commande_number');
    }
}
