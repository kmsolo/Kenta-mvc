<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Card;
use App\Service\GameSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class GameApiController extends AbstractController
{
    public function __construct(
        private readonly GameSessionManager $gameSession,
    ) {
    }

    #[Route(
        '/api/game',
        name: 'api_game_index',
        methods: ['GET'],
        options: ['description' => 'Visar spelets aktuella ställning: spelarens och bankens händer, poäng, status och resultat.'],
    )]
    public function index(SessionInterface $session): JsonResponse
    {
        $game = $this->gameSession->load($session);

        return $this->json([
            'status' => $game->getStatus()->value,
            'result' => $game->getResult(),
            'player' => [
                'cards' => array_map(self::cardToJson(...), $game->getPlayerHand()->getCards()),
                'value' => $game->getPlayerHand()->getValue(),
                'bust' => $game->getPlayerHand()->isBust(),
            ],
            'bank' => [
                'cards' => array_map(self::cardToJson(...), $game->getBankHand()->getCards()),
                'value' => $game->getBankHand()->getValue(),
                'bust' => $game->getBankHand()->isBust(),
            ],
            'cardsRemainingInDeck' => $game->getDeck()->count(),
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
