<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_agence', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('email_agence', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('tel_agence', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 8,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}
