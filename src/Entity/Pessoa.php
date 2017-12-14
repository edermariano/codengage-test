<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaRepository")
 * @UniqueEntity(fields={"id"}, message="pessoa.id.unico")
 * @UniqueEntity(fields={"nome"}, message="pessoa.nome.unico")
 */
class Pessoa
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
     * @ORM\Column(type="string", length=100, unique=true,
     *     options={"comment": "Nome da pessoa, segundo requisito não pode se repetir"})
     * @Assert\Length(max="100", min="2", maxMessage="pessoa.nome.maximo", minMessage="pessoa.nome.minimo")
     */
    private $nome;

    /**
     * @ORM\Column(type="date",
     *     options={"comment": "Data de Nascimento da pessoa"})
     */
    private $dataNascimento;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * @param mixed $dataNascimento
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }
}
