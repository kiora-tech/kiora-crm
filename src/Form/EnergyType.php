<?php

namespace App\Form;

use App\Entity\Energy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EnergyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')    // ELEC, GAS, etc.
            ->add('code')    // Code de l'énergie ou du contrat
            ->add('provider')  // Fournisseur d'énergie
            ->add('contractEnd')  // Date de fin de contrat
            ->add('power')
            ->add('basePrice')
            ->add('peakHour')
            ->add('offPeakHour')
            ->add('peakHourSummer')
            ->add('offPeakHourWinter')
            ->add('offPeakHourSummer')
            ->add('peakHourWinter')
            ->add('horoSeason')
            ->add('segment', ChoiceType::class, [
                'choices' => [
                    'C1' => 'C1',
                    'C2' => 'C2',
                    'C3' => 'C3',
                    'C4' => 'C4',
                    'C5' => 'C5',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Energy::class,
        ]);
    }
}