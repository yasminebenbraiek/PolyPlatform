<?php

namespace App\Entity;

use App\Repository\AnneeUniversitaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeUniversitaireRepository::class)]
class AnneeUniversitaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleAU = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleAU(): ?string
    {
        return $this->libelleAU;
    }

    public function setLibelleAU(string $libelleAU): self
    {
        $this->libelleAU = $libelleAU;

        return $this;
    }
}
