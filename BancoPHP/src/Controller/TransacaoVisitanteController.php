<?php

namespace App\Controller;

use App\Entity\Transacao;
use App\Form\TransacaoType;
use App\Repository\UserRepository;
use App\Repository\TransacaoRepository;
use App\Repository\ContaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/transacaovisitante')]
class TransacaoVisitanteController extends AbstractController
{
    #[Route('/deposito', name: 'app_transacao_visitante_new', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_transacao_visitante_new');
            }
            $conta = $contaRepository->findOneBy(['numeroDaConta' => $numeroDaConta]);
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_inicio', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transacao/newDeposito.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }


}
