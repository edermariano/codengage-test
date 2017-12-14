<?php

namespace App\Repository;

use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PessoaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pessoa::class);
    }

    public function verificaPedidos(Pessoa $pessoa)
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('pedido')
            ->from(PedidoVenda::class, 'pedido')
            ->where('pedido.cliente = :cliente')
            ->setParameter('cliente', $pessoa)
            ->setMaxResults(1)
        ;

        return $query->getQuery()->getOneOrNullResult();
    }

    public function listar($termos)
    {
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.nome');
        $query->addOrderBy('p.dataNascimento');

        if($termos) {
            foreach ($termos as $key => $termo) {
                $termo = "%$termo%";
                $query->orWhere($query->expr()->like('p.nome', ":termo$key"));
                $query->orWhere($query->expr()->like('p.dataNascimento', ":termo$key"));
                $query->setParameter("termo$key", $termo);
            }
        }

        return $query->getQuery()->getResult();
    }
}
