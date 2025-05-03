<?php

namespace App\Twig\Components;

use App\Entity\Person;
use App\Entity\PhysicalPerson;
use App\Entity\LegalPerson;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class PersonHeader
{
    /**
     * La personne (physique ou morale) à afficher
     */
    public Person $person;
    
    /**
     * Liste des relations de la personne
     */
    public iterable $relations = [];
    
    /**
     * URL pour définir la relation
     */
    public ?string $setRelationUrl = null;
    
    /**
     * Permet de déterminer si la personne est un client ou autre
     */
    public function isClient(): bool
    {
        foreach ($this->relations as $relation) {
            if ($relation->getType()->value === 'CLIENT' && $relation->getTargetPerson()->getId() === $this->person->getId()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Permet de déterminer si la personne est un prospect
     */
    public function isProspect(): bool
    {
        foreach ($this->relations as $relation) {
            if ($relation->getType()->value === 'PROSPECT' && $relation->getTargetPerson()->getId() === $this->person->getId()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Permet de déterminer si la personne est un contact
     */
    public function isContact(): bool
    {
        foreach ($this->relations as $relation) {
            if ($relation->getType()->value === 'CONTACT' && $relation->getTargetPerson()->getId() === $this->person->getId()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Permet de déterminer si la personne est un fournisseur
     */
    public function isSupplier(): bool
    {
        foreach ($this->relations as $relation) {
            if ($relation->getType()->value === 'SUPPLIER' && $relation->getTargetPerson()->getId() === $this->person->getId()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Permet de déterminer si la personne est un partenaire
     */
    public function isPartner(): bool
    {
        foreach ($this->relations as $relation) {
            if ($relation->getType()->value === 'PARTNER' && $relation->getTargetPerson()->getId() === $this->person->getId()) {
                return true;
            }
        }
        return false;
    }
    
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