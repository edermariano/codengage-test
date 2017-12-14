<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212022242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produto CHANGE preco preco NUMERIC(10, 2) NOT NULL;');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CAC49D720332D99 ON produto (codigo);');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CAC49D754BD530C ON produto (nome);');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produto CHANGE preco preco NUMERIC(10, 2) DEFAULT NULL;');
        $this->addSql('DROP INDEX `UNIQ_5CAC49D720332D99` ON produto;');
        $this->addSql('DROP INDEX `UNIQ_5CAC49D754BD530C` ON produto;');
    }
}
