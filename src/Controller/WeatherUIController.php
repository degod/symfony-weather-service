<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WeatherUIController extends AbstractController
{
    #[Route('/', name: 'weather')]
    public function index(): Response
    {
        return $this->render('weather/index.html.twig');
    }
}
