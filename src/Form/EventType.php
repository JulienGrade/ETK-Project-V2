<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,
                $this->getConfiguration("Titre du programme", "Saisissez le titre du programme"))
            ->add('category', ChoiceType::class, [
                'choices'=> [
                    'Enfants' => 'enfants',
                    'Parents/enfants' => 'parents/enfants'
                ]
            ])
            ->add('startDate', DateType::class,
                $this->getConfiguration("Date du début et heure", "Saisissez la date et l'heure du début de l'événement"))
            ->add('endDate', DateType::class,
                $this->getConfiguration("Date de fin et heure", "Saisissez la date et l'heire de fin de l'événement"))
            ->add('seats', IntegerType::class,
                $this->getConfiguration("Nombre de places", "Saisissez le nombre de place"))
            ->add('picture', FileType::class,
                $this->getConfiguration("Image", "Joignez un fichier image"))
            ->add('description', TextareaType::class,
                $this->getConfiguration("Description", "Saisissez la descritpion de l'événement"))
            ->add('ageMin', IntegerType::class,
                $this->getConfiguration("Age minimum", "Saisissez l'age minimum"))
            ->add('ageMax', IntegerType::class,
                $this->getConfiguration("Age maximum", "Saisissez l'age maximum"))
            ->add('location', TextType::class,
                $this->getConfiguration("Lieu de l'événement", "Saisissez le lieu de l'événement"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
