<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Form\ProdutoType;
use App\Services\ProdutoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProdutoController
 *
 * @package App\Controller
 * @Route("/produtos")
 */
class ProdutoController extends Controller
{
    /**
     * @Route("/", name="produtos")
     */
    public function index(Request $request)
    {
        $produto = new Produto();
        $form = $this->createForm(ProdutoType::class, $produto);

        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $sucesso = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            $sucesso = 'Cadastro realizado com sucesso!';
            $form = $this->createForm(ProdutoType::class, new Produto());
        }

        $search = $request->query->get('search');
        $produtos = $this->get(ProdutoService::class)
            ->listar($search);

        return $this->renderProduto($produtos, $form, 'new', $sucesso);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Produto                        $produto
     * @Route("/{id}/edit", name="produto_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Produto $produto): Response
    {
        $form = $this->createForm(ProdutoType::class, $produto);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $sucesso = $request->get('msg', false);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $sucesso = 'Cadastro atualizado com sucesso!';
            return $this->redirectToRoute('produto_edit', [
                'id' => $produto->getId(),
                'msg' => $sucesso
            ]);
        }
        $produtos = $em->getRepository(Produto::class)
            ->findAll();
        return $this->renderProduto($produtos, $form, 'edit', $sucesso);
    }

    /**
     * @param \App\Entity\Produto $produto
     * @Route("/{id}/delete", name="produto_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Produto $produto)
    {
        $params = [];
        try {

            /** @var ProdutoService $produtoService */
            $produtoService = $this->get(ProdutoService::class);
            $produtoService->removerProduto($produto);

        } catch (\Exception $e) {
            $params = ['erro' => $e->getMessage()];
        }
        return $this->redirectToRoute('produtos', $params);
    }

    public function renderProduto($lista, $form, $acao = 'new', $mensagem = false)
    {
        return $this->render('produto/produto.html.twig', [
            'produtos' => $lista,
            'acao' => $acao,
            'sucesso' => $mensagem,
            'form' => $form->createView(),
        ]);
    }
}
