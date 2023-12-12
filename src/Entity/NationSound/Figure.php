<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FigureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigureRepository::class)]
#[ApiResource]
class Figure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getforView'])]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'headerImage', cascade: ['persist', 'remove'])]
    private ?View $view = null;
    
    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?PageSection $pageSection = null;

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

    public function getView(): ?View
    {
        return $this->view;
    }

    public function setView(?View $view): static
    {
        // unset the owning side of the relation if necessary
        if ($view === null && $this->view !== null) {
            $this->view->setHeaderImage(null);
        }

        // set the owning side of the relation if necessary
        if ($view !== null && $view->getHeaderImage() !== $this) {
            $view->setHeaderImage($this);
        }

        $this->view = $view;

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
    
    public function getPageSection(): ?PageSection
    {
        return $this->pageSection;
    }

    public function setPageSection(?PageSection $pageSection): static
    {
        $this->pageSection = $pageSection;

        return $this;
    }
}
