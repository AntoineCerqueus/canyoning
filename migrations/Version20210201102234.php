<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201102234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price ADD canyon_id INT NOT NULL');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D931991ED5 FOREIGN KEY (canyon_id) REFERENCES canyon (id)');
        $this->addSql('CREATE INDEX IDX_CAC822D931991ED5 ON price (canyon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D931991ED5');
        $this->addSql('DROP INDEX IDX_CAC822D931991ED5 ON price');
        $this->addSql('ALTER TABLE price DROP canyon_id');
    }
}
