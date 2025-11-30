<?php

namespace App\Form;

use App\Entity\Assurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeAssurance', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('montantPrime', null, [
                'constraints' => [
                    new Assert\Positive(),
                ],
            ])
            ->add('nomAssure', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('nomBeneficiaire', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('infoAssurance', null, [
                'constraints' => [
                    new Assert\Length(['min' => 3]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
