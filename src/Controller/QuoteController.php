<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class QuoteController
{
    #[Route('/api/quote', name: 'app_api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            'Kod som fungerar är bättre än kod som är perfekt.',
            'Det finns inga buggar, bara oväntade funktioner.',
            'Den bästa koden är den som någon annan redan skrivit.',
            'Ge upp aldrig, förutom när kaffemaskinen är trasig.',
        ];

        $selectedQuote = $quotes[array_rand($quotes)];

        $now = new \DateTimeImmutable();

        return new JsonResponse([
            'quote' => $selectedQuote,
            'date' => $now->format('Y-m-d'),
            'timestamp' => $now->getTimestamp(),
        ]);
    }
}