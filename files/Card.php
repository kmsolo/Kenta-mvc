<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\Rank;
use App\Enum\Suit;

/**
 * Representerar ett enskilt spelkort. Klassen är immutable (readonly)
 * eftersom ett kort aldrig ska ändra valör eller färg efter skapande.
 */
final readonly class Card
{
    public function __construct(
        private Suit $suit,
        private Rank $rank,
    ) {
    }

    public function getSuit(): Suit
    {
        return $this->suit;
    }

    public function getRank(): Rank
    {
        return $this->rank;
    }

    public function getLabel(): string
    {
        return sprintf('%s i %s', $this->rank->label(), $this->suit->label());
    }

    public function __toString(): string
    {
        return sprintf('%s%s', $this->rank->label(), $this->suit->symbol());
    }
}
