<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116085433 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD salt VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE surname surname VARCHAR(255) DEFAULT NULL, CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64924A232CF ON user (user_name)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird DROP slug');
        $this->addSql('DROP INDEX UNIQ_8D93D64924A232CF ON user');
        $this->addSql('ALTER TABLE user DROP salt, CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE surname surname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE role role VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE newsletter newsletter TINYINT(1) NOT NULL');
    }
}
