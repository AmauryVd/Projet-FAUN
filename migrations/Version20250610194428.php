<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610194428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__transactions AS SELECT id, category_id, title, amount, type, description, date, created_at FROM transactions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transactions
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transactions (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, type VARCHAR(20) NOT NULL, description CLOB DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO transactions (id, category_id, title, amount, type, description, date, created_at) SELECT id, category_id, title, amount, type, description, date, created_at FROM __temp__transactions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__transactions
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EAA81A4C12469DE2 ON transactions (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__transactions AS SELECT id, category_id, title, amount, type, description, date, created_at FROM transactions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transactions
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transactions (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, type VARCHAR(20) NOT NULL, description CLOB DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO transactions (id, category_id, title, amount, type, description, date, created_at) SELECT id, category_id, title, amount, type, description, date, created_at FROM __temp__transactions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__transactions
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EAA81A4C12469DE2 ON transactions (category_id)
        SQL);
    }
}
