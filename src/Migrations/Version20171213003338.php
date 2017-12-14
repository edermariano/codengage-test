<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213003338 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pessoa (id CHAR(36) NOT NULL COMMENT \'PK do registro(DC2Type:uuid)\', nome VARCHAR(100) NOT NULL COMMENT \'Nome da pessoa, segundo requisito não pode se repetir\', data_nascimento DATE NOT NULL COMMENT \'Data de Nascimento da pessoa\', UNIQUE INDEX UNIQ_1CDFAB82BF396750 (id), UNIQUE INDEX UNIQ_1CDFAB8254BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_pedido (id CHAR(36) NOT NULL COMMENT \'PK do registro(DC2Type:uuid)\', produto_id CHAR(36) DEFAULT NULL COMMENT \'PK do registro(DC2Type:uuid)\', quantidade NUMERIC(10, 2) NOT NULL COMMENT \'Quantidade de itens/produtos\', preco_unitario NUMERIC(10, 2) NOT NULL COMMENT \'Preço do produto da época. Proveniente da tabela de produtos\', percentual_desconto NUMERIC(10, 2) NOT NULL COMMENT \'Percentual de desconto aplicado\', total NUMERIC(10, 2) NOT NULL COMMENT \'Valor total já aplicado o desconto e multiplicada a quantidade\', UNIQUE INDEX UNIQ_42156301BF396750 (id), INDEX IDX_42156301105CFD56 (produto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido_venda (id CHAR(36) NOT NULL COMMENT \'PK do registro(DC2Type:uuid)\', cliente_id CHAR(36) DEFAULT NULL COMMENT \'PK do registro(DC2Type:uuid)\', numero int(11) NOT NULL AUTO_INCREMENT, emissao DATE NOT NULL COMMENT \'Data de emissão do Pedido\', total NUMERIC(10, 2) NOT NULL COMMENT \'Valor total do pedido\', UNIQUE INDEX UNIQ_8150CD62BF396750 (id), INDEX IDX_8150CD62DE734E51 (cliente_id), INDEX numero_ai_ix (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_pedido ADD CONSTRAINT FK_42156301105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE pedido_venda ADD CONSTRAINT FK_8150CD62DE734E51 FOREIGN KEY (cliente_id) REFERENCES pessoa (id)');
        $this->addSql('ALTER TABLE produto CHANGE id id CHAR(36) NOT NULL COMMENT \'PK do registro(DC2Type:uuid)\', CHANGE codigo codigo VARCHAR(100) NOT NULL COMMENT \'Código único do produto\', CHANGE nome nome VARCHAR(100) NOT NULL COMMENT \'Nome único do produto\', CHANGE preco preco NUMERIC(10, 2) NOT NULL COMMENT \'Preço do produto\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedido_venda DROP FOREIGN KEY FK_8150CD62DE734E51');
        $this->addSql('DROP TABLE pessoa');
        $this->addSql('DROP TABLE item_pedido');
        $this->addSql('DROP TABLE pedido_venda');
        $this->addSql('ALTER TABLE produto CHANGE id id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:uuid)\', CHANGE codigo codigo VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE nome nome VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE preco preco NUMERIC(10, 2) NOT NULL');
    }
}
