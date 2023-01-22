<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conta;
use App\Entity\Agencia;
use App\Entity\Gerente;
use App\Form\ContaType;
use App\Entity\Transacao;
use App\Form\DepositoType;
use App\Form\TransacaoType;
use App\Form\DepositoClienteType;
use App\Repository\UserRepository;
use App\Repository\ContaRepository;
use App\Repository\AgenciaRepository;
use App\Form\DepositoSaqueClienteType;
use App\Repository\TransacaoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cliente')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ClienteController extends AbstractController
{
    
    #[Route('/{id}', name: 'app_cliente_show', methods: ['GET'])]
    public function show(User $user, ContaRepository $contaRepository): Response
    {
        $minhasContas = $contaRepository->findBy(['user' => $user->getId() ]);
            return $this->render('conta_cliente/index.html.twig', [
            'user' => $user,
            'minhasContas'=> $minhasContas,
        ]);
    }

    #[Route('/{id}/{conta}', name: 'app_cliente_showConta', methods: ['GET'])]
    public function showConta(User $user,TransacaoRepository $transacaoRepository, ContaRepository $contaRepository, $conta): Response
    {
        $Conta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta ]);
        $Extrato = $transacaoRepository->findByConta($Conta);
        return $this->render('conta_cliente/show.html.twig', [
            'user' => $user,
            'contum'=> $Conta,
            'extrato'=> $Extrato,
        ]);
    }



    #[Route('/{id}/new/conta', name: 'app_conta_cliente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, User $user, ContaRepository $contaRepository): Response
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

        return $this->renderForm('conta_cliente/new.html.twig', [
            'contum' => $contum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{conta}/transferencia', name: 'app_Transferencia_cliente_new', methods: ['GET', 'POST'])]
    public function newTransferencia(Request $request,$conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user, UserRepository $userRepository): Response
    {
        $minhaconta = $contaRepository->findOneBy(['user' => $user->getId(), 'status' => true, 'id' => $conta]);
        //dd($this->getUser());
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Transferencia');
            $transacao->setContaOrigem($minhaconta);
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_cliente_new');
            }
            
            if($minhaconta->getSaldo() < $valor ){
                $this->addFlash('error', 'Valor maior que Saldo!');
                return $this->redirectToRoute('app_transacaoTransferencia_cliente_new');
            }
            $minhaconta->setSaldo($minhaconta->getSaldo() - $valor);
            $entityManager->persist($minhaconta);
            $numeroDados = $transacao->getContaDestino();
            $numeroDaConta = $numeroDados->getNumeroDaConta();
            $valor = $transacao->getValor();
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_showConta', ['id'=> $user->getId(), 'conta'=> $minhaconta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/new.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{conta}/depositar', name: 'app_Depositar_cliente_new', methods: ['GET', 'POST'])]
    public function newDepositar(Request $request,Conta $conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user, UserRepository $userRepository): Response
    {
        $minhaconta = $contaRepository->findOneBy(['user' => $user->getId(), 'status' => true, 'id' => $conta]);
        $transacao = new Transacao();
        $form = $this->createForm(DepositoSaqueClienteType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Deposito');
            $transacao->setContaDestino($minhaconta);
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_cliente_new');
            }
            $minhaconta->setSaldo($minhaconta->getSaldo() + $valor);
            $entityManager->persist($minhaconta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_showConta', ['id'=> $user->getId(), 'conta'=> $minhaconta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/newSaque.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{conta}/sacar', name: 'app_Sacar_cliente_new', methods: ['GET', 'POST'])]
    public function newSacar(Request $request,$conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user, UserRepository $userRepository): Response
    {
        $minhaconta = $contaRepository->findOneBy(['user' => $user->getId(), 'status' => true, 'id' => $conta]);
        // dd($minhaconta);
        $transacao = new Transacao();
        $form = $this->createForm(DepositoSaqueClienteType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Saque');
            $transacao->setContaDestino($minhaconta);
            $valor = $transacao->getValor();
            if($valor <= 0 ){
                $this->addFlash('error', 'Iforme o valor maior que zero!');
                return $this->redirectToRoute('app_transacaoTransferencia_cliente_new');
            }
            if($minhaconta->getSaldo() < $valor ){
                $this->addFlash('error', 'Valor maior que Saldo!');
                return $this->redirectToRoute('app_transacaoTransferencia_cliente_new');
            }
            $minhaconta->setSaldo($minhaconta->getSaldo() - $valor);
            $entityManager->persist($minhaconta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_showConta', ['id'=> $user->getId(), 'conta'=> $minhaconta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/newSaque.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

}
