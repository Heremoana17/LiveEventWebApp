<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ViewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewRepository::class)]
#[ApiResource(
    operations:[
        new Get(normalizationContext:['groups' => ['getforView']]),
        new GetCollection(normalizationContext:['groups' => ['getforView']]),
    ]
)]
class View
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforView'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getforView'])]
    private ?string $name = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['getforView'])]
    private ?string $headerText = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToOne(inversedBy: 'view', cascade: ['persist', 'remove'])]
    #[Groups(['getforView'])]
    private ?Figure $headerImage = null;
    
    #[ORM\OneToMany(mappedBy: 'view', targetEntity: PageSection::class)]
    #[Groups(['getforView'])]
    private Collection $pageSections;

    public function __construct()
    {
        $this->pageSections = new ArrayCollection();
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
    
    /**
     * @return Collection<int, PageSection>
     */
    public function getPageSections(): Collection
    {
        return $this->pageSections;
    }

    public function addPageSection(PageSection $pageSection): static
    {
        if (!$this->pageSections->contains($pageSection)) {
            $this->pageSections->add($pageSection);
            $pageSection->setView($this);
        }

        return $this;
    }

    public function removePageSection(PageSection $pageSection): static
    {
        if ($this->pageSections->removeElement($pageSection)) {
            // set the owning side to null (unless already changed)
            if ($pageSection->getView() === $this) {
                $pageSection->setView(null);
            }
        }

        return $this;
    }
}
