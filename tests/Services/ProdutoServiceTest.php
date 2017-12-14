<?php

namespace App\Tests\Services;

use App\Entity\ItemPedido;
use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use App\Services\ProdutoService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProdutoServiceTests
 *
 * @package \App\Tests\Services
 */
class ProdutoServiceTest  extends KernelTestCase
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

    public function mockVerificaItemsDePedidosRepo($produto, $return = false) {
        $repoMock = $this
            ->getMockBuilder(ProdutoRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repoMock->expects($this->once())
            ->method('verificaItemProduto')->with($produto)
            ->will($this->returnValue($return ? [new ItemPedido()] : null));

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
    public function testNaoPodeRemoverProdutoComPedido()
    {
        $produto = new Produto();
        // Mock do Repositório
        $produtoRepository = $this->mockVerificaItemsDePedidosRepo($produto, true);

        // Mock do Manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($produtoRepository));

        // Mock do Validator
        $validator = $this->mockValidator();

        /** @var ProdutoService $produtoService */
        $produtoService = new ProdutoService($entityManager, $validator);
        $produtoService->removerProduto(new Produto());
    }

    public function testPodeRemoverProdutoSemPedido()
    {
        $produto = new Produto();

        // Mock do Repositório
        $produtoRepository = $this->mockVerificaItemsDePedidosRepo($produto);

        // Mock do Manager
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($produtoRepository));
        $entityManager->expects($this->once())->method('remove')->with($produto);
        $entityManager->expects($this->once())->method('flush');

        // Mock do Validator
        $validator = $this->mockValidator();

        /** @var ProdutoService $produtoService */
        $produtoService = new ProdutoService($entityManager, $validator);
        $produtoService->removerProduto($produto);
    }
}
