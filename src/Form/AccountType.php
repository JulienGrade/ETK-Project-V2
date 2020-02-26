<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,
                $this->getConfiguration('Prénom', 'Prénom'))
            ->add('lastName', TextType::class,
                $this->getConfiguration('Nom', 'Nom'))
            ->add('email', TextType::class,
                $this->getConfiguration('Email', 'Email'))
            ->add('city', TextType::class,
                $this->getConfiguration('Ville', 'Ville'))
            ->add('phone', TextType::class,
                $this->getConfiguration('Téléphone', 'Téléphone'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
