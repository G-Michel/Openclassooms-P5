<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111141740 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tax_galerie');
        $this->addSql('ALTER TABLE taxref ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taxref ADD CONSTRAINT FK_C086DF4BEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C086DF4BEE45BDBF ON taxref (picture_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tax_galerie (id INT AUTO_INCREMENT NOT NULL, taxref_id INT DEFAULT NULL, src VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_DF35B59A18F55814 (taxref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tax_galerie ADD CONSTRAINT FK_DF35B59A18F55814 FOREIGN KEY (taxref_id) REFERENCES taxref (id)');
        $this->addSql('ALTER TABLE taxref DROP FOREIGN KEY FK_C086DF4BEE45BDBF');
        $this->addSql('DROP INDEX UNIQ_C086DF4BEE45BDBF ON taxref');
        $this->addSql('ALTER TABLE taxref DROP picture_id');
    }
}
