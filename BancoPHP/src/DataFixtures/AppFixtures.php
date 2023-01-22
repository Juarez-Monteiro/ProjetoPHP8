<?php

namespace App\DataFixtures;

use App\Entity\Agencia;
use App\Entity\Gerente;
use App\Entity\TipoConta;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $tipoConta1 = new TipoConta();
        $tipoConta1->setTipo("CC");

        $tipoConta2 = new TipoConta();
        $tipoConta2->setTipo("CP");

        $user1 = new User(); 
        $user1->setEmail('admin@gmail.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setPassword(
            $this->hasher->hashPassword($user1, '123456')
        );
        $user1->setStatus(true);
        $user1->setNome('Juarez');
        $manager->persist($user1);

        $user2 = new User(); 
        $user2->setEmail('gerente-renan@gmail.com');
        $user2->setRoles(['ROLE_GERENTE']);
        $user2->setPassword(
            $this->hasher->hashPassword($user2, '123456')
        );
        $user2->setStatus(true);
        $user2->setNome('Renan Dev');
        $manager->persist($user2);

        $user3 = new User(); 
        $user3->setEmail('cliente@gmail.com');
        $user3->setRoles(['ROLE_CLIENTE']);
        $user3->setPassword(
            $this->hasher->hashPassword($user3, '123456')
        );
        $user3->setStatus(true);
        $user3->setNome('Ayla Dev');
        $manager->persist($user3);

        $user4 = new User(); 
        $user4->setEmail('gerente-roberto@gmail.com');
        $user4->setRoles(['ROLE_GERENTE']);
        $user4->setPassword(
            $this->hasher->hashPassword($user4, '123456')
        );
        $user4->setStatus(true);
        $user4->setNome('Roberto Dev');
        $manager->persist($user4);

        $user5 = new User(); 
        $user5->setEmail('gerente-maria@gmail.com');
        $user5->setRoles(['ROLE_GERENTE']);
        $user5->setPassword(
        $this->hasher->hashPassword($user5, '123456')
        );
        $user5->setStatus(true);
        $user5->setNome('Maria Dev');
        $manager->persist($user5);

        $user6 = new User(); 
        $user6->setEmail('gerente-rick@gmail.com');
        $user6->setRoles(['ROLE_GERENTE']);
        $user6->setPassword(
        $this->hasher->hashPassword($user5, '123456')
        );
        $user6->setStatus(true);
        $user6->setNome('Rick Dev');
        $manager->persist($user6);

        $tipoConta1 = new TipoConta();
        $tipoConta1->setTipo('CC');
        $manager->persist($tipoConta1);

        $tipoConta2 = new TipoConta();
        $tipoConta2->setTipo('CP');
        $manager->persist($tipoConta2);

        $gerente1 = new Gerente();
        $gerente1->setUser($user4);
        $manager->persist($gerente1);

        $gerente2 = new Gerente();
        $gerente2->setUser($user5);
        $manager->persist($gerente2);

        $gerente3 = new Gerente();
        $gerente3->setUser($user6);
        $manager->persist($gerente3);

        $agencia1 = new Agencia();
        $agencia1->setNomeAgencia('Gus1');
        $agencia1->setGerente($gerente1);
        $agencia1->setcodigo('012');
        $agencia1->setEndereco('Rua Principal,Garanhuns-PE');
        $manager->persist($agencia1);

        $agencia2 = new Agencia();
        $agencia2->setNomeAgencia('Gus2');
        $agencia2->setGerente($gerente2);
        $agencia2->setCodigo('022');
        $agencia2->setEndereco('Rua Imperador Dom Pedro Primeiro,Garanhuns-PE');
        $manager->persist($agencia2);
            
        $agencia3 = new Agencia();
        $agencia3->setNomeAgencia('Gus3');
        $agencia3->setGerente($gerente3);
        $agencia3->setCodigo('023');
        $agencia3->setEndereco('Rua Imperador Dom Pedro Primeiro,Mamanguape-PB');
        $manager->persist($agencia3);
                
                  
    
            $manager->flush();
        }
}
