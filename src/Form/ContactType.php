<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Mr' => 'Mr',
                    'Mme' => 'Mme',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('firstName', TextType::class,
                $this->getConfiguration('Prénom', 'Votre prénom...'))
            ->add('lastName', TextType::class,
                $this->getConfiguration('Nom', 'Votre nom...'))
            ->add('comeFrom', ChoiceType::class, [
                'choices' => [
                    'une Ecole' => 'Ecole',
                    'un Particulier' => 'Particulier',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('town', ChoiceType::class, [
                'choices' => [
                    'Lille' => 'Lille',
                    'Lomme' => 'Lomme',
                    'Hellemmes' => 'Hellemmes',
                    'Autre' => 'Autre',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('phone', TextType::class,
                $this->getConfiguration('Téléphone', 'Votre téléphone...'))
            ->add('email', TextType::class,
                $this->getConfiguration('Email', 'mail@exemple.com'))
            ->add('message', TextareaType::class,
                $this->getConfiguration('Message', 'Que voulez-vous nous dire ?'))
            ->add('yourTown', TextType::class,
                $this->getConfiguration('Votre ville', 'Précisez votre ville...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }



}