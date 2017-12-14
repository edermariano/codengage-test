<?php

namespace App\Tests\Entity;

use App\DataFixtures\Pedidos\PedidoFixture;
use App\DataFixtures\Pessoas\PessoaFixture;
use App\Entity\PedidoVenda;
use App\Tests\ApagaBanco;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PedidoVendaEntityTest
 *
 * @package App\Tests
 */
class PedidoVendaEntityTest extends KernelTestCase
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
    private function getPedidoVendaRepo()
    {
        return $this->em->getRepository(PedidoVenda::class);
    }

    /**
     * Teste de listagem simples de pedidoVendas
     */
    public function testDeveListarPedidoVendas()
    {
        $pedidoVendas = new PedidoFixture();
        $pedidoVendas->load($this->em);
        $pedidoVendas->load($this->em);
        $pedidoVendas->load($this->em);

        $pedidoVendas = $this->getPedidoVendaRepo()->findAll();
        $this->assertCount(3, $pedidoVendas, 'legal');
    }

    /**
     * Teste de criação de pedidoVendas
     */
    public function testDeveCriarPedidoVenda()
    {
        $pessoaFixture = new PessoaFixture();
        $cliente = $pessoaFixture->load($this->em);

        $pedidoVenda = new PedidoVenda();
        $pedidoVenda->setNumero(1);
        $pedidoVenda->setTotal(0);
        $pedidoVenda->setEmissao(new \DateTime('-1 years'));
        $pedidoVenda->setCliente($cliente);

        $this->em->persist($pedidoVenda);
        $this->em->flush();

        $this->assertNotNull($pedidoVenda->getId());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->apagarBanco($this->em);
    }
}
