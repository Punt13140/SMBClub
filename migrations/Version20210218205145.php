<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218205145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, categorie_parent_id INT DEFAULT NULL, libelle VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, is_home TINYINT(1) NOT NULL, INDEX IDX_497DD634DF25C577 (categorie_parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, categorie_id INT NOT NULL, created_at DATETIME NOT NULL, libelle VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_C0B9F90FB03A8386 (created_by_id), INDEX IDX_C0B9F90FBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634DF25C577 FOREIGN KEY (categorie_parent_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634DF25C577');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FBCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE discussion');
    }
}
