<?php

namespace App\Form;

use App\Entity\Carte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\CompteClient;
use App\Entity\TypeC;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Num_C')
            ->add('Date_Exp')
            ->add('Type_C')
            ->add('Statut_C')
            ->add('account', EntityType::class, [
                'class' => CompteClient::class,
                'choice_label' => 'id',
            ])
            ->add('type', EntityType::class, [
                'class' => TypeC::class,
                'choice_label' => 'type',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}
