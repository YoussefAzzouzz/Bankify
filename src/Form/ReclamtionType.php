<?php

namespace App\Form;

use App\Entity\Reclamtion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReclamtionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('categorie', ChoiceType::class, [
            'choices' => [
                'Payment Discrepancy' => 'Payment Discrepancy',
                'Non-Receipt' => 'Non-Receipt',
                'Delayed Processing' => 'Delayed Processing',
                'Forgery' => 'Forgery',
            ],
            'placeholder' => 'Select a User',
        ])
            ->add('statutR', ChoiceType::class, [
                'choices' => [
                    'Traitée' => 'Traitée',
                    'En cours' => 'En cours',
                    // Add more statuses as needed
                ],
                'placeholder' => 'Select a User',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamtion::class,
        ]);
    }
}
