<?php

namespace App\Controller;

use App\Entity\Gerente;
use App\Form\GerenteType;
use App\Repository\GerenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gerente')]
class GerenteController extends AbstractController
{
    #[Route('/', name: 'app_gerente_index', methods: ['GET'])]
    public function index(GerenteRepository $gerenteRepository): Response
    {
        return $this->render('gerente/index.html.twig', [
            'gerentes' => $gerenteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gerente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GerenteRepository $gerenteRepository): Response
    {
        $gerente = new Gerente();
        $form = $this->createForm(GerenteType::class, $gerente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gerenteRepository->save($gerente, true);

            return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gerente/new.html.twig', [
            'gerente' => $gerente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gerente_show', methods: ['GET'])]
    public function show(Gerente $gerente): Response
    {
        return $this->render('gerente/show.html.twig', [
            'gerente' => $gerente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gerente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gerente $gerente, GerenteRepository $gerenteRepository): Response
    {
        $form = $this->createForm(GerenteType::class, $gerente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gerenteRepository->save($gerente, true);

            return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gerente/edit.html.twig', [
            'gerente' => $gerente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gerente_delete', methods: ['POST'])]
    public function delete(Request $request, Gerente $gerente, GerenteRepository $gerenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gerente->getId(), $request->request->get('_token'))) {
            $gerenteRepository->remove($gerente, true);
        }

        return $this->redirectToRoute('app_gerente_index', [], Response::HTTP_SEE_OTHER);
    }
}
