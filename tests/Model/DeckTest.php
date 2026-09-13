<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use PHPUnit\Framework\TestCase;

final class DeckTest extends TestCase
{
    public function testCardCanBeCreated(): void
    {
        $card = new Card(Suit::HEARTS, Rank::ACE);

        $this->assertInstanceOf(Card::class, $card);
    }

    public function testGetSuitReturnsSuit(): void
    {
        $card = new Card(Suit::SPADES, Rank::KING);

        $this->assertSame(Suit::SPADES, $card->getSuit());
    }

    public function testGetRankReturnsRank(): void
    {
        $card = new Card(Suit::CLUBS, Rank::TEN);

        $this->assertSame(Rank::TEN, $card->getRank());
    }

    public function testGetLabelReturnsExpectedString(): void
    {
        $card = new Card(Suit::DIAMONDS, Rank::ACE);

        $this->assertSame('Ess i Ruter', $card->getLabel());
    }

    public function testGetImageIdReturnsExpectedString(): void
    {
        $card = new Card(Suit::HEARTS, Rank::QUEEN);

        $this->assertSame('heart_queen', $card->getImageId());
    }

    public function testToStringReturnsExpectedString(): void
    {
        $card = new Card(Suit::SPADES, Rank::KING);

        $this->assertSame('Kung♠', (string) $card);
    }
}