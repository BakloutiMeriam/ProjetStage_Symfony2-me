<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201102009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplois ADD idemploye INT DEFAULT NULL, ADD idjour INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_461274B9270081D7 ON emplois (idemploye)');
        $this->addSql('CREATE INDEX IDX_461274B99D82865 ON emplois (idjour)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplois DROP FOREIGN KEY FK_461274B9270081D7');
        $this->addSql('ALTER TABLE emplois DROP FOREIGN KEY FK_461274B99D82865');
        $this->addSql('DROP INDEX IDX_461274B9270081D7 ON emplois');
        $this->addSql('DROP INDEX IDX_461274B99D82865 ON emplois');
        $this->addSql('ALTER TABLE emplois DROP idemploye, DROP idjour');
        $this->addSql('ALTER TABLE empreinte DROP FOREIGN KEY FK_32AF9675270081D7');
    }
}
