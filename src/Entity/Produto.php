<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProdutoRepository")
 * @UniqueEntity(fields={"id"}, message="produto.codigo.unico")
 * @UniqueEntity(fields={"codigo"}, message="produto.codigo.unico")
 * @UniqueEntity(fields={"nome"}, message="produto.nome.unico")
 */
class Produto
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
     * @Assert\Length(max="100", min="1", maxMessage="produto.codigo.maximo", minMessage="produto.codigo.minimo")
     * @ORM\Column(type="string", length=100, unique=true,
     *     options={"comment": "Código único do produto"})
     */
    private $codigo;

    /**
     * @Assert\Length(max="100", min="1", maxMessage="produto.nome.maximo", minMessage="produto.nome.minimo")
     * @ORM\Column(type="string", length=100, unique=true,
     *     options={"comment": "Nome único do produto"})
     */
    private $nome;

    /**
     * @Assert\GreaterThan(0, message="produto.preco.obrigatorio")
     * @ORM\Column(type="decimal", scale=2,
     *     options={"comment": "Preço do produto"})
     */
    private $preco;

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * @param mixed $preco
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
    }
}
