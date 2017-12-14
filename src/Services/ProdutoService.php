<?php

namespace App\Services;

use App\Entity\ItemPedido;
use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use App\Entity\Produto;
use App\Traits\UtilNumero;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PedidoService
 *
 * @package \App\Services
 */
class ProdutoService
{
    use UtilNumero;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * PedidoService constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface                      $em
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    public function removerProduto(Produto $produto)
    {
        /** @var \App\Repository\ProdutoRepository $produtoRepo */
        $produtoRepo = $this->em->getRepository(Produto::class);
        $possuiPedidos = $produtoRepo->verificaItemProduto($produto);

        if($possuiPedidos) {
            throw new BadRequestHttpException('Produto vinculado a itens de pedidos, não é possível deletar.');
        }

        $this->em->remove($produto);
        $this->em->flush();
    }

    public function listar($search) {
        /** @var \App\Repository\ProdutoRepository $produtosRepo */
        $produtosRepo = $this->em->getRepository(Produto::class);

        $termos = false;
        if($search) {
            $termos = explode(" ", $search);
        }

        return $produtosRepo->listar($termos);
    }

}
