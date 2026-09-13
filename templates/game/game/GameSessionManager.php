<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\GameStatus;
use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use App\Model\Deck;
use App\Model\Game;
use App\Model\Hand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Ansvarar för att läsa och spara ett Game-objekts tillstånd i sessionen,
 * på samma sätt som DeckSessionManager gör för den fristående kortleken.
 */
final class GameSessionManager
{
    private const string GAME_KEY = 'game_state';

    public function load(SessionInterface $session): Game
    {
        /** @var array<string, mixed>|null $stored */
        $stored = $session->get(self::GAME_KEY);

        if (null === $stored) {
            $game = Game::startNewRound();
            $this->save($session, $game);

            return $game;
        }

        return $this->fromArray($stored);
    }

    public function save(SessionInterface $session, Game $game): void
    {
        $session->set(self::GAME_KEY, $this->toArray($game));
    }

    public function startNewRound(SessionInterface $session): Game
    {
        $game = Game::startNewRound();
        $this->save($session, $game);

        return $game;
    }

    /**
     * @return array<string, mixed>
     */
    private function toArray(Game $game): array
    {
        return [
            'deck' => array_map(self::cardToPrimitive(...), $game->getDeck()->getCards()),
            'playerHand' => array_map(self::cardToPrimitive(...), $game->getPlayerHand()->getCards()),
            'bankHand' => array_map(self::cardToPrimitive(...), $game->getBankHand()->getCards()),
            'status' => $game->getStatus()->value,
            'result' => $game->getResult(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function fromArray(array $data): Game
    {
        $deck = new Deck();
        $deck->setCards(self::cardsFromPrimitive($data['deck']));

        $playerHand = new Hand();
        $playerHand->setCards(self::cardsFromPrimitive($data['playerHand']));

        $bankHand = new Hand();
        $bankHand->setCards(self::cardsFromPrimitive($data['bankHand']));

        $game = new Game($deck, $playerHand, $bankHand);
        $game->restoreState(
            GameStatus::from($data['status']),
            $data['result'],
        );

        return $game;
    }

    /**
     * @param array<int, array{suit: string, rank: int}> $primitives
     * @return Card[]
     */
    private static function cardsFromPrimitive(array $primitives): array
    {
        return array_map(
            static fn (array $c): Card => new Card(Suit::from($c['suit']), Rank::from($c['rank'])),
            $primitives,
        );
    }

    private static function cardToPrimitive(Card $card): array
    {
        return [
            'suit' => $card->getSuit()->value,
            'rank' => $card->getRank()->value,
        ];
    }
}
