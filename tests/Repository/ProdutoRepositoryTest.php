<?php

namespace App\Tests\Repository;

use App\DataFixtures\Pedidos\ItemPedidoFixtures;
use App\DataFixtures\Pedidos\PedidoFixture;
use App\DataFixtures\Pedidos\ProdutoItemPedidoFixtures;
use App\DataFixtures\Produtos\ProdutoFixture;
use App\DataFixtures\Produtos\ProdutoFixtures;
use App\Entity\ItemPedido;
use App\Entity\Produto;
use App\Tests\ApagaBanco;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ProdutoRepositoryTest
 *
 * @package App\Tests\Repository
 */
class ProdutoRepositoryTest extends KernelTestCase
{
    use ApagaBanco;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Ativa o setup do teste carregando Container
     */
    public function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @return \App\Repository\ProdutoRepository
     */
    private function getProdutoRepo()
    {
        return $this->em->getRepository(Produto::class);
    }

    /**
     * Testa se a pessoa possui Pedido
     */
    public function testPossuiItemDePedidoVinculadoAoProduto()
    {
        $itemPedidoFixture = new ProdutoItemPedidoFixtures();
        $produtoItemPedido = $itemPedidoFixture->load($this->em);

        $produto = $produtoItemPedido['produto'];

        $possuiPedido = $this->getProdutoRepo()->verificaItemProduto($produto);
        $this->assertNotEmpty($possuiPedido);
    }

    /**
     * Testa se a pessoa NÃO possui Pedido
     */
    public function testNaoPossuiItemDePedidoVinculadoAoProduto()
    {
        $produtoFixture = new ProdutoFixtures();
        $produto = $produtoFixture->load($this->em);

        $possuiProdutoVinculadoAoItem = $this->getProdutoRepo()->verificaItemProduto($produto[0]);
        $this->assertEmpty($possuiProdutoVinculadoAoItem);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->apagarBanco($this->em);
    }
}
