<?php

namespace App\Entity;

use App\Repository\GadgetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GadgetRepository::class)]
#[ORM\Table(name: 'gadgets')]
class Gadget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Namn får inte vara tomt.')]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Varumärke får inte vara tomt.')]
    private ?string $brand = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Kategori måste anges.')]
    private ?string $category = null;

    #[ORM\Column]
    #[Assert\Range(min: 1970, max: 2100, notInRangeMessage: 'Ange ett rimligt årtal.')]
    private ?int $releaseYear = null;

    #[ORM\Column(type: 'float')]
    #[Assert\PositiveOrZero(message: 'Priset kan inte vara negativt.')]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'Betyg måste vara mellan 1 och 5.')]
    private ?int $rating = 5;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

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

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(int $releaseYear): static
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

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
     * Returnerar en emoji-ikon baserat på prylens kategori.
     * Slipper bero på externa bildtjänster eller upphovsrättsskyddade bilder.
     */
    public function getCategoryIcon(): string
    {
        return match ($this->category) {
            'Mobil' => '📱',
            'Dator' => '💻',
            'Ljud' => '🎧',
            'Gaming' => '🎮',
            'Tillbehör' => '⌨️',
            'Smart hem' => '🏠',
            'Kamera' => '📷',
            'Wearable' => '⌚',
            default => '🔌',
        };
    }

    /**
     * Returnerar betyget som en sträng av stjärnor, t.ex. "★★★★☆".
     */
    public function getStars(): string
    {
        $rating = $this->rating ?? 0;

        return str_repeat('★', $rating).str_repeat('☆', 5 - $rating);
    }
}
