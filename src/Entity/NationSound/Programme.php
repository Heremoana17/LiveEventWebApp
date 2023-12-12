<?php

namespace App\Entity\NationSound;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
#[ApiResource(
    operations:[
        new Get(normalizationContext:['groups' => ['getforProg']]),
        new GetCollection(normalizationContext:['groups' => ['getforProg']]),
    ]
)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforProg'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getforProg'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: Day::class)]
    #[Groups(['getforProg'])]
    private Collection $day;

    public function __construct()
    {
        $this->day = new ArrayCollection();
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

    /**
     * @return Collection<int, Day>
     */
    public function getDay(): Collection
    {
        return $this->day;
    }

    public function addDay(Day $day): static
    {
        if (!$this->day->contains($day)) {
            $this->day->add($day);
            $day->setProgramme($this);
        }

        return $this;
    }

    public function removeDay(Day $day): static
    {
        if ($this->day->removeElement($day)) {
            // set the owning side to null (unless already changed)
            if ($day->getProgramme() === $this) {
                $day->setProgramme(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
