<?php

namespace App\DataFixtures\Pedidos;
use App\DataFixtures\Pessoas\PessoaFixture;
use App\Entity\PedidoVenda;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PedidoFixture
 *
 * @package \App\DataFixtures\Pedidos
 */
class PedidoFixture extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @return \App\Entity\PedidoVenda
     */
    public function load(ObjectManager $manager)
    {
        $pessoaFixture = new PessoaFixture();
        $cliente = $pessoaFixture->load($manager);

        $pedidos = $manager->getRepository(PedidoVenda::class)
            ->findAll();

        $pedido = new PedidoVenda();
        $pedido->setNumero(count($pedidos) + 1);
        $pedido->setEmissao(new \DateTime());
        $pedido->setCliente($cliente);
        $pedido->setTotal(10);

        $manager->persist($pedido);
        $manager->flush();

        return $pedido;
    }
}
