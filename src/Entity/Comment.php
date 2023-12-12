<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    // normalizationContext:['groups' => ['read:collection']], 
    // order: ['createdAt' => 'DESC'],
    operations:[
        new Get(normalizationContext:['groups' => ['getforarticle']]),
        new GetCollection(normalizationContext:['groups' => ['getforcomment','authorForComment','articleForComment']]),
        new Post()
    ]
)]
class Comment
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getforcomment'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getforcomment'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['authorForComment'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getforcomment'])]
    private ?Article $relatedArticle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $authorMobile = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getRelatedArticle(): ?Article
    {
        return $this->relatedArticle;
    }

    public function setRelatedArticle(?Article $relatedArticle): static
    {
        $this->relatedArticle = $relatedArticle;

        return $this;
    }

    public function getAuthorMobile(): ?string
    {
        return $this->authorMobile;
    }

    public function setAuthorMobile(?string $authorMobile): static
    {
        $this->authorMobile = $authorMobile;

        return $this;
    }
}
