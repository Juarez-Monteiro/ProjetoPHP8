<?php

namespace App\Form;

use App\Entity\Conta;
use App\Entity\User;
use App\Entity\Agencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('saldo')
           // ->add('numeroDaConta')
            // ->add('status')
            ->add('agencia')
            // ->add('user')
            ->add('tipos')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conta::class,
        ]);
    }
}
