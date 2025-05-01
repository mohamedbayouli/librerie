<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430223617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre ADD qte SMALLINT NOT NULL, DROP prix');
        $this->addSql('ALTER TABLE user ADD liv_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64958FCEBF7 FOREIGN KEY (liv_id_id) REFERENCES livre (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64958FCEBF7 ON user (liv_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre ADD prix INT NOT NULL, DROP qte');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64958FCEBF7');
        $this->addSql('DROP INDEX UNIQ_8D93D64958FCEBF7 ON user');
        $this->addSql('ALTER TABLE user DROP liv_id_id');
    }
}
