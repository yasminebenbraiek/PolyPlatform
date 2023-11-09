<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleP = null;

    #[ORM\Column(length: 500)]
    private ?string $conditions = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(type:"string",length: 255)]
    private $image;

    #[ORM\ManyToOne]
    private ?AnneeUniversitaire $AnneeUniversitaire = null;

    /**
    * @return mixed
    */
    public function getImage()
    {
    return $this->image;
    }
    /**
    * @param mixed $image
    */
    public function setImage($image): void
    {
    $this->image = $image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleP(): ?string
    {
        return $this->libelleP;
    }

    public function setLibelleP(string $libelleP): self
    {
        $this->libelleP = $libelleP;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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
