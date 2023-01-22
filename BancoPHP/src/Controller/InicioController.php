<?php

namespace App\Controller;

use App\Repository\AgenciaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    #[Route('/', name: 'app_inicio')]
    public function index(AgenciaRepository $agenciaRepository, UserRepository $userRepository): Response
    {
        $agencias = $agenciaRepository->findAll();
        return $this->render('inicio/index.html.twig', [
            'controller_name' => 'InicioController',
            'agencias' => $agencias,
        ]);
    }

    
}
