<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516100936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, num_carte VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BCE3F79812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE livre ADD cat_id_id INT DEFAULT NULL, ADD sub_category_id INT DEFAULT NULL, ADD tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99C33F2EBA FOREIGN KEY (cat_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('CREATE INDEX IDX_AC634F99C33F2EBA ON livre (cat_id_id)');
        $this->addSql('CREATE INDEX IDX_AC634F99F7BFE87C ON livre (sub_category_id)');
        $this->addSql('ALTER TABLE user ADD num_carte VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99F7BFE87C');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99C33F2EBA');
        $this->addSql('DROP INDEX IDX_AC634F99C33F2EBA ON livre');
        $this->addSql('DROP INDEX IDX_AC634F99F7BFE87C ON livre');
        $this->addSql('ALTER TABLE livre DROP cat_id_id, DROP sub_category_id, DROP tags');
        $this->addSql('ALTER TABLE user DROP num_carte, DROP nom');
    }
}
