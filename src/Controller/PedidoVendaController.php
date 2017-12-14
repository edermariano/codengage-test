<?php

namespace App\Controller;

use App\Entity\PedidoVenda;
use App\Entity\Produto;
use App\Form\PedidoVendaType;
use App\Services\PedidoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PedidoVendaController
 *
 * @package App\Controller
 * @Route("/pedidos")
 */
class PedidoVendaController extends Controller
{
    /**
     * @Route("/", name="pedidos")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(PedidoVendaType::class, new PedidoVenda());
        /** @var PedidoService $pedidoService */
        $pedidoService = $this->get(PedidoService::class);

        $form->handleRequest($request);
        $sucesso = false;
        $erro = false;

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $pedidoService->salvarPedido($request->request->get('pedido_venda'));
                $sucesso = 'Cadastro realizado com sucesso!';
            } catch (\Exception $e) {
                $erro = $e->getMessage();
            }

            $form = $this->createForm(PedidoVendaType::class, new PedidoVenda());
        }
        $em = $this->getDoctrine()->getManager();
        $em->clear();

        return $this->render('pedido/pedido.html.twig', [
            'produtos' => $em->getRepository(Produto::class)->findAll(),
            'pedidos' => $this->get(PedidoService::class)->listar($request->query->get('search')),
            'erro' => $erro,
            'sucesso' => $sucesso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \App\Entity\PedidoVenda $pedidoVenda
     * @Route("/{id}/delete", name="pedido_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(PedidoVenda $pedidoVenda)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(PedidoVenda::class)
            ->remove($pedidoVenda);
        return $this->redirectToRoute('pedidos');
    }
}
