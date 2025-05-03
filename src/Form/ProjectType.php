<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\ProjectStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'project.title',
                'attr' => ['placeholder' => 'project.title.placeholder'],
            ])
            ->add('reference', TextType::class, [
                'label' => 'project.reference',
                'required' => false,
                'attr' => ['placeholder' => 'project.reference.placeholder'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'project.description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'project.description.placeholder',
                    'rows' => 5
                ],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'project.start_date',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'project.end_date',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('status', EnumType::class, [
                'label' => 'project.status',
                'class' => ProjectStatus::class,
                'choice_translation_domain' => true,
                'choice_label' => function(ProjectStatus $status) {
                    return 'project.status.' . strtolower($status->value);
                }
            ])
            ->add('client', EntityType::class, [
                'label' => 'project.client',
                'class' => Person::class,
                'choice_label' => function ($person) {
                    return $person->getName();
                },
                'placeholder' => 'project.select_client',
            ])
            ->add('manager', EntityType::class, [
                'label' => 'project.manager',
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
                'placeholder' => 'project.select_manager',
            ])
            ->add('budget', MoneyType::class, [
                'label' => 'project.budget',
                'required' => false,
                'currency' => 'EUR',
                'attr' => ['placeholder' => 'project.budget.placeholder'],
            ])
            ->add('tags', ChoiceType::class, [
                'label' => 'project.tags',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'project.tag.web' => 'web',
                    'project.tag.mobile' => 'mobile',
                    'project.tag.design' => 'design',
                    'project.tag.development' => 'development',
                    'project.tag.marketing' => 'marketing',
                    'project.tag.consulting' => 'consulting',
                    'project.tag.integration' => 'integration',
                    'project.tag.training' => 'training',
                    'project.tag.support' => 'support',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'translation_domain' => 'messages'
        ]);
    }
}