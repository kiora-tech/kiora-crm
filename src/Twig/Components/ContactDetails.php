<?php

namespace App\Twig\Components;

use App\Entity\PhysicalPerson;
use App\Entity\LegalPerson;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ContactDetails
{
    /**
     * La personne (physique ou morale) à afficher
     */
    public object $person;
    
    /**
     * Titre de la carte
     */
    public string $title = 'Contact Details';
    
    /**
     * Déterminer le type de personne
     */
    public function getPersonType(): string
    {
        if ($this->person instanceof PhysicalPerson) {
            return 'physical';
        } elseif ($this->person instanceof LegalPerson) {
            return 'legal';
        }
        return 'unknown';
    }
}