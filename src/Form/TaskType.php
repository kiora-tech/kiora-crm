<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'task.title',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'task.description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('dueDate', DateTimeType::class, [
                'label' => 'task.due_date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('status', EnumType::class, [
                'label' => 'task.status',
                'class' => TaskStatus::class,
                'choice_label' => function (TaskStatus $status) {
                    return 'task.status.' . strtolower($status->value);
                },
                'attr' => ['class' => 'form-select']
            ])
            ->add('priority', EnumType::class, [
                'label' => 'task.priority',
                'class' => TaskPriority::class,
                'choice_label' => function (TaskPriority $priority) {
                    return 'task.priority.' . strtolower($priority->value);
                },
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'title',
                'label' => 'task.project',
                'placeholder' => 'task.select_project',
                'attr' => ['class' => 'form-select']
            ])
            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
                'label' => 'task.assignee',
                'placeholder' => 'task.select_assignee',
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'task.start_date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('estimatedHours', IntegerType::class, [
                'label' => 'task.estimated_hours',
                'required' => false,
                'attr' => ['class' => 'form-control', 'min' => 0]
            ])
            ->add('actualHours', IntegerType::class, [
                'label' => 'task.actual_hours',
                'required' => false,
                'attr' => ['class' => 'form-control', 'min' => 0]
            ])
            ->add('tags', ChoiceType::class, [
                'label' => 'task.tags',
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'task.tag.design' => 'design',
                    'task.tag.development' => 'development',
                    'task.tag.testing' => 'testing',
                    'task.tag.documentation' => 'documentation',
                    'task.tag.bug' => 'bug',
                    'task.tag.feature' => 'feature',
                    'task.tag.enhancement' => 'enhancement',
                ],
                'attr' => ['class' => 'form-select', 'data-controller' => 'select2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'translation_domain' => 'messages'
        ]);
    }
}