<?php

namespace App\Form;

use App\Entity\Conta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Conta2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('saldo')
            ->add('numeroDaConta')
            ->add('status')
            ->add('agencia')
            ->add('user')
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
