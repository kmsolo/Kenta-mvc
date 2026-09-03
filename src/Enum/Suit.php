<?php

declare(strict_types=1);

namespace App\Enum;

enum Suit: string
{
    case HEARTS = 'hearts';
    case DIAMONDS = 'diamonds';
    case CLUBS = 'clubs';
    case SPADES = 'spades';

    public function symbol(): string
    {
        return match ($this) {
            self::HEARTS => '♥',
            self::DIAMONDS => '♦',
            self::CLUBS => '♣',
            self::SPADES => '♠',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::HEARTS => 'Hjärter',
            self::DIAMONDS => 'Ruter',
            self::CLUBS => 'Klöver',
            self::SPADES => 'Spader',
        };
    }

    /**
     * Hjärter och ruter ska visas i rött, klöver och spader i svart.
     */
    public function isRed(): bool
    {
        return match ($this) {
            self::HEARTS, self::DIAMONDS => true,
            self::CLUBS, self::SPADES => false,
        };
    }

    /**
     * Namnkonvention som matchar svg-cards.svg (singularform).
     */
    public function svgName(): string
    {
        return match ($this) {
            self::HEARTS => 'heart',
            self::DIAMONDS => 'diamond',
            self::CLUBS => 'club',
            self::SPADES => 'spade',
        };
    }
}