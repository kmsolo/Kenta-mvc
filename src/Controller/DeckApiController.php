<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Service\DeckSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class DeckApiController extends AbstractController
{
    public function __construct(
        private readonly DeckSessionManager $deckSession,
    ) {
    }

    #[Route(
        '/api/deck',
        name: 'api_deck_index',
        methods: ['GET'],
        options: ['description' => 'Hämtar hela kortleken, sorterad per färg och värde.'],
    )]
    public function index(SessionInterface $session): JsonResponse
    {
        $deck = $this->deckSession->load($session);
        $sorted = $this->deckSession->sorted($deck->getCards());

        return $this->json([
            'cards' => array_map(self::cardToJson(...), $sorted),
            'remaining' => $deck->count(),
        ]);
    }

    #[Route(
        '/api/deck/shuffle',
        name: 'api_deck_shuffle',
        methods: ['POST'],
        options: ['description' => 'Blandar kortleken och sparar den blandade ordningen i sessionen.'],
    )]
    public function shuffle(SessionInterface $session): JsonResponse
    {
        $deck = $this->deckSession->load($session);
        $deck->shuffle();
        $this->deckSession->save($session, $deck);

        return $this->json([
            'cards' => array_map(self::cardToJson(...), $deck->getCards()),
            'remaining' => $deck->count(),
        ]);
    }

    #[Route(
        '/api/deck/draw',
        name: 'api_deck_draw',
        methods: ['POST'],
        options: ['description' => 'Drar ett kort från kortleken och returnerar det.'],
    )]
    public function draw(SessionInterface $session): JsonResponse
    {
        return $this->drawCards($session, 1);
    }

    #[Route(
        '/api/deck/draw/{number}',
        name: 'api_deck_draw_number',
        requirements: ['number' => '\d+'],
        methods: ['POST'],
        options: ['description' => 'Drar :number kort från kortleken och returnerar dem.'],
    )]
    public function drawNumber(SessionInterface $session, int $number): JsonResponse
    {
        return $this->drawCards($session, $number);
    }

    #[Route(
        '/api/deck/deal/{players}/{cards}',
        name: 'api_deck_deal',
        requirements: ['players' => '\d+', 'cards' => '\d+'],
        methods: ['POST'],
        options: ['description' => 'Delar ut :cards kort vardera till :players spelare.'],
    )]
    public function deal(SessionInterface $session, int $players, int $cards): JsonResponse
    {
        $deck = $this->deckSession->load($session);

        $hands = [];
        for ($p = 1; $p <= $players; $p++) {
            $hands['player' . $p] = array_map(
                self::cardToJson(...),
                $deck->drawMultiple($cards),
            );
        }

        $this->deckSession->save($session, $deck);

        return $this->json([
            'hands' => $hands,
            'remaining' => $deck->count(),
        ]);
    }

    private function drawCards(SessionInterface $session, int $number): JsonResponse
    {
        $deck = $this->deckSession->load($session);
        $drawn = $deck->drawMultiple($number);
        $this->deckSession->save($session, $deck);

        if ([] !== $drawn) {
            $this->deckSession->recordLastDrawn($session, $drawn[array_key_last($drawn)]);
        }

        return $this->json([
            'drawn' => array_map(self::cardToJson(...), $drawn),
            'remaining' => $deck->count(),
        ]);
    }

    private static function cardToJson(Card $card): array
    {
        return [
            'suit' => $card->getSuit()->value,
            'rank' => $card->getRank()->value,
            'label' => $card->getLabel(),
        ];
    }
}