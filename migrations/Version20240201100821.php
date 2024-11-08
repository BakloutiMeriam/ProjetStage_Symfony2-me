<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201100821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplois CHANGE idemplois idemplois INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (idemplois)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplois MODIFY idemplois INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON emplois');
        $this->addSql('ALTER TABLE emplois CHANGE idemplois idemplois INT NOT NULL');
        $this->addSql('ALTER TABLE empreinte DROP FOREIGN KEY FK_32AF9675270081D7');
    }
}
