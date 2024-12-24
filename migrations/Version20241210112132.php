<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210112132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, note VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_9474526C9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D9395C3F3');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('ALTER TABLE customer ADD origin VARCHAR(255) NOT NULL, ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE energy ADD total INT DEFAULT NULL, CHANGE power power INT DEFAULT NULL, CHANGE segment segment VARCHAR(255) DEFAULT \'C1\', CHANGE peak_hour_winter peak_hour_winter INT DEFAULT NULL, CHANGE peak_hour_summer peak_hour_summer INT DEFAULT NULL, CHANGE off_peak_hour_winter off_peak_hour_winter INT DEFAULT NULL, CHANGE off_peak_hour_summer off_peak_hour_summer INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, comments LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_C9CE8C7D9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE energy DROP total, CHANGE power power VARCHAR(255) DEFAULT NULL, CHANGE segment segment VARCHAR(255) DEFAULT NULL, CHANGE peak_hour_winter peak_hour_winter VARCHAR(255) DEFAULT NULL, CHANGE peak_hour_summer peak_hour_summer VARCHAR(255) DEFAULT NULL, CHANGE off_peak_hour_winter off_peak_hour_winter VARCHAR(255) DEFAULT NULL, CHANGE off_peak_hour_summer off_peak_hour_summer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer DROP origin, DROP status');
    }
}
