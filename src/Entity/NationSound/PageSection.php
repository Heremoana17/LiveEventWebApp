<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PageSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageSectionRepository::class)]
#[ApiResource]
class PageSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforView'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pageSections')]
    #[Groups(['getforView'])]
    private ?View $view = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getforView'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getforView'])]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'pageSection', targetEntity: Figure::class)]
    #[Groups(['getforView'])]
    private Collection $images;

    #[ORM\Column(length: 50)]
    private ?string $display = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getView(): ?View
    {
        return $this->view;
    }

    public function setView(?View $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Figure>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Figure $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPageSection($this);
        }

        return $this;
    }

    public function removeImage(Figure $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPageSection() === $this) {
                $image->setPageSection(null);
            }
        }

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): static
    {
        $this->display = $display;

        return $this;
    }
}
