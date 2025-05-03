<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Relation;
use App\Enum\RelationType as RelationTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class RelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'relation.type',
                'class' => RelationTypeEnum::class,
                'choice_translation_domain' => true,
                'choice_label' => function(RelationTypeEnum $type) {
                    return 'relation_type.' . strtolower($type->value);
                }
            ]);
            
        // Si la source est définie, on l'utilise, sinon on ajoute un champ
        if ($options['source_person']) {
            $builder->add('targetPerson', EntityType::class, [
                'label' => 'relation.target_person',
                'class' => Person::class,
                'choice_label' => function (Person $person) {
                    if ($person instanceof \App\Entity\LegalPerson) {
                        return $person->getName();
                    } else {
                        return $person->getFirstName().' '.$person->getLastName();
                    }
                },
                'required' => true,
                'placeholder' => 'relation.select_target_person',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.id != :id')
                        ->setParameter('id', $options['source_person']->getId());
                },
            ]);
        } else {
            $builder
                ->add('sourcePerson', EntityType::class, [
                    'label' => 'relation.source_person',
                    'class' => Person::class,
                    'choice_label' => function (Person $person) {
                        if ($person instanceof \App\Entity\LegalPerson) {
                            return $person->getName();
                        } else {
                            return $person->getFirstName().' '.$person->getLastName();
                        }
                    },
                    'required' => true,
                    'placeholder' => 'relation.select_source_person',
                ])
                ->add('targetPerson', EntityType::class, [
                    'label' => 'relation.target_person',
                    'class' => Person::class,
                    'choice_label' => function (Person $person) {
                        if ($person instanceof \App\Entity\LegalPerson) {
                            return $person->getName();
                        } else {
                            return $person->getFirstName().' '.$person->getLastName();
                        }
                    },
                    'required' => true,
                    'placeholder' => 'relation.select_target_person',
                ]);
        }
        
        $builder->add('notes', TextareaType::class, [
            'label' => 'relation.notes',
            'required' => false,
            'attr' => [
                'placeholder' => 'relation.notes.placeholder',
                'rows' => 3
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relation::class,
            'source_person' => null,
            'translation_domain' => 'messages',
        ]);
        
        $resolver->setAllowedTypes('source_person', ['null', Person::class]);
    }
}