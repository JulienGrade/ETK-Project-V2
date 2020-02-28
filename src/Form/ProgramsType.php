<?php

namespace App\Form;

use App\Entity\Programs;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramsType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                $this->getConfiguration('Titre du programme', 'titre'))
            ->add('image',FileType::class, [
                'mapped' => false
                ])
//                $this->getConfiguration('Image du programme', 'upload de l\'image'))
            ->add('secondTitle',TextType::class,
                $this->getConfiguration('Sous-titre du programme', 'Sous-titre'))
            ->add('description',CKEditorType::class,
                $this->getConfiguration('Description du programme', 'Description'))
            ->add('intro', CKEditorType::class,
                $this->getConfiguration('Résumé du programme', ' '))
            ->add('limitedAge', TextType::class,
                $this->getConfiguration('Âge limite du programme', 'Âge du programme'))
            ->add('illustration', FileType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Programs::class,
        ]);
    }
}
