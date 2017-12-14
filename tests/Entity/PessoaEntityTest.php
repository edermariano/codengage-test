<?php

namespace App\Tests\Entity;

use App\DataFixtures\Pessoas\PessoaFixture;
use App\Entity\Pessoa;
use App\Tests\ApagaBanco;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PessoaEntityTest
 *
 * @package App\Tests
 */
class PessoaEntityTest extends KernelTestCase
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
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getPessoaRepo()
    {
        return $this->em->getRepository(Pessoa::class);
    }

    /**
     * Teste de listagem simples de pessoas
     */
    public function testDeveListarPessoas()
    {
        $pessoas = new PessoaFixture();
        $pessoas->load($this->em);
        $pessoas->load($this->em);
        $pessoas->load($this->em);
        $pessoas->load($this->em);
        $pessoas->load($this->em);

        $pessoas = $this->getPessoaRepo()->findAll();
        $this->assertCount(5, $pessoas, 'legal');
    }

    /**
     * Teste de criação de pessoas
     */
    public function testDeveCriarPessoa()
    {
        $pessoa = new Pessoa();
        $pessoa->setNome('Criando Pessoa Novo');
        $pessoa->setDataNascimento(new \DateTime('-20 years'));

        $this->em->persist($pessoa);
        $this->em->flush();

        $this->assertNotNull($pessoa->getId());
    }

    /**
     * Teste de criação de pessoas
     * @dataProvider mesmoNomeDePessoaProvider
     * @expectedException \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function testDeveFalharAoCriarPessoaDuplicado($nome1, $nome2, $mensagem)
    {
        $pessoa = new Pessoa();
        $pessoa->setNome($nome1);
        $pessoa->setDataNascimento(new \DateTime('-20 years'));
        $this->em->persist($pessoa);
        $this->em->flush();

        $pessoaDuplicado = new Pessoa();
        $pessoaDuplicado->setNome($nome2);
        $pessoaDuplicado->setDataNascimento(new \DateTime('-20 years'));
        $this->em->persist($pessoaDuplicado);
        $this->em->flush();

        $this->assertNotNull($pessoa->getId());
    }

    /**
     * Provider de mesmo nome
     * @return array
     */
    public function mesmoNomeDePessoaProvider()
    {
        return [
            ['PessoaA', 'PessoaA', 'pessoa.nome.unico'],
            ['Pessoa A', 'Pessoa A', 'pessoa.nome.unico'],
            ['Pessoa 0', 'Pessoa 0', 'pessoa.nome.unico'],
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->apagarBanco($this->em);
    }
}
