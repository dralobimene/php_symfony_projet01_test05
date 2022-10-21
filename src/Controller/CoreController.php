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
	    // PR SELECTIONNER LES AFFICHAGES:
	    // 01: utilisat° de la fct° findAll()
	    // de la class
	    // src/Repository/EventRepository.php
	    // affiche ts les Events
	    // 02: on la remplace par la methode
	    // findBy qui prend 4 parametres:
	    // - tab associatif qui represente la condit°
	    //	= à la clause WHERE
	    // - tab associatif = ORDER BY
	    // - definit le nbre d'elts a recuperer = LIMIT
	    // - indice a partir duqel on souhaite lire
	    //	= à OFFSET
	    // 01:
	    // $events = $eventRepository->findAll();
	    // 02:
	    $events =
		    $eventRepository->findBy(['isPublished' => true],
		    				['eventDate' => 'ASC'],
		    				12,
		    				0);
	    return $this->render('core/index.html.twig', ['events' => $events]);
    }

}
