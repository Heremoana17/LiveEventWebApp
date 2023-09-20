<?php

namespace App\Entity;

use App\Repository\SponsorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'sponsor', targetEntity: ImageSponsor::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $imageSponsors;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    public function __construct()
    {
        $this->imageSponsors = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ImageSponsor>
     */
    public function getImageSponsors(): Collection
    {
        return $this->imageSponsors;
    }

    public function addImageSponsor(ImageSponsor $imageSponsor): static
    {
        if (!$this->imageSponsors->contains($imageSponsor)) {
            $this->imageSponsors->add($imageSponsor);
            $imageSponsor->setSponsor($this);
        }

        return $this;
    }

    public function removeImageSponsor(ImageSponsor $imageSponsor): static
    {
        if ($this->imageSponsors->removeElement($imageSponsor)) {
            // set the owning side to null (unless already changed)
            if ($imageSponsor->getSponsor() === $this) {
                $imageSponsor->setSponsor(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

}
