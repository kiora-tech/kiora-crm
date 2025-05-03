<?php

namespace App\Form;

use App\Entity\Interaction;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\InteractionType as InteractionTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InteractionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'interaction.type',
                'class' => InteractionTypeEnum::class,
                'choice_translation_domain' => true,
                'choice_label' => function(InteractionTypeEnum $type) {
                    return 'interaction.' . $type->value;
                }
            ])
            ->add('dateTime', DateTimeType::class, [
                'label' => 'interaction.date_time',
                'widget' => 'single_text',
            ])
            ->add('subject', TextType::class, [
                'label' => 'interaction.subject',
                'attr' => ['placeholder' => 'interaction.subject.placeholder'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'interaction.content',
                'attr' => [
                    'placeholder' => 'interaction.content.placeholder',
                    'rows' => 5,
                ],
            ])
            ->add('isOutgoing', CheckboxType::class, [
                'label' => 'interaction.is_outgoing',
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'interaction.location',
                'required' => false,
                'attr' => ['placeholder' => 'interaction.location.placeholder'],
            ])
            ->add('endDateTime', DateTimeType::class, [
                'label' => 'interaction.end_date_time',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('outcome', TextType::class, [
                'label' => 'interaction.outcome',
                'required' => false,
                'attr' => ['placeholder' => 'interaction.outcome.placeholder'],
            ]);
            
        // Ajout conditionnel des champs liés aux entités
        if (!$options['hide_contact']) {
            $builder->add('contact', EntityType::class, [
                'label' => 'interaction.contact',
                'class' => Person::class,
                'choice_label' => function (Person $person) {
                    return $person->getName();
                },
                'required' => false,
                'placeholder' => 'interaction.select_contact',
            ]);
        }
        
        if (!$options['hide_project']) {
            $builder->add('project', EntityType::class, [
                'label' => 'interaction.project',
                'class' => Project::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'interaction.select_project',
            ]);
        }
        
        if (!$options['hide_task']) {
            $builder->add('task', EntityType::class, [
                'label' => 'interaction.task',
                'class' => Task::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'interaction.select_task',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interaction::class,
            'hide_contact' => false,
            'hide_project' => false,
            'hide_task' => false,
            'translation_domain' => 'messages',
        ]);
        
        $resolver->setAllowedTypes('hide_contact', 'bool');
        $resolver->setAllowedTypes('hide_project', 'bool');
        $resolver->setAllowedTypes('hide_task', 'bool');
    }
}