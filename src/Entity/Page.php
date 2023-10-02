<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: BackgroundImage::class)]
    private Collection $backgroundImages;

    public function __construct()
    {
        $this->backgroundImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BackgroundImage>
     */
    public function getBackgroundImages(): Collection
    {
        return $this->backgroundImages;
    }

    public function addBackgroundImage(BackgroundImage $backgroundImage): static
    {
        if (!$this->backgroundImages->contains($backgroundImage)) {
            $this->backgroundImages->add($backgroundImage);
            $backgroundImage->setPage($this);
        }

        return $this;
    }

    public function removeBackgroundImage(BackgroundImage $backgroundImage): static
    {
        if ($this->backgroundImages->removeElement($backgroundImage)) {
            // set the owning side to null (unless already changed)
            if ($backgroundImage->getPage() === $this) {
                $backgroundImage->setPage(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this -> name;
    }
}
