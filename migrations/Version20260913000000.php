<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260913000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Skapar tabellen books för Bibliotek-applikationen';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE books (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            isbn VARCHAR(20) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            UNIQUE INDEX UNIQ_BOOKS_ISBN (isbn),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE books');
    }
}
