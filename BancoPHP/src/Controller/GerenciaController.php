<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GerenciaController extends AbstractController
{
    #[Route('/gerencia', name: 'app_gerencia')]
    public function index(): Response
    {
        return $this->render('gerencia/index.html.twig', [
            'controller_name' => 'GerenciaController',
        ]);
    }
}
