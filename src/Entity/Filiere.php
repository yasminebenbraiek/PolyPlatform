<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiliereRepository::class)]
class Filiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleF = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleF(): ?string
    {
        return $this->libelleF;
    }

    public function setLibelleF(string $libelleF): self
    {
        $this->libelleF = $libelleF;

        return $this;
    }
}
