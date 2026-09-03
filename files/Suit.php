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
}
