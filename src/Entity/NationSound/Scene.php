<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneRepository::class)]
#[ApiResource]
class Scene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $name = null;

    #[ORM\Column(length: 200, nullable:true)]
    private ?string $featuredImage = null;

    #[ORM\Column(length: 255)]
    private ?string $GPSPtLat = null;

    #[ORM\Column(length: 255)]
    private ?string $GPSPtLng = null;

    #[ORM\OneToMany(mappedBy: 'scene', targetEntity: Episode::class)]
    private Collection $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
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

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(string $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): static
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setScene($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getScene() === $this) {
                $episode->setScene(null);
            }
        }

        return $this;
    }
}
