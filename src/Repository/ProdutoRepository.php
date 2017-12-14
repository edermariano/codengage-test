<?php

namespace App\Repository;

use App\Entity\ItemPedido;
use App\Entity\Produto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProdutoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produto::class);
    }

    public function verificaItemProduto(Produto $produto)
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('item')
            ->from(ItemPedido::class, 'item')
            ->where('item.produto = :produto')
            ->setParameter('produto', $produto)
            ->setMaxResults(1)
        ;

        return $query->getQuery()->getOneOrNullResult();
    }

    public function listar($termos)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.codigo');
        $query->addOrderBy('p.nome');
        $query->addOrderBy('p.preco');

        if($termos) {
            foreach ($termos as $key => $termo) {
                $termo = "%$termo%";
                $query->orWhere($query->expr()->like('p.nome', ":termo$key"));
                $query->orWhere($query->expr()->like('p.codigo', ":termo$key"));
                $query->orWhere($query->expr()->like('p.preco', ":termo$key"));
                $query->setParameter("termo$key", $termo);
            }
        }

        return $query->getQuery()->getResult();
    }
}
