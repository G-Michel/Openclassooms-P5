<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111083955 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auth (id INT AUTO_INCREMENT NOT NULL, remember_token VARCHAR(255) NOT NULL, reset_at DATETIME NOT NULL, comfirmed_at DATETIME NOT NULL, comfirmed_token VARCHAR(255) NOT NULL, reset_token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, gps_x INT NOT NULL, gps_y INT NOT NULL, address VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, bird_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, date_obs DATETIME NOT NULL, date_add DATETIME NOT NULL, status VARCHAR(255) NOT NULL, bird_number INT NOT NULL, comment VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C576DBE064D218E (location_id), UNIQUE INDEX UNIQ_C576DBE0E813F9 (bird_id), UNIQUE INDEX UNIQ_C576DBE0EE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, alt VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxref (id INT AUTO_INCREMENT NOT NULL, reign_type VARCHAR(255) NOT NULL, phylum_type VARCHAR(255) NOT NULL, class_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, newsletter TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE064D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0E813F9 FOREIGN KEY (bird_id) REFERENCES bird (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE064D218E');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0EE45BDBF');
        $this->addSql('DROP TABLE auth');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE observation');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE taxref');
        $this->addSql('DROP TABLE user');
    }
}
