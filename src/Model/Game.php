<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\GameStatus;

/**
 * Representerar en pågående omgång av spelet 21. Håller reda på
 * kortleken, spelarens och bankens händer, samt vems tur det är.
 * Klassen är "tjock" med all spelregel-logik, så att kontrollern
 * kan hållas tunn (den bara anropar metoder här och renderar resultatet).
 */
final class Game
{
    private const int BANK_STANDS_AT = 17;

    private GameStatus $status;
    private ?string $result = null;

    public function __construct(
        private Deck $deck,
        private Hand $playerHand = new Hand(),
        private Hand $bankHand = new Hand(),
    ) {
        $this->status = GameStatus::PLAYER_TURN;
    }

    /**
     * Startar en ny omgång: fräsch, blandad kortlek och tomma händer,
     * och delar sedan ut spelarens första kort.
     */
    public static function startNewRound(): self
    {
        $deck = new Deck();
        $deck->shuffle();

        $game = new self($deck);
        $game->playerHand->addCard($deck->draw());

        return $game;
    }

    public function hit(): void
    {
        if (GameStatus::PLAYER_TURN !== $this->status) {
            return;
        }

        $this->playerHand->addCard($this->deck->draw());

        if ($this->playerHand->isBust()) {
            $this->finish('bank_wins');
        }
    }

    public function stand(): void
    {
        if (GameStatus::PLAYER_TURN !== $this->status) {
            return;
        }

        $this->playBank();

        $this->finish($this->determineWinner());
    }

    private function playBank(): void
    {
        while ($this->bankHand->getValue() < self::BANK_STANDS_AT && !$this->deck->isEmpty()) {
            $this->bankHand->addCard($this->deck->draw());
        }
    }

    private function determineWinner(): string
    {
        if ($this->bankHand->isBust()) {
            return 'player_wins';
        }

        if ($this->playerHand->getValue() > $this->bankHand->getValue()) {
            return 'player_wins';
        }

        // Lika värde -> banken vinner, enligt spelets regler.
        return 'bank_wins';
    }

    private function finish(string $result): void
    {
        $this->status = GameStatus::FINISHED;
        $this->result = $result;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function getPlayerHand(): Hand
    {
        return $this->playerHand;
    }

    public function getBankHand(): Hand
    {
        return $this->bankHand;
    }

    public function getStatus(): GameStatus
    {
        return $this->status;
    }

    public function isFinished(): bool
    {
        return GameStatus::FINISHED === $this->status;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * Återställer status och resultat efter att spelet lästs in från
     * sessionen. Kallas bara av GameSessionManager - konstruktorn/
     * startNewRound() räcker för allt annat.
     */
    public function restoreState(GameStatus $status, ?string $result): void
    {
        $this->status = $status;
        $this->result = $result;
    }
}
