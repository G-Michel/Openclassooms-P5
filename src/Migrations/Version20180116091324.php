<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116091324 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird ADD taxref_id INT DEFAULT NULL, DROP reference_id, DROP reference_name, DROP vernicular_name');
        $this->addSql('ALTER TABLE bird ADD CONSTRAINT FK_A0BBAE0E18F55814 FOREIGN KEY (taxref_id) REFERENCES taxref (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0BBAE0E18F55814 ON bird (taxref_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird DROP FOREIGN KEY FK_A0BBAE0E18F55814');
        $this->addSql('DROP INDEX UNIQ_A0BBAE0E18F55814 ON bird');
        $this->addSql('ALTER TABLE bird ADD reference_id INT NOT NULL, ADD reference_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD vernicular_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP taxref_id');
    }
}
