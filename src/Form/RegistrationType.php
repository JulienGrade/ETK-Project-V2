<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,
                $this->getConfiguration("Prénom", "Votre prénom..."))
            ->add('lastName', TextType::class,
                $this->getConfiguration("Nom", "Votre nom de famille..."))
            ->add('email', EmailType::class,
                $this->getConfiguration("Email", "Votre adresse email..."))
            ->add('hash', PasswordType::class,
                $this->getConfiguration("Mot de passe", "Choisissez votre mot de passe..."))
            ->add('passwordConfirm', PasswordType::class,
                $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('city', TextType::class,
                $this->getConfiguration("Ville", "Votre ville..."))
            ->add('phone', TelType::class,
                $this->getConfiguration("Téléphone", "Votre numéro de téléphone"))
            ->add('cgu', CheckboxType::class,
                $this->getConfiguration("Acceptez les conditions générales de ventes","Cochez"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
