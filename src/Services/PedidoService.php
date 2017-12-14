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
class PedidoService
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

    /**
     * @param $dados
     */
    public function salvarPedido($dados)
    {
        if(!$dados['cliente'] || !isset($dados['items'])) {
            throw new BadRequestHttpException('Escolha o cliente e adcione items ao pedido!');
        }

        $cliente = $this->em->getPartialReference(Pessoa::class, $dados['cliente']);

        $pedido = new PedidoVenda();
        $pedido->setCliente($cliente);
        $pedido->setEmissao(new \DateTime());
        $pedido->setNumero($this->getProximoNumero());
        $pedido->setTotal(0);

        $this->validate($pedido);
        $this->em->persist($pedido);
        $this->salvarItemsPedido($pedido, $dados['items']);
    }

    /**
     * @param \App\Entity\PedidoVenda $pedido
     * @param                         $items
     */
    public function salvarItemsPedido(PedidoVenda $pedido, $items)
    {
        $totalPedido = 0;
        foreach($items as $dado) {
            $item = new ItemPedido();
            $item->setPedido($pedido);
            $this->encapsulaItem($item, $dado);

            $totalPedido += $item->getTotal();
            $this->validate($item);

            $this->em->persist($item);
        }
        $pedido->setTotal($totalPedido);
        $this->em->flush();
    }

    public function encapsulaItem(ItemPedido $item, $dado)
    {
        $produto = $this->em->getPartialReference(Produto::class, $dado["produto_id"]);
        $item->setProduto($produto);
        $item->setQuantidade($this->trataFloat($dado["quantidade"]));
        $item->setPercentualDesconto($this->trataFloat($dado["percentualDesconto"]));
        $item->setPrecoUnitario($this->trataFloat($dado["precoUnitario"]));
        $item->setTotal($this->trataFloat($dado["total"]));
    }

    public function validate($entidade)
    {
        $errors = $this->validator->validate($entidade);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            throw new BadRequestHttpException($errorsString);
        }
    }

    public function getProximoNumero()
    {
        $numeroAtual = $this->em->getRepository(PedidoVenda::class)
            ->getNumeroAtual();

        return $numeroAtual + 1;
    }

    public function listar($search)
    {
        /** @var \App\Repository\PedidoVendaRepository $pedidoVendaRepo */
        $pedidoVendaRepo = $this->em->getRepository(PedidoVenda::class);

        $termos = false;
        if($search) {
            $termos = explode(" ", $search);
            foreach ($termos as $key => $termo) {
                $data = explode("/", $termo);
                $termos[$key] = implode("-", array_reverse($data));
            }
        }

        return $this->mountResult($pedidoVendaRepo->listar($termos));
    }

    private function mountResult($listar)
    {
        $resultado = [];
        foreach($listar as $item) {
            /** @var $item PedidoVenda */
            $resultado[] = [
                'id' => $item->getId(),
                'total' => $item->getTotal(),
                'emissao' => $item->getEmissao(),
                'numero' => $item->getNumero(),
                'cliente' => $item->getCliente(),
                'produtos' => $this->getProdutosDoItem($item),
            ];
        }

        return $resultado;
    }

    private function getProdutosDoItem($item)
    {
        foreach($item->getItems() as $itemProduto) {
            $arProdutos[] = $itemProduto->getProduto()->getNome();
        }

        return implode(", ", $arProdutos);
    }
}
