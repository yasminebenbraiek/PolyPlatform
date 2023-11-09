<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleG = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveau $Niveau = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeUniversitaire $AnneeUniversitaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleG(): ?string
    {
        return $this->libelleG;
    }

    public function setLibelleG(string $libelleG): self
    {
        $this->libelleG = $libelleG;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getNiveau(): ?Niveau
    {
        return $this->Niveau;
    }

    public function setNiveau(?Niveau $Niveau): self
    {
        $this->Niveau = $Niveau;

        return $this;
    }

    public function getAnneeUniversitaire(): ?AnneeUniversitaire
    {
        return $this->AnneeUniversitaire;
    }

    public function setAnneeUniversitaire(?AnneeUniversitaire $AnneeUniversitaire): self
    {
        $this->AnneeUniversitaire = $AnneeUniversitaire;

        return $this;
    }
}
