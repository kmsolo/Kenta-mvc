<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GameSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Tunn kontroller för kortspelet 21. All spellogik ligger i Model\Game
 * (och Model\Hand), sessionen sköts av GameSessionManager - kontrollern
 * gör bara: läs tillstånd, anropa en metod, spara tillstånd, rendera.
 */
#[Route('/game')]
final class GameController extends AbstractController
{
    public function __construct(
        private readonly GameSessionManager $gameSession,
    ) {
    }

    #[Route('', name: 'game_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('game/index.html.twig');
    }

    #[Route('/doc', name: 'game_doc', methods: ['GET'])]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/play', name: 'game_play', methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {
        $game = $this->gameSession->load($session);

        return $this->render('game/play.html.twig', ['game' => $game]);
    }

    #[Route('/hit', name: 'game_hit', methods: ['GET'])]
    public function hit(SessionInterface $session): Response
    {
        $game = $this->gameSession->load($session);
        $game->hit();
        $this->gameSession->save($session, $game);

        return $this->render('game/play.html.twig', ['game' => $game]);
    }

    #[Route('/stand', name: 'game_stand', methods: ['GET'])]
    public function stand(SessionInterface $session): Response
    {
        $game = $this->gameSession->load($session);
        $game->stand();
        $this->gameSession->save($session, $game);

        return $this->render('game/play.html.twig', ['game' => $game]);
    }

    #[Route('/new', name: 'game_new', methods: ['GET'])]
    public function newRound(SessionInterface $session): Response
    {
        $game = $this->gameSession->startNewRound($session);

        return $this->render('game/play.html.twig', ['game' => $game]);
    }
}
