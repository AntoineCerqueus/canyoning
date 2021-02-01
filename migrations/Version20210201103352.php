<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201103352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD guide_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D7ED1D4B FOREIGN KEY (guide_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D7ED1D4B ON event (guide_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D7ED1D4B');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D7ED1D4B ON event');
        $this->addSql('ALTER TABLE event DROP guide_id');
    }
}
