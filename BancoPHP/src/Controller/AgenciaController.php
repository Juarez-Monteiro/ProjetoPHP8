<?php

namespace App\Controller;

use App\Entity\Agencia;
use App\Form\AgenciaType;
use App\Repository\AgenciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agencia')]
class AgenciaController extends AbstractController
{
    #[Route('/', name: 'app_agencia_index', methods: ['GET'])]
    public function index(AgenciaRepository $agenciaRepository): Response
    {
        return $this->render('agencia/index.html.twig', [
            'agencias' => $agenciaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AgenciaRepository $agenciaRepository): Response
    {
        $agencium = new Agencia();
        $form = $this->createForm(AgenciaType::class, $agencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenciaRepository->save($agencium, true);

            return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agencia/new.html.twig', [
            'agencium' => $agencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agencia_show', methods: ['GET'])]
    public function show(Agencia $agencium): Response
    {
        return $this->render('agencia/show.html.twig', [
            'agencium' => $agencium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agencia $agencium, AgenciaRepository $agenciaRepository): Response
    {
        $form = $this->createForm(AgenciaType::class, $agencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenciaRepository->save($agencium, true);

            return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agencia/edit.html.twig', [
            'agencium' => $agencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agencia_delete', methods: ['POST'])]
    public function delete(Request $request, Agencia $agencium, AgenciaRepository $agenciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agencium->getId(), $request->request->get('_token'))) {
            $agenciaRepository->remove($agencium, true);
        }

        return $this->redirectToRoute('app_agencia_index', [], Response::HTTP_SEE_OTHER);
    }
}
