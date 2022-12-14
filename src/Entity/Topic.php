<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titreTopic;

    /**
     * @ORM\Column(type="date")
     */
    private $dateTopic;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verrouTopic;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $resoluTopic;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="topic", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreTopic(): ?string
    {
        return $this->titreTopic;
    }

    public function setTitreTopic(string $titreTopic): self
    {
        $this->titreTopic = $titreTopic;

        return $this;
    }

    public function getDateTopic(): ?\DateTimeInterface
    {
        return $this->dateTopic;
    }

    public function setDateTopic(\DateTimeInterface $dateTopic): self
    {
        $this->dateTopic = $dateTopic;

        return $this;
    }

    public function isVerrouTopic(): ?bool
    {
        return $this->verrouTopic;
    }

    public function setVerrouTopic(?bool $verrouTopic): self
    {
        $this->verrouTopic = $verrouTopic;

        return $this;
    }

    public function isResoluTopic(): ?bool
    {
        return $this->resoluTopic;
    }

    public function setResoluTopic(?bool $resoluTopic): self
    {
        $this->resoluTopic = $resoluTopic;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setTopic($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getTopic() === $this) {
                $post->setTopic(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
