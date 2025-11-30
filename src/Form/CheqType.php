<?php

namespace App\Form;

use App\Entity\Cheque;
use App\Entity\Compte;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CheqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantC')
            ->add('destinationC', ChoiceType::class, [
                'label' => 'Destination',
                'choices' => $this->getUserChoices(),
                'placeholder' => 'Select a User',
            ])
           
           
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cheque::class,
        ]);
    }
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
   

    private function getUserChoices(): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $choices = [];
        foreach ($users as $user) {
            $choices[ $user->getFullName()] = $user;
        }

        return $choices;
    }
}
    