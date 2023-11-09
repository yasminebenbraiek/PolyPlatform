<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\EtudiantRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
#[UniqueEntity(fields: ['User'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['matricule'], message: 'There is already an account with this identifier')]
#[UniqueEntity(fields: ['CIN'], message: 'There is already an account with this CIN')]
#[UniqueEntity(fields: ['passport'], message: 'There is already an account with this passport')]

class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateN = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuN = null;

    #[ORM\Column(nullable: true)]
    private ?int $CIN = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passport = null;

    #[ORM\Column]
    private ?int $tel = null;

    #[ORM\Column]
    private ?int $anneeBac = null;

    #[ORM\Column(length: 255)]
    private ?string $sectionBac = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 3)]
    private ?string $moyenneBac = null;

    #[ORM\Column(type:"string",length: 255, nullable: true)]
    private $image;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $User = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Groupe $Groupe = null;

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

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateN(): ?\DateTimeInterface
    {
        return $this->dateN;
    }

    public function setDateN(\DateTimeInterface $dateN): self
    {
        $this->dateN = $dateN;

        return $this;
    }

    public function getLieuN(): ?string
    {
        return $this->lieuN;
    }

    public function setLieuN(string $lieuN): self
    {
        $this->lieuN = $lieuN;

        return $this;
    }

    public function getCIN(): ?int
    {
        return $this->CIN;
    }

    public function setCIN(?int $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getPassport(): ?string
    {
        return $this->passport;
    }

    public function setPassport(?string $passport): self
    {
        $this->passport = $passport;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAnneeBac(): ?int
    {
        return $this->anneeBac;
    }

    public function setAnneeBac(int $anneeBac): self
    {
        $this->anneeBac = $anneeBac;

        return $this;
    }

    public function getSectionBac(): ?string
    {
        return $this->sectionBac;
    }

    public function setSectionBac(string $sectionBac): self
    {
        $this->sectionBac = $sectionBac;

        return $this;
    }

    public function getMoyenneBac(): ?string
    {
        return $this->moyenneBac;
    }

    public function setMoyenneBac(string $moyenneBac): self
    {
        $this->moyenneBac = $moyenneBac;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->Groupe;
    }

    public function setGroupe(?Groupe $Groupe): self
    {
        $this->Groupe = $Groupe;

        return $this;
    }
}
