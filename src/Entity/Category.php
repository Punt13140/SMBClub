<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="category")
     */
    private $topics;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="categoryChilds")
     */
    private $categoryParent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="categoryParent")
     */
    private $categoryChilds;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->categoryChilds = new ArrayCollection();
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

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setCategory($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getCategory() === $this) {
                $topic->setCategory(null);
            }
        }

        return $this;
    }

    public function getCategoryParent(): ?self
    {
        return $this->categoryParent;
    }

    public function setCategoryParent(?self $categoryParent): self
    {
        $this->categoryParent = $categoryParent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategoryChilds(): Collection
    {
        return $this->categoryChilds;
    }

    public function addCategoryChild(self $categoryChild): self
    {
        if (!$this->categoryChilds->contains($categoryChild)) {
            $this->categoryChilds[] = $categoryChild;
            $categoryChild->setCategoryParent($this);
        }

        return $this;
    }

    public function removeCategoryChild(self $categoryChild): self
    {
        if ($this->categoryChilds->removeElement($categoryChild)) {
            // set the owning side to null (unless already changed)
            if ($categoryChild->getCategoryParent() === $this) {
                $categoryChild->setCategoryParent(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }


}
