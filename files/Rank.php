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
}
