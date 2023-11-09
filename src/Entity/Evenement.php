<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleE = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateE = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuE = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

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

    public function getLibelleE(): ?string
    {
        return $this->libelleE;
    }

    public function setLibelleE(string $libelleE): self
    {
        $this->libelleE = $libelleE;

        return $this;
    }

    public function getDateE(): ?\DateTimeInterface
    {
        return $this->dateE;
    }

    public function setDateE(\DateTimeInterface $dateE): self
    {
        $this->dateE = $dateE;

        return $this;
    }

    public function getLieuE(): ?string
    {
        return $this->lieuE;
    }

    public function setLieuE(string $lieuE): self
    {
        $this->lieuE = $lieuE;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
