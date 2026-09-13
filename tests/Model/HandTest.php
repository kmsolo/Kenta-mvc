<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use App\Model\Hand;
use PHPUnit\Framework\TestCase;

final class HandTest extends TestCase
{
    public function testHandCanBeCreated(): void
    {
        $hand = new Hand();

        $this->assertInstanceOf(Hand::class, $hand);
    }

    public function testHandStartsEmpty(): void
    {
        $hand = new Hand();

        $this->assertSame(0, $hand->count());
        $this->assertSame([], $hand->getCards());
    }

    public function testAddCardIncreasesCount(): void
    {
        $hand = new Hand();

        $hand->addCard(new Card(Suit::HEARTS, Rank::ACE));

        $this->assertSame(1, $hand->count());
    }

    public function testGetCardsReturnsAddedCards(): void
    {
        $hand = new Hand();
        $card = new Card(Suit::SPADES, Rank::KING);

        $hand->addCard($card);

        $this->assertSame([$card], $hand->getCards());
    }

    public function testGetValueSumsCardsCorrectly(): void
    {
        $hand = new Hand();
        $hand->addCard(new Card(Suit::HEARTS, Rank::TEN));
        $hand->addCard(new Card(Suit::SPADES, Rank::SEVEN));

        $this->assertSame(17, $hand->getValue());
    }

    public function testGetValueCountsAceAsElevenWhenSafe(): void
    {
        $hand = new Hand();
        $hand->addCard(new Card(Suit::HEARTS, Rank::ACE));
        $hand->addCard(new Card(Suit::SPADES, Rank::SEVEN));

        $this->assertSame(18, $hand->getValue());
    }

    public function testGetValueCountsAceAsOneWhenNeeded(): void
    {
        $hand = new Hand();
        $hand->addCard(new Card(Suit::HEARTS, Rank::ACE));
        $hand->addCard(new Card(Suit::SPADES, Rank::KING));
        $hand->addCard(new Card(Suit::CLUBS, Rank::QUEEN));

        $this->assertSame(21, $hand->getValue());
    }

    public function testIsBustReturnsTrueWhenAbove21(): void
    {
        $hand = new Hand();
        $hand->addCard(new Card(Suit::HEARTS, Rank::KING));
        $hand->addCard(new Card(Suit::SPADES, Rank::QUEEN));
        $hand->addCard(new Card(Suit::CLUBS, Rank::TWO));

        $this->assertTrue($hand->isBust());
    }

    public function testIsBustReturnsFalseWhen21OrLess(): void
    {
        $hand = new Hand();
        $hand->addCard(new Card(Suit::HEARTS, Rank::ACE));
        $hand->addCard(new Card(Suit::SPADES, Rank::QUEEN));

        $this->assertFalse($hand->isBust());
    }

    public function testSetCardsReplacesHandContent(): void
    {
        $card = new Card(Suit::DIAMONDS, Rank::ACE);
        $hand = new Hand();

        $hand->setCards([$card]);

        $this->assertSame([$card], $hand->getCards());
        $this->assertSame(1, $hand->count());
    }
}