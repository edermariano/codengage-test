<?php

namespace App\DataFixtures\Pedidos;
use App\DataFixtures\Produtos\ProdutoFixtures;
use App\Entity\ItemPedido;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ItemPedidoFixtures
 *
 * @package \App\DataFixtures\Pedidos
 */
class ItemPedidoFixtures extends Fixture
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \App\Entity\PedidoVenda
     */
    public function load(ObjectManager $manager)
    {
        $produtoFixture = new ProdutoFixtures();
        $produtos = $produtoFixture->load($manager);

        $pedidoFixture = new PedidoFixture();
        $pedido = $pedidoFixture->load($manager);

        $totalPedido = 0;

        for($i = 0; $i < rand(1, 5); $i++) {
            /** @var \App\Entity\Produto $produto */
            $produto = $produtos[rand(0,4)];

            $item = new ItemPedido();
            $item->setPercentualDesconto(rand(0,40));
            $item->setProduto($produto);
            $item->setPedido($pedido);
            $item->setQuantidade(rand(0,4));
            $item->setPrecoUnitario($produto->getPreco());
            $item->setTotal($this->calculaTotal($item));

            $totalPedido += $item->getTotal();
            $manager->persist($item);
        }
        $pedido->setTotal($totalPedido);
        $manager->persist($pedido);
        $manager->flush();

        return $pedido;
    }

    /**
     * @param \App\Entity\ItemPedido $item
     *
     * @return float
     */
    private function calculaTotal(ItemPedido $item)
    {
        $precoBruto = $item->getPrecoUnitario() * $item->getQuantidade();
        $percentual = $item->getPercentualDesconto() / 100;
        $desconto = $precoBruto * $percentual;
        return $precoBruto - $desconto;
    }
}
