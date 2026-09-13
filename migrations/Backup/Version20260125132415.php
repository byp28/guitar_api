<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260125132415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, numeros INT NOT NULL, rue VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, likes INT NOT NULL, dislikes INT NOT NULL, user_id INT NOT NULL, product_id INT DEFAULT NULL, INDEX IDX_8F91ABF0A76ED395 (user_id), INDEX IDX_8F91ABF04584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, img LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date_cmd DATE NOT NULL, date_exp DATE NOT NULL, user_id INT NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commande_product (commande_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_25F1760D82EA2E54 (commande_id), INDEX IDX_25F1760D4584665A (product_id), PRIMARY KEY (commande_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, description_technique LONGTEXT NOT NULL, price INT NOT NULL, image LONGTEXT DEFAULT NULL, model_3d LONGTEXT DEFAULT NULL, evaluation INT DEFAULT NULL, categorie_id INT NOT NULL, sous_categorie_id INT NOT NULL, INDEX IDX_D34A04ADBCF5E72D (categorie_id), INDEX IDX_D34A04AD365BF48 (sous_categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE sous_categorie (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, img LONGTEXT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_52743D7BBCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, type VARCHAR(10) NOT NULL, adresses_id INT DEFAULT NULL, INDEX IDX_8D93D64985E14726 (adresses_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64985E14726 FOREIGN KEY (adresses_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF04584665A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D82EA2E54');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBCF5E72D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD365BF48');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64985E14726');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
