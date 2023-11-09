<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleC = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $centre = null;

    #[ORM\Column(type:"string",length: 255)]
    private $image;

    #[ORM\ManyToOne]
    private ?Pack $Pack = null;

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

    public function getLibelleC(): ?string
    {
        return $this->libelleC;
    }

    public function setLibelleC(string $libelleC): self
    {
        $this->libelleC = $libelleC;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getCentre(): ?string
    {
        return $this->centre;
    }

    public function setCentre(string $centre): self
    {
        $this->centre = $centre;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->Pack;
    }

    public function setPack(?Pack $Pack): self
    {
        $this->Pack = $Pack;

        return $this;
    }

}
