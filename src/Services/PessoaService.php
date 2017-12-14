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
class PessoaService
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

    public function removerPessoa(Pessoa $pessoa)
    {
        /** @var \App\Repository\PessoaRepository $pessoaRepo */
        $pessoaRepo = $this->em->getRepository(Pessoa::class);
        $possuiPedidos = $pessoaRepo->verificaPedidos($pessoa);

        if($possuiPedidos) {
            throw new BadRequestHttpException('Usuário possui pedidos, não é possível deletar.');
        }

        $this->em->remove($pessoa);
        $this->em->flush();
    }

    public function listar($search) {
        /** @var \App\Repository\PessoaRepository $pessoasRepo */
        $pessoasRepo = $this->em->getRepository(Pessoa::class);

        $termos = false;
        if($search) {
            $termos = explode(" ", $search);
            foreach ($termos as $key => $termo) {
                $data = explode("/", $termo);
                $termos[$key] = implode("-", array_reverse($data));
            }
        }

        return $pessoasRepo->listar($termos);
    }

}
