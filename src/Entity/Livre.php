<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\Column(length: 255)]
    private ?string $editeur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_edition = null;

  

    #[ORM\Column(length: 255)]
    private ?string $isbn = null;
    

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_dispo = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'livres')]
    private ?Category $cat_id = null;

    #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'livres')]
    private ?SubCategory $subCategory = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $tags = null;
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $qte = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $qtedispo = null;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'liv')]
    private Collection $emprunts;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getDateEdition(): ?\DateTimeInterface
    {
        return $this->date_edition;
    }

    public function setDateEdition(\DateTimeInterface $date_edition): static
    {
        $this->date_edition = $date_edition;

        return $this;
    }

   

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    

    public function getDateDispo(): ?\DateTimeInterface
    {
        return $this->date_dispo;
    }

    public function setDateDispo(?\DateTimeInterface $date_dispo): static
    {
        $this->date_dispo = $date_dispo;

        return $this;
    }

    public function getCatId(): ?Category
    {
        return $this->cat_id;
    }

    public function setCatId(?Category $cat_id): static
    {
        $this->cat_id = $cat_id;
        return $this;
    }
    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }
    public function setSubCategory(?SubCategory $subCategory): static
    {
        $this->subCategory = $subCategory;

        return $this;
    }
    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
    
    public function getQtedispo(): ?int
    {
        return $this->qtedispo;
    }

    public function setQtedispo(int $qtedispo): static
    {
        $this->qtedispo = $qtedispo;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLiv($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLiv() === $this) {
                $emprunt->setLiv(null);
            }
        }

        return $this;
    }
}
