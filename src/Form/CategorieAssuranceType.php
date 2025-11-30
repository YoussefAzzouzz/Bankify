<?php

namespace App\Form;

use App\Entity\CategorieAssurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategorieAssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCategorie', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('typeCouverture', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('agenceResponsable', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieAssurance::class,
        ]);
    }
}
