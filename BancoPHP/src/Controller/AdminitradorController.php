<?php

namespace App\Controller;

use App\Entity\Gerente;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminitradorController extends AbstractController
{
    #[Route('/adminitrador', name: 'app_adminitrador')]
    public function CriarAdm(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setNome($request->request->get('nome'));
            $user->setIsVerified(true);
    
            $user->setRoles(['ROLE_GERENTE']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $request->request->get('password')));
                
            $gerente = new Gerente();
            $gerente->setUser($user);
    
            $entityManager->persist($gerente);
            $entityManager->flush();
            return $this->redirectToRoute('app_adminitrador');
        }
        return $this->render('adminitrador/index.html.twig', [
            'controller_name' => 'AdminitradorController',
         ]);
    }
       
    
}


    


