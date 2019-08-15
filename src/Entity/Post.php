<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Controller\PostTransitionController;

/**
 * @ApiResource(
 *     itemOperations={"get", "put", "delete",
 *          "status"={
 *              "method"="PATCH",
 *              "path"="/posts/{id}/{transition}",
 *              "controller"=PostTransitionController::class
 *          }
 *     },
 *     normalizationContext={"groups"={"post:read"}},
 *     denormalizationContext={"groups"={"post:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @UniqueEntity(fields="slug")
 */
class Post
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'draft';
    public const STATUS_TRASH = 'trash';
    public const STATUS_ARCHIVED = 'archived';

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post:read", "post:write"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=100, unique=true)
     * @Groups({"post:read"})
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     * @Groups({"post:read"})
     */
    private $status = self::STATUS_DRAFT;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"post:read", "post:write"})
     * @Assert\DateTime()
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post:read", "post:write"})
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="posts")
     * @Groups({"post:read", "post:write"})
     * @ApiSubresource()
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
