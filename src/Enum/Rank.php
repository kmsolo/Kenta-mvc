<?php

declare(strict_types=1);

namespace App\Enum;

enum Rank: int
{
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case SIX = 6;
    case SEVEN = 7;
    case EIGHT = 8;
    case NINE = 9;
    case TEN = 10;
    case JACK = 11;
    case QUEEN = 12;
    case KING = 13;
    case ACE = 14;

    public function label(): string
    {
        return match ($this) {
            self::JACK => 'Knekt',
            self::QUEEN => 'Dam',
            self::KING => 'Kung',
            self::ACE => 'Ess',
            default => (string) $this->value,
        };
    }

    /**
     * Kort etikett för kortets hörn, t.ex. "J", "Q", "K", "A" eller "10".
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::JACK => 'J',
            self::QUEEN => 'Q',
            self::KING => 'K',
            self::ACE => 'A',
            default => (string) $this->value,
        };
    }

    /**
     * Sant för klädkorten (knekt, dam, kung) som ska visas med en
     * illustration istället för prickar.
     */
    public function isFace(): bool
    {
        return match ($this) {
            self::JACK, self::QUEEN, self::KING => true,
            default => false,
        };
    }

    public function isAce(): bool
    {
        return self::ACE === $this;
    }

    /**
     * Namnkonvention som matchar svg-cards.svg (t.ex. "1" för ess).
     */
    public function svgName(): string
    {
        return match ($this) {
            self::ACE => '1',
            self::JACK => 'jack',
            self::QUEEN => 'queen',
            self::KING => 'king',
            default => (string) $this->value, // 2–10
        };
    }
}