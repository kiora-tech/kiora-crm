<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RelationsList
{
    /**
     * Liste des relations à afficher
     */
    public array $relations = [];
    
    /**
     * Message à afficher quand il n'y a pas de relations
     */
    public string $emptyMessage = 'No relations';
    
    /**
     * URL vers laquelle rediriger pour ajouter une relation
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
     * Entité associée aux relations (personne)
     */
    public ?object $entity = null;
}