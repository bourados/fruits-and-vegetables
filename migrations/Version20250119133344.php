<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119133344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruits_collection (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE produce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity INTEGER NOT NULL, type VARCHAR(255) NOT NULL, collection_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B9FA245F514956FD ON produce (collection_id)');
        $this->addSql('CREATE TABLE vegetables_collection (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fruits_collection');
        $this->addSql('DROP TABLE produce');
        $this->addSql('DROP TABLE vegetables_collection');
    }
}
