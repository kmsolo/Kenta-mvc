<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use App\Model\Deck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Ansvarar för att läsa och spara kortlekens tillstånd i sessionen.
 * Används av både webb-kontrollern och JSON API-kontrollern så att
 * de delar samma kortlek.
 */
final class DeckSessionManager
{
    private const string DECK_KEY = 'deck_cards';
    private const string LAST_DRAWN_KEY = 'deck_last_drawn';

    public function load(SessionInterface $session): Deck
    {
        $deck = new Deck();

        /** @var array<int, array{suit: string, rank: int}>|null $stored */
        $stored = $session->get(self::DECK_KEY);

        if (null === $stored) {
            $this->save($session, $deck);

            return $deck;
        }

        $cards = array_map(
            static fn (array $c): Card => new Card(
                Suit::from($c['suit']),
                Rank::from($c['rank']),
            ),
            $stored,
        );

        $deck->setCards($cards);

        return $deck;
    }

    public function save(SessionInterface $session, Deck $deck): void
    {
        $session->set(self::DECK_KEY, array_map(
            self::cardToPrimitive(...),
            $deck->getCards(),
        ));
    }

    public function recordLastDrawn(SessionInterface $session, Card $card): void
    {
        $session->set(self::LAST_DRAWN_KEY, self::cardToPrimitive($card));
    }

    public function loadLastDrawn(SessionInterface $session): ?Card
    {
        /** @var array{suit: string, rank: int}|null $data */
        $data = $session->get(self::LAST_DRAWN_KEY);

        if (null === $data) {
            return null;
        }

        return new Card(Suit::from($data['suit']), Rank::from($data['rank']));
    }

    /**
     * Returnerar korten sorterade per färg (enumordning) och värde,
     * utan att ändra ordningen som ligger sparad i sessionen.
     *
     * @param Card[] $cards
     * @return Card[]
     */
    public function sorted(array $cards): array
    {
        $suitOrder = array_flip(array_map(
            static fn (Suit $s): string => $s->value,
            Suit::cases(),
        ));

        usort($cards, static function (Card $a, Card $b) use ($suitOrder): int {
            $suitCompare = $suitOrder[$a->getSuit()->value] <=> $suitOrder[$b->getSuit()->value];

            return 0 !== $suitCompare ? $suitCompare : $a->getRank()->value <=> $b->getRank()->value;
        });

        return $cards;
    }

    private static function cardToPrimitive(Card $card): array
    {
        return [
            'suit' => $card->getSuit()->value,
            'rank' => $card->getRank()->value,
        ];
    }
}