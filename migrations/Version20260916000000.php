<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260916000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Skapar tabellen gadgets för Prylkatalogen-projektet';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE gadgets (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            brand VARCHAR(100) NOT NULL,
            category VARCHAR(50) NOT NULL,
            release_year INT NOT NULL,
            price DOUBLE PRECISION NOT NULL,
            rating INT NOT NULL,
            description LONGTEXT DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE gadgets');
    }
}
