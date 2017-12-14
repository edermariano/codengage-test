<?php

namespace App\Tests\TypeTests;

use App\Entity\Pessoa;
use App\Form\PessoaType;
use Symfony\Component\Form\Test\TypeTestCase;

class PessoaTypeTest extends TypeTestCase
{
    public function testSubmeterFormularioDePessoa() {
        $dados = [
            'nome' => 'Eder Lamar',
            'dataNascimento' => ''
        ];

        $form = $this->factory->create(PessoaType::class);

        $entidade = new Pessoa();
        $entidade->setNome($dados['nome']);

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
