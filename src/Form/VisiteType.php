<?php

namespace App\Form;

use App\Entity\Visite;
use App\Enum\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'label' => 'Date et heure',
                'widget' => 'single_text'
            ])
            ->add('commentaire', TextType::class, ['label' => 'Commentaire'])
            ->add('compteRendu', TextType::class, [
                'label' => 'Compte-rendu',
                'required' => false
            ])
            ->add('statut', EnumType::class, [
                'class' => Statut::class,
                'choice_label' => fn($choice) => ucfirst($choice->value),
                'label' => 'Statut'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
