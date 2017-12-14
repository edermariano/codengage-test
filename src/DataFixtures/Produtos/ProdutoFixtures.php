<?php

namespace App\DataFixtures\Produtos;

use App\Entity\Produto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProdutoFixtures
 *
 * @package App\DataFixtures\Produto
 */
class ProdutoFixtures extends Fixture
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return array
     */
    public function load(ObjectManager $manager)
    {
        $produtos = [];
        for($i = 0; $i < 10; $i++) {
            $produto = new Produto();
            $produto->setCodigo($i);
            $produto->setNome('Produto-' . $i);
            $produto->setPreco(mt_rand(10, 100));

            $manager->persist($produto);
            $produtos[] = $produto;
        }

        $manager->flush();

        return $produtos;
    }
}
