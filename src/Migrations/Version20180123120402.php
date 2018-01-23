<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123120402 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE observation DROP bird_number');
        $this->addSql('ALTER TABLE bird ADD bird_number INT NOT NULL, ADD bird_size INT NOT NULL, ADD bird_colors LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP slug');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bird ADD slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP bird_number, DROP bird_size, DROP bird_colors');
        $this->addSql('ALTER TABLE observation ADD bird_number INT NOT NULL');
    }
}
