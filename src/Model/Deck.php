<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\Rank;
use App\Enum\Suit;

/**
 * Representerar en kortlek om 52 kort och all logik för att
 * blanda, dra och återställa den.
 */
final class Deck
{
    /** @var Card[] */
    private array $cards = [];

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Fyller på kortleken med samtliga 52 kort i ordning.
     */
    public function reset(): void
    {
        $this->cards = [];
        foreach (Suit::cases() as $suit) {
            foreach (Rank::cases() as $rank) {
                $this->cards[] = new Card($suit, $rank);
            }
        }
    }

    /**
     * Blandar korten som finns kvar i leken.
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Drar det översta kortet, eller null om leken är tom.
     */
    public function draw(): ?Card
    {
        return array_pop($this->cards);
    }

    /**
     * Drar upp till $number kort. Stannar tidigare om leken tar slut.
     *
     * @return Card[]
     */
    public function drawMultiple(int $number): array
    {
        $drawn = [];
        for ($i = 0; $i < $number && !$this->isEmpty(); $i++) {
            $drawn[] = $this->draw();
        }

        return $drawn;
    }

    public function count(): int
    {
        return count($this->cards);
    }

    public function isEmpty(): bool
    {
        return [] === $this->cards;
    }

    /** @return Card[] */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Ersätter korten i leken, t.ex. när tillståndet återskapas
     * från en session mellan HTTP-anrop.
     *
     * @param Card[] $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = array_values($cards);
    }
}