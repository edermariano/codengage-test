<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PedidoVendaRepository")
 * @ORM\Table(name="pedido_venda", indexes={@ORM\Index(name="numero_ai_ix", columns={"numero"})})
 */
class PedidoVenda
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
     * @var \App\Entity\Pessoa
     *
     * @ORM\ManyToOne(targetEntity="\App\Entity\Pessoa")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $cliente;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"comment": "Número auto incrementado do pedido."})
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date",
     *     options={"comment": "Data de emissão do Pedido"})
     */
    private $emissao;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Valor total do pedido"})
     */
    private $total;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ItemPedido", mappedBy="pedido")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\Pessoa
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param \App\Entity\Pessoa $cliente
     */
    public function setCliente(Pessoa $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return \DateTime
     */
    public function getEmissao()
    {
        return $this->emissao;
    }

    /**
     * @param \DateTime $emissao
     */
    public function setEmissao($emissao)
    {
        $this->emissao = $emissao;
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

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }
}
