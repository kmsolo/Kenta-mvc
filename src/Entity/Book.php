<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'books')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Titel får inte vara tom.')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Författare får inte vara tomt.')]
    private ?string $author = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank(message: 'ISBN får inte vara tomt.')]
    #[Assert\Regex(pattern: '/^[0-9\-Xx]{10,17}$/', message: 'Ange ett giltigt ISBN-nummer.')]
    private ?string $isbn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Bygger en bokomslagsbild dynamiskt via Open Library Covers API,
     * baserat på bokens ISBN. Ingen bilduppladdning eller extra kolumn behövs.
     */
    public function getCoverImageUrl(): string
    {
        $cleanIsbn = str_replace('-', '', (string) $this->isbn);

        return sprintf('https://covers.openlibrary.org/b/isbn/%s-M.jpg?default=false', $cleanIsbn);
    }
}
