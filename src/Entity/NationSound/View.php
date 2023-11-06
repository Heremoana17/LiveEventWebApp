<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ViewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewRepository::class)]
#[ApiResource]
class View
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $headerText = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToOne(inversedBy: 'view', cascade: ['persist', 'remove'])]
    private ?Figure $headerImage = null;

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

    public function getHeaderText(): ?string
    {
        return $this->headerText;
    }

    public function setHeaderText(?string $headerText): static
    {
        $this->headerText = $headerText;

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

    public function getHeaderImage(): ?Figure
    {
        return $this->headerImage;
    }

    public function setHeaderImage(?Figure $headerImage): static
    {
        $this->headerImage = $headerImage;

        return $this;
    }
}
