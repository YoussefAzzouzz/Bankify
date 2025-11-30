<?php

namespace App\Form;

use App\Entity\Credit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantTotale')
            ->add('interet', ChoiceType::class, [
                'choices' => [
                    '10% sur 36 mois' => 10,
                    '15% sur 48 mois' => 15,
                    '20% sur 60 mois' => 20,
                ],
                'expanded' => true,
                'attr' => ['class' => 'vertical-radio'],
                ])
            ->add('categorie')
            ->add('save',SubmitType::class,['label' => 'Enregistrer la demande'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}
