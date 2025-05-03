<?php

namespace App\Form;

use App\Entity\LegalPerson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'legal_person.name',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('legalName', TextType::class, [
                'label' => 'legal_person.legal_name',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('tradeName', TextType::class, [
                'label' => 'legal_person.trade_name',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'legal_person.email',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phone', TextType::class, [
                'label' => 'legal_person.phone',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'legal_person.address',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('siret', TextType::class, [
                'label' => 'legal_person.siret',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('siren', TextType::class, [
                'label' => 'legal_person.siren',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('vatNumber', TextType::class, [
                'label' => 'legal_person.vat_number',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('website', UrlType::class, [
                'label' => 'legal_person.website',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('industry', TextType::class, [
                'label' => 'legal_person.industry',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('countryCode', CountryType::class, [
                'label' => 'legal_person.country',
                'required' => false,
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'legal_person.select_country'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LegalPerson::class,
            'translation_domain' => 'messages'
        ]);
    }
}