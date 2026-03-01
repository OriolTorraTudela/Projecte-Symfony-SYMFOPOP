<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migració inicial - Crea les taules user i product amb la relació ManyToOne.
 */
final class Version20250101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea les taules user i product amb relació ManyToOne';
    }

    public function up(Schema $schema): void
    {
        // Taula d'usuaris
        $this->addSql('CREATE TABLE `user` (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Taula de productes amb clau forana a user (owner)
        $this->addSql('CREATE TABLE product (
            id INT AUTO_INCREMENT NOT NULL,
            owner_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT NOT NULL,
            price NUMERIC(10, 2) NOT NULL,
            image VARCHAR(500) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            INDEX IDX_D34A04AD7E3C61F9 (owner_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_D34A04AD7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Taula de migracions de Doctrine (necessària)
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_75EA56E0FB7336F0 (queue_name),
            INDEX IDX_75EA56E0E3BD61CE (available_at),
            INDEX IDX_75EA56E016BA31DB (delivered_at),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7E3C61F9');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
