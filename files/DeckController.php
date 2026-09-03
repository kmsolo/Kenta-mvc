<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\Rank;
use App\Enum\Suit;
use App\Model\Card;
use App\Model\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller-delen i MVC. Håller inte själv reda på spelregler
 * (det gör Card/Deck i Model), utan hämtar/sparar tillstånd
 * och skickar data vidare till Twig-vyn.
 */
final class DeckController extends AbstractController
{
    private const string SESSION_KEY = 'deck_cards';

    #[Route('/deck', name: 'deck_index', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        $deck = $this->loadDeck($session);

        return $this->render('deck/index.html.twig', [
            'cards' => $deck->getCards(),
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/deck/shuffle', name: 'deck_shuffle', methods: ['POST'])]
    public function shuffle(SessionInterface $session): RedirectResponse
    {
        $deck = $this->loadDeck($session);
        $deck->shuffle();
        $this->saveDeck($session, $deck);

        $this->addFlash('success', 'Kortleken blandades.');

        return $this->redirectToRoute('deck_index');
    }

    #[Route('/deck/draw', name: 'deck_draw', methods: ['POST'])]
    public function draw(SessionInterface $session): RedirectResponse
    {
        $deck = $this->loadDeck($session);
        $card = $deck->draw();
        $this->saveDeck($session, $deck);

        if (null !== $card) {
            $this->addFlash('success', sprintf('Du drog: %s', $card->getLabel()));
        } else {
            $this->addFlash('warning', 'Kortleken är tom.');
        }

        return $this->redirectToRoute('deck_index');
    }

    #[Route('/deck/reset', name: 'deck_reset', methods: ['POST'])]
    public function resetDeck(SessionInterface $session): RedirectResponse
    {
        $deck = new Deck();
        $this->saveDeck($session, $deck);

        $this->addFlash('success', 'Kortleken återställdes med 52 kort.');

        return $this->redirectToRoute('deck_index');
    }

    /**
     * Läser upp kortlekens tillstånd ur sessionen, eller skapar en
     * ny fräsch kortlek första gången.
     */
    private function loadDeck(SessionInterface $session): Deck
    {
        $deck = new Deck();

        /** @var array<int, array{suit: string, rank: int}>|null $stored */
        $stored = $session->get(self::SESSION_KEY);

        if (null === $stored) {
            $this->saveDeck($session, $deck);

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

    private function saveDeck(SessionInterface $session, Deck $deck): void
    {
        $serialized = array_map(
            static fn (Card $card): array => [
                'suit' => $card->getSuit()->value,
                'rank' => $card->getRank()->value,
            ],
            $deck->getCards(),
        );

        $session->set(self::SESSION_KEY, $serialized);
    }
}
