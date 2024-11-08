<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: DetailsRepository::class)]
#[ApiResource]
#[Broadcast]
class Details
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $nbrhtotal = null;

    #[ORM\Column]
    private ?int $nbrjabs = null;

    #[ORM\Column]
    private ?int $nbrhsupp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrhtotal(): ?float
    {
        return $this->nbrhtotal;
    }

    public function setNbrhtotal(float $nbrhtotal): static
    {
        $this->nbrhtotal = $nbrhtotal;

        return $this;
    }

    public function getNbrjabs(): ?int
    {
        return $this->nbrjabs;
    }

    public function setNbrjabs(int $nbrjabs): static
    {
        $this->nbrjabs = $nbrjabs;

        return $this;
    }

    public function getNbrhsupp(): ?int
    {
        return $this->nbrhsupp;
    }

    public function setNbrhsupp(int $nbrhsupp): static
    {
        $this->nbrhsupp = $nbrhsupp;

        return $this;
    }
}
