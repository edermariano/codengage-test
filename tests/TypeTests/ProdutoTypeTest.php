<?php

namespace App\Tests\TypeTests;

use App\Entity\Produto;
use App\Form\ProdutoType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProdutoTypeTest extends TypeTestCase
{
    public function testSubmeterFormularioDeProduto() {
        $dados = [
            'nome' => 'Nome do produto',
            'preco' => 10,
            'codigo' => 'abcd',
        ];

        $form = $this->factory->create(ProdutoType::class);

        $entidade = new Produto();
        $entidade->setNome($dados['nome']);
        $entidade->setPreco(10.00);
        $entidade->setCodigo('abcd');

        // submit the data to the form directly
        $form->submit($dados);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entidade, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($dados) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
