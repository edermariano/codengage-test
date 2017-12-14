<?php

namespace App\Tests\Services;
use App\Entity\ItemPedido;
use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use App\Repository\PessoaRepository;
use App\Services\PessoaService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PessoaServiceTests
 *
 * @package \App\Tests\Services
 */
class PessoaServiceTest  extends KernelTestCase
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * Ativa o setup do teste carregando Container
     */
    public function setUp()
    {
        $kernel = self::bootKernel();

        $this->container = $kernel->getContainer();
    }

    public function mockVerificaPedidosRepo($pessoa, $return = false) {
        $repoMock = $this
            ->getMockBuilder(PessoaRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repoMock->expects($this->once())
            ->method('verificaPedidos')->with($pessoa)
            ->will($this->returnValue($return ? [new PedidoVenda()] : []));

        return $repoMock;
    }

    public function mockValidator()
    {
        return $this
            ->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testNaoPodeRemoverPessoaComPedido()
    {
        $pessoa = new Pessoa();
        // Mock do Repositório
        $pessoaRepository = $this->mockVerificaPedidosRepo($pessoa, true);

        // Mock do Manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($pessoaRepository));

        // Mock do Validator
        $validator = $this->mockValidator();

        /** @var PessoaService $pessoaService */
        $pessoaService = new PessoaService($entityManager, $validator);
        $pessoaService->removerPessoa(new Pessoa());
    }

    public function testPodeRemoverPessoaSemPedido()
    {
        $pessoa = new Pessoa();

        // Mock do Repositório
        $pessoaRepository = $this->mockVerificaPedidosRepo($pessoa);

        // Mock do Manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($pessoaRepository));
        $entityManager->expects($this->once())->method('remove')->with($pessoa);
        $entityManager->expects($this->once())->method('flush');

        // Mock do Validator
        $validator = $this->mockValidator();

        /** @var PessoaService $pessoaService */
        $pessoaService = new PessoaService($entityManager, $validator);
        $pessoaService->removerPessoa($pessoa);
    }
}
