<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\LegalPerson;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'label' => 'user.name.label',
                'attr' => ['placeholder' => 'user.name.placeholder'],
            ])
            ->add('lastName', null, [
                'label' => 'user.lastName.label',
                'attr' => ['placeholder' => 'user.lastName.placeholder'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email.label',
                'attr' => ['placeholder' => 'user.email.placeholder'],
            ])
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER'],
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'user.roles.label',
                ]
            )
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'user.password.placeholder',
                ],
            ])
            ->add('company', EntityType::class, [
                'class' => LegalPerson::class,
                'choice_label' => 'name',
                'label' => 'user.company.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
