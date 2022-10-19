<?php

namespace App\Controller;

use App\Repository\EventRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('core/index.html.twig', ['events' => $events]);
    }

}
