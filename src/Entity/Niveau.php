<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleN = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $Filiere = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleN(): ?string
    {
        return $this->libelleN;
    }

    public function setLibelleN(string $libelleN): self
    {
        $this->libelleN = $libelleN;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->Filiere;
    }

    public function setFiliere(?Filiere $Filiere): self
    {
        $this->Filiere = $Filiere;

        return $this;
    }

}
