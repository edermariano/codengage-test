<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemPedidoRepository")
 */
class ItemPedido
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true, options={"comment": "PK do registro"})
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var \App\Entity\PedidoVenda
     * @ORM\ManyToOne(targetEntity="\App\Entity\PedidoVenda", inversedBy="items")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $pedido;

    /**
     * @var \App\Entity\Produto
     * @ORM\ManyToOne(targetEntity="\App\Entity\Produto")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $produto;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Quantidade de itens/produtos"})
     */
    private $quantidade;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Preço do produto da época. Proveniente da tabela de produtos"})
     */
    private $precoUnitario;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Percentual de desconto aplicado"})
     */
    private $percentualDesconto;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Valor total já aplicado o desconto e multiplicada a quantidade"})
     */
    private $total;

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\PedidoVenda
     */
    public function getPedido(): \App\Entity\PedidoVenda
    {
        return $this->pedido;
    }

    /**
     * @param \App\Entity\PedidoVenda $pedido
     */
    public function setPedido(\App\Entity\PedidoVenda $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * @return \App\Entity\Produto
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param \App\Entity\Produto $produto
     */
    public function setProduto($produto)
    {
        $this->produto = $produto;
    }

    /**
     * @return float
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param float $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return float
     */
    public function getPrecoUnitario()
    {
        return $this->precoUnitario;
    }

    /**
     * @param float $precoUnitario
     */
    public function setPrecoUnitario($precoUnitario)
    {
        $this->precoUnitario = $precoUnitario;
    }

    /**
     * @return mixed
     */
    public function getPercentualDesconto()
    {
        return $this->percentualDesconto;
    }

    /**
     * @param mixed $percentualDesconto
     */
    public function setPercentualDesconto($percentualDesconto)
    {
        $this->percentualDesconto = $percentualDesconto;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}
