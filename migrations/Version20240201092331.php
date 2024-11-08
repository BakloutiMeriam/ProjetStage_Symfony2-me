<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201092331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empreinte CHANGE codeemp codeemp INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (codeemp)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empreinte MODIFY codeemp INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON empreinte');
        $this->addSql('ALTER TABLE empreinte CHANGE codeemp codeemp INT NOT NULL');
    }
}
