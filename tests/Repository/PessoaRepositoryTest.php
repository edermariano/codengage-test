<?php

namespace App\Tests\Repository;

use App\DataFixtures\Pedidos\PedidoFixture;
use App\DataFixtures\Pessoas\PessoaFixture;
use App\Entity\Pessoa;
use App\Tests\ApagaBanco;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PessoaRepositoryTest
 *
 * @package App\Tests\Repository
 */
class PessoaRepositoryTest extends KernelTestCase
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
     * @return \App\Repository\PessoaRepository
     */
    private function getPessoaRepo()
    {
        return $this->em->getRepository(Pessoa::class);
    }

    /**
     * Testa se a pessoa possui Pedido
     */
    public function testPossuiPedido()
    {
        $pedidoFixture = new PedidoFixture();
        $pedido = $pedidoFixture->load($this->em);

        $pessoa = $pedido->getCliente();

        $possuiPedido = $this->getPessoaRepo()->verificaPedidos($pessoa);
        $this->assertNotEmpty($possuiPedido);
    }

    /**
     * Testa se a pessoa NÃO possui Pedido
     */
    public function testNaoPossuiPedido()
    {
        $pessoaFixture = new PessoaFixture();
        $pessoa = $pessoaFixture->load($this->em);

        $possuiPedido = $this->getPessoaRepo()->verificaPedidos($pessoa);
        $this->assertEmpty($possuiPedido);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->apagarBanco($this->em);
    }
}
