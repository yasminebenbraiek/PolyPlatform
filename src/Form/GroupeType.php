<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Niveau;
use App\Entity\AnneeUniversitaire;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelleG')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Jour' => true,
                    'Soir' => false,
                ],
            ])
            ->add('Niveau',EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'libelleN',
            ])
            ->add('AnneeUniversitaire',EntityType::class, [
                'class' => AnneeUniversitaire::class,
                'choice_label' => 'libelleAU',
            ])
            ->add('Valider', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
