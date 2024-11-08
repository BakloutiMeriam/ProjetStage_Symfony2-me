<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EmpreinteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Employe;
#[ORM\Entity(repositoryClass: EmpreinteRepository::class)]
#[ApiResource]
#[Broadcast]
class Empreinte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'codeemp')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tentree = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tsortie = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)] 
    #[ORM\JoinColumn(name: 'idemploye', referencedColumnName: 'id')] 
    private ?Employe $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
{
    $this->id = $id;

    return $this;
}
    

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTentree(): ?\DateTimeInterface
    {
        return $this->tentree;
    }

    public function setTentree(\DateTimeInterface $tentree): static
    {
        $this->tentree = $tentree;

        return $this;
    }

    public function getTsortie(): ?\DateTimeInterface
    {
        return $this->tsortie;
    }

    public function setTsortie(\DateTimeInterface $tsortie): static
    {
        $this->tsortie = $tsortie;

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
}
