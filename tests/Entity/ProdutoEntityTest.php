<?php

namespace App\Tests\Entity;

use App\DataFixtures\Produtos\ProdutoFixtures;
use App\Entity\Produto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ProdutoEntityTest
 *
 * @package App\Tests
 */
class ProdutoEntityTest extends KernelTestCase
{

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
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getProdutoRepo()
    {
        return $this->em->getRepository(Produto::class);
    }

    /**
     * Teste de listagem simples de produtos
     */
    public function testDeveListarProdutos()
    {
        $produtos = new ProdutoFixtures();
        $produtos->load($this->em);

        $produtos = $this->getProdutoRepo()->findAll();
        $this->assertCount(10, $produtos, 'legal');
    }

    /**
     * Teste de criação de produtos
     */
    public function testDeveCriarProduto()
    {
        $codigo = uniqid('gerado por teste');
        $produto = new Produto();
        $produto->setNome('Criando Produto Novo');
        $produto->setPreco(200.58);
        $produto->setCodigo($codigo);

        $this->em->persist($produto);
        $this->em->flush();

        $this->assertNotNull($produto->getId());
    }

    /**
     * Teste de criação de produtos
     * @dataProvider mesmoNomeDeProdutoProvider
     * @expectedException \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function testDeveFalharAoCriarProdutoDuplicado($nome1, $nome2, $mensagem)
    {
        $produto = new Produto();
        $produto->setNome($nome1);
        $produto->setPreco(200.58);
        $produto->setCodigo(uniqid('gerado por teste'));
        $this->em->persist($produto);
        $this->em->flush();

        $produtoDuplicado = new Produto();
        $produtoDuplicado->setNome($nome2);
        $produtoDuplicado->setPreco(200.58);
        $produtoDuplicado->setCodigo(uniqid('gerado por teste'));
        $this->em->persist($produtoDuplicado);
        $this->em->flush();

        $this->assertNotNull($produto->getId());
    }

    /**
     * Provider de mesmo nome
     * @return array
     */
    public function mesmoNomeDeProdutoProvider()
    {
        return [
            ['ProdutoA', 'ProdutoA', 'produto.nome.unico'],
            ['Produto A', 'Produto A', 'produto.nome.unico'],
            ['Produto 0', 'Produto 0', 'produto.nome.unico'],
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
        $qb = $this->em->createQueryBuilder();
        $qb->delete()
            ->from(Produto::class, 'p')
            ->getQuery()->getResult();
    }
}
