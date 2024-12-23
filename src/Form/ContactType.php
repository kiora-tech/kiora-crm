<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('position')
            ->add('email')
            ->add('phone')
            ->add('mobilePhone')
            ->add('address');  // Ajoute le formulaire individuel pour chaque contact
        if ($options['with_customer'] === true) {
            $builder->add('customer', EntityType::class, [
                'class' => Customer::class
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'with_customer' => true
        ]);

        $resolver->setAllowedValues('with_customer', [true, false]);
    }
}