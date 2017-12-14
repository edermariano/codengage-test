<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213210937 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_pedido ADD pedido_id CHAR(36) DEFAULT NULL COMMENT \'PK do registro(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE item_pedido ADD CONSTRAINT FK_421563014854653A FOREIGN KEY (pedido_id) REFERENCES pedido_venda (id)');
        $this->addSql('CREATE INDEX IDX_421563014854653A ON item_pedido (pedido_id)');
        $this->addSql('ALTER TABLE pedido_venda CHANGE numero numero int(11) NOT NULL AUTO_INCREMENT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_pedido DROP FOREIGN KEY FK_421563014854653A');
        $this->addSql('DROP INDEX IDX_421563014854653A ON item_pedido');
        $this->addSql('ALTER TABLE item_pedido DROP pedido_id');
        $this->addSql('ALTER TABLE pedido_venda CHANGE numero numero INT AUTO_INCREMENT NOT NULL');
    }
}
