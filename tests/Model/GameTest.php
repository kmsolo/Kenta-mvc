<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Enum\GameStatus;
use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use App\Model\Deck;
use App\Model\Game;
use App\Model\Hand;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGameCanBeCreated(): void
    {
        $game = new Game(new Deck());

        $this->assertInstanceOf(Game::class, $game);
    }

    public function testNewGameStartsInPlayerTurn(): void
    {
        $game = new Game(new Deck());

        $this->assertSame(GameStatus::PLAYER_TURN, $game->getStatus());
        $this->assertFalse($game->isFinished());
        $this->assertNull($game->getResult());
    }

    public function testStartNewRoundDealsOneCardToPlayer(): void
    {
        $game = Game::startNewRound();

        $this->assertSame(1, $game->getPlayerHand()->count());
        $this->assertSame(0, $game->getBankHand()->count());
        $this->assertSame(GameStatus::PLAYER_TURN, $game->getStatus());
        $this->assertSame(51, $game->getDeck()->count());
    }

    public function testHitAddsCardToPlayerHand(): void
    {
        $deck = new Deck();
        $deck->setCards([new Card(Suit::HEARTS, Rank::TWO)]);
        $game = new Game($deck);

        $game->hit();

        $this->assertSame(1, $game->getPlayerHand()->count());
        $this->assertSame(GameStatus::PLAYER_TURN, $game->getStatus());
    }

    public function testHitThatBustsFinishesGameWithBankWins(): void
    {
        $playerHand = new Hand();
        $playerHand->addCard(new Card(Suit::HEARTS, Rank::KING));
        $playerHand->addCard(new Card(Suit::SPADES, Rank::QUEEN));

        $deck = new Deck();
        $deck->setCards([new Card(Suit::CLUBS, Rank::TWO)]);

        $game = new Game($deck, $playerHand);
        $game->hit();

        $this->assertTrue($game->getPlayerHand()->isBust());
        $this->assertSame(GameStatus::FINISHED, $game->getStatus());
        $this->assertSame('bank_wins', $game->getResult());
    }

    public function testHitDoesNothingWhenGameIsAlreadyFinished(): void
    {
        $deck = new Deck();
        $deck->setCards([new Card(Suit::HEARTS, Rank::TWO)]);
        $game = new Game($deck);
        $game->restoreState(GameStatus::FINISHED, 'bank_wins');

        $game->hit();

        $this->assertSame(0, $game->getPlayerHand()->count());
        $this->assertSame(1, $game->getDeck()->count());
    }

    public function testStandDoesNothingWhenGameIsAlreadyFinished(): void
    {
        $deck = new Deck();
        $deck->setCards([new Card(Suit::HEARTS, Rank::TWO)]);
        $game = new Game($deck);
        $game->restoreState(GameStatus::FINISHED, 'player_wins');

        $game->stand();

        $this->assertSame(0, $game->getBankHand()->count());
        $this->assertSame(1, $game->getDeck()->count());
        $this->assertSame('player_wins', $game->getResult());
    }

    public function testStandPlayerWinsWhenHigherThanBank(): void
    {
        $playerHand = new Hand();
        $playerHand->addCard(new Card(Suit::HEARTS, Rank::KING));
        $playerHand->addCard(new Card(Suit::SPADES, Rank::EIGHT));

        // Banken drar tills den når 17: SJU (7) först, sedan TIO (10) -> 7, 17.
        $deck = new Deck();
        $deck->setCards([
            new Card(Suit::CLUBS, Rank::SEVEN),
            new Card(Suit::DIAMONDS, Rank::TEN),
        ]);

        $game = new Game($deck, $playerHand);
        $game->stand();

        $this->assertSame(18, $game->getPlayerHand()->getValue());
        $this->assertSame(17, $game->getBankHand()->getValue());
        $this->assertTrue($game->isFinished());
        $this->assertSame('player_wins', $game->getResult());
    }

    public function testStandBankWinsWhenHigherThanPlayer(): void
    {
        $playerHand = new Hand();
        $playerHand->addCard(new Card(Suit::HEARTS, Rank::KING));
        $playerHand->addCard(new Card(Suit::SPADES, Rank::EIGHT));

        // Banken drar: NIO (9) först, sedan TIO (10) -> 9, 19.
        $deck = new Deck();
        $deck->setCards([
            new Card(Suit::CLUBS, Rank::NINE),
            new Card(Suit::DIAMONDS, Rank::TEN),
        ]);

        $game = new Game($deck, $playerHand);
        $game->stand();

        $this->assertSame(18, $game->getPlayerHand()->getValue());
        $this->assertSame(19, $game->getBankHand()->getValue());
        $this->assertSame('bank_wins', $game->getResult());
    }

    public function testStandBankWinsOnTie(): void
    {
        $playerHand = new Hand();
        $playerHand->addCard(new Card(Suit::HEARTS, Rank::TEN));
        $playerHand->addCard(new Card(Suit::SPADES, Rank::SEVEN));

        // Banken drar till exakt 17: SJU (7) först, sedan TIO (10) -> 7, 17.
        $deck = new Deck();
        $deck->setCards([
            new Card(Suit::CLUBS, Rank::SEVEN),
            new Card(Suit::DIAMONDS, Rank::TEN),
        ]);

        $game = new Game($deck, $playerHand);
        $game->stand();

        $this->assertSame(17, $game->getPlayerHand()->getValue());
        $this->assertSame(17, $game->getBankHand()->getValue());
        $this->assertSame('bank_wins', $game->getResult());
    }

    public function testStandPlayerWinsWhenBankBusts(): void
    {
        $playerHand = new Hand();
        $playerHand->addCard(new Card(Suit::HEARTS, Rank::TEN));
        $playerHand->addCard(new Card(Suit::SPADES, Rank::SEVEN));

        // Banken bygger långsamt (4, 5, 6 -> 15, fortfarande < 17) och
        // spricker sedan på ett TIO (15 + 10 = 25).
        $deck = new Deck();
        $deck->setCards([
            new Card(Suit::CLUBS, Rank::TEN),
            new Card(Suit::CLUBS, Rank::SIX),
            new Card(Suit::CLUBS, Rank::FIVE),
            new Card(Suit::CLUBS, Rank::FOUR),
        ]);

        $game = new Game($deck, $playerHand);
        $game->stand();

        $this->assertTrue($game->getBankHand()->isBust());
        $this->assertSame(25, $game->getBankHand()->getValue());
        $this->assertTrue($game->isFinished());
        $this->assertSame('player_wins', $game->getResult());
    }

    public function testRestoreStateSetsStatusAndResult(): void
    {
        $game = new Game(new Deck());

        $game->restoreState(GameStatus::FINISHED, 'player_wins');

        $this->assertSame(GameStatus::FINISHED, $game->getStatus());
        $this->assertSame('player_wins', $game->getResult());
        $this->assertTrue($game->isFinished());
    }
}