<?php

namespace App\Tests;

use App\Entity\ItemPedido;
use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use App\Entity\Produto;
use Doctrine\ORM\EntityManager;

/**
 * Class ApagaBanco
 *
 * @package \\${NAMESPACE}
 */
trait ApagaBanco
{

    /**
     * Remove todos os registros de todas as tabelas
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function apagarBanco(EntityManager $em) {
        $qb = $em->createQueryBuilder();
        $qb->delete()
            ->from(ItemPedido::class, 'i')
            ->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->delete()
            ->from(Produto::class, 'p')
            ->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->delete()
            ->from(PedidoVenda::class, 'pv')
            ->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->delete()
            ->from(Pessoa::class, 'pe')
            ->getQuery()->getResult();
    }
}
