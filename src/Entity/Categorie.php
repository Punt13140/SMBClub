<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHome;

    /**
     * @ORM\OneToMany(targetEntity=Discussion::class, mappedBy="categorie")
     */
    private $discussions;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="categorieChilds")
     */
    private $categorieParent;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="categorieParent")
     */
    private $categorieChilds;

    public function __construct()
    {
        $this->discussions = new ArrayCollection();
        $this->categorieChilds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getIsHome(): ?bool
    {
        return $this->isHome;
    }

    public function setIsHome(bool $isHome): self
    {
        $this->isHome = $isHome;

        return $this;
    }

    /**
     * @return Collection|Discussion[]
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->discussions->contains($discussion)) {
            $this->discussions[] = $discussion;
            $discussion->setCategorie($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->discussions->removeElement($discussion)) {
            // set the owning side to null (unless already changed)
            if ($discussion->getCategorie() === $this) {
                $discussion->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCategorieParent(): ?self
    {
        return $this->categorieParent;
    }

    public function setCategorieParent(?self $categorieParent): self
    {
        $this->categorieParent = $categorieParent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategorieChilds(): Collection
    {
        return $this->categorieChilds;
    }

    public function addCategorieChild(self $categorieChild): self
    {
        if (!$this->categorieChilds->contains($categorieChild)) {
            $this->categorieChilds[] = $categorieChild;
            $categorieChild->setCategorieParent($this);
        }

        return $this;
    }

    public function removeCategorieChild(self $categorieChild): self
    {
        if ($this->categorieChilds->removeElement($categorieChild)) {
            // set the owning side to null (unless already changed)
            if ($categorieChild->getCategorieParent() === $this) {
                $categorieChild->setCategorieParent(null);
            }
        }

        return $this;
    }
}
