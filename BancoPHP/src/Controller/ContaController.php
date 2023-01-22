<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\ContaType;
use App\Form\ContaGerenteType;
use App\Repository\ContaRepository;
use Container8xnAxK7\getUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/conta')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ContaController extends AbstractController
{
    #[Route('/', name: 'app_conta_index', methods: ['GET'])]
    #[IsGranted('ROLE_GERENTE')]
    public function index(ContaRepository $contaRepository): Response
    {
       
        return $this->render('conta/index.html.twig', [
            'contas' => $contaRepository->findAll('id'),
            
        ]);
    }

    #[Route('/new', name: 'app_conta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContaRepository $contaRepository): Response
    {
        $contum = new Conta();
        $form = $this->createForm(ContaType::class, $contum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contum->setStatus(false);
            $contum->setNumeroDaConta(rand(1000, 9999));
            $contum->setUser($this->getUser());
            $contaRepository->save($contum, true);
            $this->addFlash('success', 'Conta criada com sucesso!');
            return $this->redirectToRoute('app_cliente_show', ['id'=> $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conta/new.html.twig', [
            'contum' => $contum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conta_show', methods: ['GET'])]
    public function show(Conta $contum): Response
    {
        return $this->render('conta/show.html.twig', [
            'contum' => $contum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conta $contum, ContaRepository $contaRepository): Response
    {
        $form = $this->createForm(ContaGerenteType::class, $contum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contaRepository->save($contum, true);

            return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conta/edit.html.twig', [
            'contum' => $contum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conta_delete', methods: ['POST'])]
    public function delete(Request $request, Conta $contum, ContaRepository $contaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contum->getId(), $request->request->get('_token'))) {
            $contaRepository->remove($contum, true);
        }

        return $this->redirectToRoute('app_conta_index', [], Response::HTTP_SEE_OTHER);
    }

   

}
