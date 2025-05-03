<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503034047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER budget TYPE DOUBLE PRECISION
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA citus
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA citus_internal
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA columnar
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA columnar_internal
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE columnar_internal.storageid_seq INCREMENT BY 1 MINVALUE 10000000000 START 10000000000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ALTER budget TYPE NUMERIC(10, 2)
        SQL);
    }
}
