<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class InteractionsList
{
    /**
     * Liste des interactions à afficher
     */
    public iterable $interactions = [];
    
    /**
     * Message à afficher quand il n'y a pas d'interactions
     */
    public string $emptyMessage = 'No interactions';
    
    /**
     * URL vers laquelle rediriger pour ajouter une interaction
     */
    public ?string $addUrl = null;
    
    /**
     * Libellé du bouton d'ajout
     */
    public ?string $addLabel = null;
    
    /**
     * Titre de la section
     */
    public ?string $title = null;
    
    /**
     * Entité associée aux interactions (personne, projet, tâche)
     */
    public ?object $entity = null;
    
    /**
     * Type d'entité (person, project, task)
     */
    public string $entityType = 'entity';
}