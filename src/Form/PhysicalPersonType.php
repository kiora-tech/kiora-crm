<?php

namespace App\Form;

use App\Entity\LegalPerson;
use App\Entity\PhysicalPerson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhysicalPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'physical_person.first_name',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'physical_person.last_name',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('title', TextType::class, [
                'label' => 'physical_person.title',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'physical_person.title_placeholder']
            ])
            ->add('position', TextType::class, [
                'label' => 'physical_person.position',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('department', TextType::class, [
                'label' => 'physical_person.department',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'physical_person.email',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phone', TextType::class, [
                'label' => 'physical_person.phone',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('mobile', TextType::class, [
                'label' => 'physical_person.mobile',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'physical_person.address',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'physical_person.birth_date',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('isPrimaryContact', CheckboxType::class, [
                'label' => 'physical_person.is_primary_contact',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('isManager', CheckboxType::class, [
                'label' => 'physical_person.is_manager',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhysicalPerson::class,
            'translation_domain' => 'messages'
        ]);
    }
}