<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api_index')]
    public function index(RouterInterface $router): Response
    {
        $apiRoutes = [];

        foreach ($router->getRouteCollection()->all() as $name => $route) {
            if (str_starts_with($route->getPath(), '/api') && 'app_api_index' !== $name) {
                $apiRoutes[] = [
                    'name' => $name,
                    'path' => $route->getPath(),
                    'methods' => $route->getMethods(),
                    'description' => $route->getOption('description') ?? '',
                ];
            }
        }

        return $this->render('api/index.html.twig', [
            'routes' => $apiRoutes,
        ]);
    }
}