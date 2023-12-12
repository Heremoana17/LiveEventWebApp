<?php

namespace App\Entity;

use App\Repository\ImageSponsorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ImageSponsorRepository::class)]
#[ApiResource]
class ImageSponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforSponsor'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getforSponsor'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'imageSponsors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sponsor $sponsor = null;

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

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): static
    {
        $this->sponsor = $sponsor;

        return $this;
    }
}
