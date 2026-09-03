<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DeckSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller-delen i MVC. Håller inte själv reda på spelregler
 * (det gör Card/Deck i Model), utan hämtar/sparar tillstånd
 * via DeckSessionManager och skickar data vidare till Twig-vyn.
 */
#[Route('/card/deck')]
final class DeckController extends AbstractController
{
    public function __construct(
        private readonly DeckSessionManager $deckSession,
    ) {
    }

    #[Route('', name: 'card_deck_index', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        $deck = $this->deckSession->load($session);

        return $this->render('deck/index.html.twig', [
            'cards' => $this->deckSession->sorted($deck->getCards()),
            'remaining' => $deck->count(),
            'lastDrawn' => $this->deckSession->loadLastDrawn($session),
        ]);
    }

    #[Route('/shuffle', name: 'card_deck_shuffle', methods: ['GET'])]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $this->deckSession->load($session);
        $deck->shuffle();
        $this->deckSession->save($session, $deck);

        return $this->render('deck/index.html.twig', [
            'cards' => $deck->getCards(),
            'remaining' => $deck->count(),
            'lastDrawn' => $this->deckSession->loadLastDrawn($session),
        ]);
    }

    #[Route('/draw', name: 'card_deck_draw', methods: ['GET'])]
    public function draw(SessionInterface $session): Response
    {
        return $this->drawAndRender($session, 1);
    }

    #[Route('/draw/{number}', name: 'card_deck_draw_number', requirements: ['number' => '\d+'], methods: ['GET'])]
    public function drawNumber(SessionInterface $session, int $number): Response
    {
        return $this->drawAndRender($session, $number);
    }
    #[Route('/deal/{players}/{cards}', name: 'card_deck_deal', requirements: ['players' => '\d+', 'cards' => '\d+'], methods: ['GET'])]
    public function deal(SessionInterface $session, int $players, int $cards): Response
    {
        $deck = $this->deckSession->load($session);

        $hands = [];
        for ($p = 1; $p <= $players; $p++) {
            $hands[$p] = $deck->drawMultiple($cards);
        }

        $this->deckSession->save($session, $deck);

        return $this->render('deck/deal.html.twig', [
            'hands' => $hands,
            'remaining' => $deck->count(),
        ]);
    }
    private function drawAndRender(SessionInterface $session, int $number): Response
    {
        $deck = $this->deckSession->load($session);
        $drawn = $deck->drawMultiple($number);
        $this->deckSession->save($session, $deck);

        if ([] !== $drawn) {
            $this->deckSession->recordLastDrawn($session, $drawn[array_key_last($drawn)]);
        }

        return $this->render('deck/draw.html.twig', [
            'drawn' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }
}