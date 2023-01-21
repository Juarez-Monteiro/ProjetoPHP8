<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\Conta1Type;
use App\Repository\ContaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administrador')]
class AdministradorController extends AbstractController
{
    #[Route('/', name: 'app_administrador_index', methods: ['GET'])]
    public function index(ContaRepository $contaRepository): Response
    {
        return $this->render('administrador/index.html.twig', [
            'contas' => $contaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_administrador_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContaRepository $contaRepository): Response
    {
        $contum = new Conta();
        $form = $this->createForm(Conta1Type::class, $contum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contaRepository->save($contum, true);

            return $this->redirectToRoute('app_administrador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('administrador/new.html.twig', [
            'contum' => $contum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administrador_show', methods: ['GET'])]
    public function show(Conta $contum): Response
    {
        return $this->render('administrador/show.html.twig', [
            'contum' => $contum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administrador_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conta $contum, ContaRepository $contaRepository): Response
    {
        $form = $this->createForm(Conta1Type::class, $contum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contaRepository->save($contum, true);

            return $this->redirectToRoute('app_administrador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('administrador/edit.html.twig', [
            'contum' => $contum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administrador_delete', methods: ['POST'])]
    public function delete(Request $request, Conta $contum, ContaRepository $contaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contum->getId(), $request->request->get('_token'))) {
            $contaRepository->remove($contum, true);
        }

        return $this->redirectToRoute('app_administrador_index', [], Response::HTTP_SEE_OTHER);
    }
}
