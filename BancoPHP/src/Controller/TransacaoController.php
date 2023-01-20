<?php

namespace App\Controller;

use App\Entity\Transacao;
use App\Form\TransacaoType;
use App\Form\TransacaoTransferenciaType;
use App\Repository\UserRepository;
use App\Repository\TransacaoRepository;
use App\Repository\ContaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/transacao')]
class TransacaoController extends AbstractController
{
    #[Route('/', name: 'app_transacao_index', methods: ['GET'])]
    public function index(TransacaoRepository $transacaoRepository): Response
    {
        return $this->render('transacao/index.html.twig', [
            'transacaos' => $transacaoRepository->findAll('id' ),
        ]);
    }

    #[Route('/new', name: 'app_transacao_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, EntityManagerInterface $entityManager): Response
    {
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoType::class, $transacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('deposito');
            $numeroDados = $transacao->getContaDestino();
            $numeroDaConta = $numeroDados->getNumeroDaConta();
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_new');
            }
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_transacao_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/new.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }
   
    #[Route('/newsac', name: 'app_transacaosac_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function newSac(Request $request, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, EntityManagerInterface $entityManager): Response
    {
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoType::class, $transacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('saque');
            $user = $this->getUser();
            $numeroDados = $transacao->getContaDestino();
            $numeroDaConta = $numeroDados->getNumeroDaConta();
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_new');
            }
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            if($conta->getSaldo() < $valor ){
                $this->addFlash('error', 'Valor maior que Saldo!');
                return $this->redirectToRoute('app_transacaoTransferencia_new');
            }
            $conta->setSaldo($conta->getSaldo() - $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_transacao_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/new.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/newtransferencia', name: 'app_transacaoTransferencia_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function newTransferencia(Request $request, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        // $this->getUser();
        // dd($this->getUser());
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoTransferenciaType::class, $transacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Transferencia');
            $numeroDados = $transacao->getContaOrigem();
            $numeroDaConta = $numeroDados->getNumeroDaConta();
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_new');
            }
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            if($conta->getSaldo() < $valor ){
                $this->addFlash('error', 'Valor maior que Saldo!');
                return $this->redirectToRoute('app_transacaoTransferencia_new');
            }
            $conta->setSaldo($conta->getSaldo() - $valor);
            $entityManager->persist($conta);
            $numeroDados = $transacao->getContaDestino();
            $numeroDaConta = $numeroDados->getNumeroDaConta();
            $valor = $transacao->getValor();
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_transacaoTransferencia_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacaoTransferencia/new.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transacao_show', methods: ['GET'])]
    public function show(Transacao $transacao): Response
    {
        return $this->render('transacao/show.html.twig', [
            'transacao' => $transacao,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_transacao_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transacao $transacao, TransacaoRepository $transacaoRepository): Response
    {
        $form = $this->createForm(TransacaoType::class, $transacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_transacao_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/edit.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transacao_delete', methods: ['POST'])]
    public function delete(Request $request, Transacao $transacao, TransacaoRepository $transacaoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transacao->getId(), $request->request->get('_token'))) {
            $transacaoRepository->remove($transacao, true);
        }

        return $this->redirectToRoute('app_transacao_index', [], Response::HTTP_SEE_OTHER);
    }

}
