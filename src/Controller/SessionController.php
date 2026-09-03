<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class SessionController extends AbstractController
{
    #[Route('/session', name: 'session_index', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        return $this->render('session/index.html.twig', [
            'sessionData' => $session->all(),
        ]);
    }

    #[Route('/session/delete', name: 'session_delete', methods: ['GET'])]
    public function delete(SessionInterface $session): RedirectResponse
    {
        $session->clear();
        $this->addFlash('success', 'Nu är sessionen raderad.');

        return $this->redirectToRoute('session_index');
    }
}