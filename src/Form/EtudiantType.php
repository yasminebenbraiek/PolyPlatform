<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Unique;
use App\Entity\User;
use App\Entity\Groupe;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('nom')
            ->add('prenom')
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Femme' => true,
                    'Homme' => false,
                ],
            ])
            ->add('dateN', DateType::class, [
                'widget' => 'choice',
                'years' => range(1980,2020)
            ])
            ->add('lieuN')
            ->add('CIN', TextType::class)
            ->add('passport')
            ->add('tel', TelType::class)
            ->add('anneeBac', NumberType::class)
            ->add('sectionBac')
            ->add('moyenneBac')
            ->add('User',EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                ])
            ->add('Groupe',EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'libelleG',
            ])
            ->add('image', FileType::class, array('data_class' => null))
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
