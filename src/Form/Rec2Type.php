<?php

namespace App\Form;

use App\Entity\Reclamtion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class Rec2Type extends AbstractType
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
     
        ])
            ->add('statutR', ChoiceType::class, [
                'choices' => [
                    'Traitée' => 'Traitée',
                    'En cours' => 'En cours',
                    // Add more statuses as needed
                ],
             
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamtion::class,
        ]);
    }
}
