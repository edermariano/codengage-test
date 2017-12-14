<?php

namespace App\Controller;

use App\Entity\Pessoa;
use App\Form\PessoaType;
use App\Services\PessoaService;
use PHPUnit\Runner\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PessoaController
 *
 * @package App\Controller
 * @Route("/pessoas")
 */
class PessoaController extends Controller
{
    /**
     * @Route("/", name="pessoas")
     */
    public function index(Request $request)
    {
        $pessoa = new Pessoa();
        $form = $this->createForm(PessoaType::class, $pessoa);

        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $sucesso = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            $sucesso = 'Cadastro realizado com sucesso!';
            $form = $this->createForm(PessoaType::class, new Pessoa());
        }

        $search = $request->query->get('search');
        $pessoas = $this->get(PessoaService::class)
            ->listar($search);

        return $this->renderPessoa($pessoas, $form, 'new', $sucesso);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Pessoa                        $pessoa
     * @Route("/{id}/edit", name="pessoa_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Pessoa $pessoa): Response
    {
        $form = $this->createForm(PessoaType::class, $pessoa);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $sucesso = $request->get('msg', false);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $sucesso = 'Cadastro atualizado com sucesso!';
            return $this->redirectToRoute('pessoa_edit', [
                'id' => $pessoa->getId(),
                'msg' => $sucesso
            ]);
        }
        $search = $request->query->get('search');
        $pessoas = $this->get(PessoaService::class)
            ->listar($search);

        return $this->renderPessoa($pessoas, $form, 'edit', $sucesso);
    }

    /**
     * @param \App\Entity\Pessoa $pessoa
     * @Route("/{id}/delete", name="pessoa_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Pessoa $pessoa)
    {
        $params = [];
        try {

            /** @var PessoaService $pessoaService */
            $pessoaService = $this->get(PessoaService::class);
            $pessoaService->removerPessoa($pessoa);

        } catch (\Exception $e) {
            $params = ['erro' => $e->getMessage()];
        }
        return $this->redirectToRoute('pessoas', $params);
    }

    public function renderPessoa($lista, $form, $acao = 'new', $mensagem = false)
    {
        return $this->render('pessoa/pessoa.html.twig', [
            'pessoas' => $lista,
            'acao' => $acao,
            'sucesso' => $mensagem,
            'form' => $form->createView(),
        ]);
    }
}
