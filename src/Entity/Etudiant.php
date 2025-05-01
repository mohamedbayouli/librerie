<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numCarte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCarte(): ?string
    {
        return $this->numCarte;
    }

    public function setNumCarte(string $numCarte): static
    {
        $this->numCarte = $numCarte;

        return $this;
    }
}
