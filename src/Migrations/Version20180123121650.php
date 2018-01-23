<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123121650 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE observation CHANGE date_add date_add DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bird DROP inpn_link');
        $this->addSql('ALTER TABLE location DROP country, DROP state, DROP city');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird ADD inpn_link VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE location ADD country VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD state VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE observation CHANGE date_add date_add DATETIME NOT NULL');
    }
}
