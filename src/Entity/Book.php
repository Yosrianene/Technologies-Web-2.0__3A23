<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $publicationDate = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    #[ORM\OneToOne(inversedBy: 'book', cascade: ['persist', 'remove'])]
    private ?Student $student = null;

    #[ORM\ManyToMany(targetEntity: Reader::class, mappedBy: 'books')]
    private Collection $readers;

    public function __construct()
    {
        $this->readers = new ArrayCollection();
        $this->published = true; // par défaut
    }

    // --- Getters / Setters ---

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getPublicationDate(): ?\DateTime { return $this->publicationDate; }
    public function setPublicationDate(\DateTime $publicationDate): static { $this->publicationDate = $publicationDate; return $this; }

    public function isPublished(): ?bool { return $this->published; }
    public function setPublished(bool $published): static { $this->published = $published; return $this; }

    public function getCategory(): ?string { return $this->category; }
    public function setCategory(string $category): static { $this->category = $category; return $this; }

    public function getAuthor(): ?Author { return $this->author; }
    public function setAuthor(?Author $author): static { $this->author = $author; return $this; }

    public function getStudent(): ?Student { return $this->student; }
    public function setStudent(?Student $student): static { $this->student = $student; return $this; }

    public function getReaders(): Collection { return $this->readers; }
    public function addReader(Reader $reader): static { if (!$this->readers->contains($reader)) { $this->readers->add($reader); $reader->addBook($this); } return $this; }
    public function removeReader(Reader $reader): static { if ($this->readers->removeElement($reader)) { $reader->removeBook($this); } return $this; }
}
