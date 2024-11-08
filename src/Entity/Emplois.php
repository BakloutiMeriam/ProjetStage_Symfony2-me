<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EmploisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Employe;
use App\Entity\Jour; 

#[ORM\Entity(repositoryClass: EmploisRepository::class)]
#[ApiResource]
#[Broadcast]
class Emplois
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idemplois')]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tdebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tfin = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)] 
    #[ORM\JoinColumn(name: 'idemploye', referencedColumnName: 'id')] 
    private ?Employe $employe = null;

    #[ORM\ManyToOne(targetEntity: Jour::class)] 
    #[ORM\JoinColumn(name: 'idjour', referencedColumnName: 'id')] 
    private ?Jour $jour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTdebut(): ?\DateTimeInterface
    {
        return $this->tdebut;
    }

    public function setTdebut(\DateTimeInterface $tdebut): static
    {
        $this->tdebut = $tdebut;

        return $this;
    }

    public function getTfin(): ?\DateTimeInterface
    {
        return $this->tfin;
    }

    public function setTfin(\DateTimeInterface $tfin): static
    {
        $this->tfin = $tfin;

        return $this;
    }
    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;
        return $this;
    }
    public function getJour(): ?Employe
    {
        return $this->jour;
    }

    public function setJour(?Jour $jour): static
    {
        $this->jour = $jour;
        return $this;
    }
}
