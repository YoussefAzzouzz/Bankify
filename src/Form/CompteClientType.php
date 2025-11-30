<?php


namespace App\Form;

use App\Entity\Type;
use App\Entity\CompteClient;
use App\Entity\Pack; // Import the Pack entity
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('rib')
            ->add('mail')
            ->add('tel')
            ->add('solde')
            ->add('nom_type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'nomType',
                'label' => 'Type'
            ])
            ->add('nom_pack', EntityType::class, [
                'class' => Pack::class,
                'choice_label' => 'nom_pack',
                'label' => 'Pack'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteClient::class,
        ]);
    }
}
