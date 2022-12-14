<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $textePost;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextePost(): ?string
    {
        return $this->textePost;
    }

    public function setTextePost(string $textePost): self
    {
        $this->textePost = $textePost;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

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

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this->datePost;
    }

    public function setDatePost(\DateTimeInterface $datePost): self
    {
        $this->datePost = $datePost;

        return $this;
    }
}
