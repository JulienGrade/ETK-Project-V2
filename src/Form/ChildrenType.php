<?php

namespace App\Form;

use App\Entity\Children;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildrenType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sexe', ChoiceType::class, [
                'choices'=> [
                    'Garçon' => 'garçon',
                    'Fille'  => 'fille'
                ]
            ])
            ->add('age', IntegerType::class,
                $this->getConfiguration("Age", "Age de l'enfant", [
                    'attr' => [
                        'min' => 4,
                        'max' => 18
                    ]
                ]))
            ->add('firstName', TextType::class,
                $this->getConfiguration("Prénom", "Prénom de l'enfant"))
            ->add('lastName', TextType::class,
                $this->getConfiguration("Nom", "Nom de l'enfant"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Children::class,
        ]);
    }
}
