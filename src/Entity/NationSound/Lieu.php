<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\LieuRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
#[ApiResource(
    operations:[
        new Get(normalizationContext:['groups' => ['getforLieu']]),
        new GetCollection(normalizationContext:['groups' => ['getforLieu']]),
    ]
)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforDay', 'getforLieu','getforProg','getforEpisode'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getforDay', 'getforLieu', 'getforEpisode','getforProg','getforEpisode'])]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['getforLieu','getforProg'])]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['getforLieu','getforProg'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getforLieu'])]
    private ?string $GPSPtLat = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getforLieu'])]
    private ?string $GPSPtLng = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['getforDay', 'getforLieu','getforProg','getforEpisode'])]
    private ?string $featuredImage = null;
    
    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Link::class)]
    #[Groups(['getforDay', 'getforLieu'])]
    private Collection $links;

    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Episode::class)]
    #[Groups(['getforDay','getforLieu'])]
    private Collection $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->links = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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
            $episode->setLieu($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getLieu() === $this) {
                $episode->setLieu(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setLieu($this);
        }

        return $this;
    }

    public function removeLink(Link $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getLieu() === $this) {
                $link->setLieu(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }



}
