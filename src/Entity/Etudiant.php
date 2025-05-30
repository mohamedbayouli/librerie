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
    private ?string $num_carte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCarte(): ?string
    {
        return $this->num_carte;
    }

    public function setNumCarte(string $num_carte): static
    {
        $this->num_carte = $num_carte;

        return $this;
    }
}
