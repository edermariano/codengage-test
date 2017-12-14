<?php

namespace App\Repository;

use App\Entity\ItemPedido;
use App\Entity\PedidoVenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PedidoVendaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PedidoVenda::class);
    }

    public function remove($pedidoVenda)
    {
        $query = $this->_em->createQueryBuilder()
            ->delete(ItemPedido::class, 'ip')
            ->where('ip.pedido = :pedido')
            ->setParameter('pedido', $pedidoVenda->getId());
        $query->getQuery()->getResult();

        $query = $this->_em->createQueryBuilder()
            ->delete(PedidoVenda::class, 'p')
            ->where('p.id = :pedido')
            ->setParameter('pedido', $pedidoVenda->getId());
        $query->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function getNumeroAtual()
    {
        $query = $this->createQueryBuilder('p')
            ->select('MAX(p.numero)')
            ->orderBy('p.numero', 'DESC')
            ->setMaxResults(1);

        $numero = $query->getQuery()->getSingleScalarResult();
        return $numero ?: 0;
    }

    public function listar($termos)
    {
        $query = $this->createQueryBuilder('p');
        $query->addSelect('pe')
            ->addSelect('i')
            ->addSelect('pro')
            ->join('p.cliente', 'pe')
            ->join('p.items', 'i')
            ->join('i.produto', 'pro');

        $query->orderBy('p.numero', 'DESC');
        $query->orderBy('p.cliente');
        $query->orderBy('p.emissao');
        $query->addOrderBy('p.total');

        if($termos) {
            foreach ($termos as $key => $termo) {
                $termo = "%$termo%";
                $query->orWhere($query->expr()->like('pe.nome', ":termo$key"));
                $query->orWhere($query->expr()->like('p.numero', ":termo$key"));
                $query->orWhere($query->expr()->like('p.emissao', ":termo$key"));
                $query->orWhere($query->expr()->like('p.total', ":termo$key"));
                $query->orWhere($query->expr()->like('pro.nome', ":termo$key"));
                $query->orWhere($query->expr()->like('pro.codigo', ":termo$key"));
                $query->setParameter("termo$key", $termo);
            }
        }
        return $query->getQuery()->getResult();
    }
}
