<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201101712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture ADD canyon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8931991ED5 FOREIGN KEY (canyon_id) REFERENCES canyon (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8931991ED5 ON picture (canyon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8931991ED5');
        $this->addSql('DROP INDEX IDX_16DB4F8931991ED5 ON picture');
        $this->addSql('ALTER TABLE picture DROP canyon_id');
    }
}
