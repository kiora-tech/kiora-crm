<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209093232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE energy ADD peak_hour VARCHAR(255) DEFAULT NULL, ADD off_peak_hour VARCHAR(255) DEFAULT NULL, ADD horo_season VARCHAR(255) DEFAULT NULL, ADD peak_hour_winter VARCHAR(255) DEFAULT NULL, ADD peak_hour_summer VARCHAR(255) DEFAULT NULL, ADD off_peak_hour_winter VARCHAR(255) NOT NULL, ADD off_peak_hour_summer VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE energy DROP peak_hour, DROP off_peak_hour, DROP horo_season, DROP peak_hour_winter, DROP peak_hour_summer, DROP off_peak_hour_winter, DROP off_peak_hour_summer');
    }
}
