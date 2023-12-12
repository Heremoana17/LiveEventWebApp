<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource]
class Event
{
    use SlugTrait;
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforSponsor'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getforSponsor'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: ImageEvent::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $imageEvents;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'events')]
    private Collection $category;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $featuredImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\OneToMany(mappedBy: 'relatedEvent', targetEntity: Article::class)]
    private Collection $relatedArticles;

    #[ORM\ManyToOne(inversedBy: 'event')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Sponsor::class)]
    private Collection $sponsors;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Billet::class)]
    private Collection $billets;
    
    public function __construct()
    {
        $this->imageEvents = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->category = new ArrayCollection();
        $this->relatedArticles = new ArrayCollection();
        $this->sponsors = new ArrayCollection();
        $this->billets = new ArrayCollection();
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): static
    {
        $this->introduction = $introduction;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, ImageEvent>
     */
    public function getImageEvents(): Collection
    {
        return $this->imageEvents;
    }

    public function addImageEvent(ImageEvent $imageEvent): static
    {
        if (!$this->imageEvents->contains($imageEvent)) {
            $this->imageEvents->add($imageEvent);
            $imageEvent->setEvent($this);
        }

        return $this;
    }

    public function removeImageEvent(ImageEvent $imageEvent): static
    {
        if ($this->imageEvents->removeElement($imageEvent)) {
            // set the owning side to null (unless already changed)
            if ($imageEvent->getEvent() === $this) {
                $imageEvent->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(?string $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getRelatedArticles(): Collection
    {
        return $this->relatedArticles;
    }

    public function addRelatedArticle(Article $relatedArticle): static
    {
        if (!$this->relatedArticles->contains($relatedArticle)) {
            $this->relatedArticles->add($relatedArticle);
            $relatedArticle->setRelatedEvent($this);
        }

        return $this;
    }

    public function removeRelatedArticle(Article $relatedArticle): static
    {
        if ($this->relatedArticles->removeElement($relatedArticle)) {
            // set the owning side to null (unless already changed)
            if ($relatedArticle->getRelatedEvent() === $this) {
                $relatedArticle->setRelatedEvent(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Sponsor>
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsor $sponsor): static
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors->add($sponsor);
            $sponsor->setEvent($this);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): static
    {
        if ($this->sponsors->removeElement($sponsor)) {
            // set the owning side to null (unless already changed)
            if ($sponsor->getEvent() === $this) {
                $sponsor->setEvent(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, Billet>
     */
    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addBillet(Billet $billet): static
    {
        if (!$this->billets->contains($billet)) {
            $this->billets->add($billet);
            $billet->setEvent($this);
        }

        return $this;
    }

    public function removeBillet(Billet $billet): static
    {
        if ($this->billets->removeElement($billet)) {
            // set the owning side to null (unless already changed)
            if ($billet->getEvent() === $this) {
                $billet->setEvent(null);
            }
        }

        return $this;
    }


}
