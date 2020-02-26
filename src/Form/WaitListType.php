<?php

namespace App\Form;

use App\Entity\WaitList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WaitListType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isWaiting', RadioType::class,
                $this->getConfiguration("Se placer en liste d'attente", "Cochez si vous souhaitez vous placer en liste d'attente..."))
            ->add('number', IntegerType::class,
                $this->getConfiguration("Nombre de places", "Choisissez le nombre de place que vous souhaitez..."))
            ->add('comment', TextareaType::class,
                $this->getConfiguration("Votre commentaire", "Vous avez la possibilité de laisser un commentaire...",['required'=>0]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WaitList::class,
        ]);
    }
}
