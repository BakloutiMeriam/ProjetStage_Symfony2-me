<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201094021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empreinte ADD idemploye INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_32AF9675270081D7 ON empreinte (idemploye)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empreinte DROP FOREIGN KEY FK_32AF9675270081D7');
        $this->addSql('DROP INDEX IDX_32AF9675270081D7 ON empreinte');
        $this->addSql('ALTER TABLE empreinte DROP idemploye');
    }
}