<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?Livre $liv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_emprunt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_retour = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_retour_max = null;

    #[ORM\Column]
    private ?bool $retour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLiv(): ?Livre
    {
        return $this->liv;
    }

    public function setLiv(?Livre $liv): static
    {
        $this->liv = $liv;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $date_emprunt): static
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(?\DateTimeInterface $date_retour): static
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function getDateRetourMax(): ?\DateTimeInterface
    {
        return $this->date_retour_max;
    }

    public function setDateRetourMax(\DateTimeInterface $date_retour_max): static
    {
        $this->date_retour_max = $date_retour_max;

        return $this;
    }

    public function isRetour(): ?bool
    {
        return $this->retour;
    }

    public function setRetour(bool $retour): static
    {
        $this->retour = $retour;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
