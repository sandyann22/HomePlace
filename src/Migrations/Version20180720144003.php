<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180720144003 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, phone CLOB NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json_array)
        )');
        $this->addSql('CREATE TEMPORARY TABLE __temp__annonce AS SELECT id, titre, description, created_at, image, region, categorie, autre FROM annonce');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('CREATE TABLE annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, titre VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL COLLATE BINARY, region VARCHAR(255) NOT NULL COLLATE BINARY, categorie VARCHAR(255) NOT NULL COLLATE BINARY, autre VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO annonce (id, titre, description, created_at, image, region, categorie, autre) SELECT id, titre, description, created_at, image, region, categorie, autre FROM __temp__annonce');
        $this->addSql('DROP TABLE __temp__annonce');
        $this->addSql('CREATE INDEX IDX_F65593E5A76ED395 ON annonce (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_F65593E5A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__annonce AS SELECT id, titre, description, created_at, image, region, categorie, autre FROM annonce');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('CREATE TABLE annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, autre VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO annonce (id, titre, description, created_at, image, region, categorie, autre) SELECT id, titre, description, created_at, image, region, categorie, autre FROM __temp__annonce');
        $this->addSql('DROP TABLE __temp__annonce');
    }
}
