<?php

namespace App\Form;

use App\Entity\Virement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VirementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compte_source')
            ->add('compte_destination')
            ->add('montant')
            ->add('date')
            ->add('heure')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Virement::class,
        ]);
    }
}
