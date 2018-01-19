<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180119075858 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auth CHANGE remember_token remember_token VARCHAR(255) DEFAULT NULL, CHANGE reset_at reset_at DATETIME DEFAULT NULL, CHANGE comfirmed_at comfirmed_at DATETIME DEFAULT NULL, CHANGE comfirmed_token comfirmed_token VARCHAR(255) DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD auth_id INT DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, CHANGE salt salt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498082819C FOREIGN KEY (auth_id) REFERENCES auth (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498082819C ON user (auth_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auth CHANGE remember_token remember_token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE reset_at reset_at DATETIME NOT NULL, CHANGE comfirmed_at comfirmed_at DATETIME NOT NULL, CHANGE comfirmed_token comfirmed_token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE reset_token reset_token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498082819C');
        $this->addSql('DROP INDEX UNIQ_8D93D6495126AC48 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6498082819C ON user');
        $this->addSql('ALTER TABLE user DROP auth_id, DROP is_active, CHANGE salt salt VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
