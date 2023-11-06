<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LieuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
#[ApiResource]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $GPSPtLat = null;

    #[ORM\Column(length: 255)]
    private ?string $GPSPtLng = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $featuredImage = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGPSPtLat(): ?string
    {
        return $this->GPSPtLat;
    }

    public function setGPSPtLat(string $GPSPtLat): static
    {
        $this->GPSPtLat = $GPSPtLat;

        return $this;
    }
    public function getGPSPtLng(): ?string
    {
        return $this->GPSPtLng;
    }

    public function setGPSPtLng(string $GPSPtLng): static
    {
        $this->GPSPtLng = $GPSPtLng;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(string $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }
}
