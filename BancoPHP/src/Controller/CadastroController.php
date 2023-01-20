<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CadastroController extends AbstractController
{
    #[Route('/cadastro', name: 'app_cadastro')]
    public function index(): Response
    {
        return $this->render('cadastro/index.html.twig', [
            'controller_name' => 'CadastroController',
        ]);
    }
}
