<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
#[Broadcast]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id")]
    private ?int $id = null;
    
    #[Assert\NotBlank(message: "Le champ 'nom' ne peut pas être vide.")]
    #[ORM\Column(length: 255,name:"nom")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,name:"prenom")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,name:"mail")]
    private ?string $mail = null;

    #[ORM\Column(length: 15,name:"tel")]
    private ?string $tel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }
}
