<?php

namespace App\Tests\Repository;

use App\DataFixtures\Pedidos\PedidoFixture;
use App\Entity\ItemPedido;
use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use App\Entity\Produto;
use App\Tests\ApagaBanco;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ProdutoEntityTest
 *
 * @package App\Tests
 */
class PedidoVendaRepositoryTest extends KernelTestCase
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
     * @return \App\Repository\PedidoVendaRepository
     */
    private function getPedidoRepo()
    {
        return $this->em->getRepository(PedidoVenda::class);
    }

    /**
     * Teste se o método traz o último número da sequência incremental
     */
    public function testNumeroAtual()
    {
        $pedidoFixture = new PedidoFixture();
        $pedidoFixture->load($this->em);
        $pedidoFixture->load($this->em);
        $pedidoFixture->load($this->em);
        $pedidoFixture->load($this->em);

        $proximoNumero = $this->getPedidoRepo()->getNumeroAtual();
        $this->assertEquals(4, $proximoNumero);
    }

    /**
     * Testa se traz 0 quando não tem ninguém cadastrado
     */
    public function testNumeroAtualEZero()
    {
        $proximoNumero = $this->getPedidoRepo()->getNumeroAtual();
        $this->assertEquals(0, $proximoNumero);
    }

    /**
     * Teste de Remoção de pedido e seus itens filhos
     */
    public function testRemovePedido()
    {
        $pedidoFixture = new PedidoFixture();
        $pedido = $pedidoFixture->load($this->em);

        $this->assertNotNull($pedido->getId());

        $this->getPedidoRepo()->remove($pedido);

        $this->assertEmpty($this->getPedidoRepo()->findAll());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->apagarBanco($this->em);
    }
}
