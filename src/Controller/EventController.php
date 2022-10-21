<?php

namespace App\Controller;

// personnel
// ajout perso
use App\Entity\Event;
use App\Entity\Tag;
use App\Repository\EventRepository;

// built-in
// ajout perso
use DateInterval;
use DateTime;
use DateTimeImmutable;

// built-in
// ajout perso
use Doctrine\ORM\EntityManagerInterface;

// built-in
// ajout automatique à la creat° du fichier controller
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

	/**
	* @Route("/events/create", name="create_event")
	 */
	// on emprunte cette Route par la barre d'adresse du
	// nav

	// controller qui permet d'ajouter des objets de
	// type Event ds la table adéquate de la DB
	// methode appliquée: injection d'1 instance de la
	// class EntityManagerInterface
	// NE PAS OUBLIER LE use NECESSAIRE
	public function createEvent(EntityManagerInterface $entityManager): Response {

		// on crée un événement, ces données pourraient venir d'un formulaire
		$event = new Event();
		$event->setPicture('1.png');
		$event->setTitle('À la découverte du développement web');
		$event->setAddress('Sacré Coeur 3 VDN, Dakar');
		$event->setDescription('DU TEXTE PR LA DERSCIPTION');
		// la date de l'événement c'est dans 14 jours à 10h30
		$event->setEventDate((new DateTime('+14 days'))->setTime(10, 30));
		$event->setIsPublished(true); // on publie l'événement
		$event->setPublishedAt(new DateTimeImmutable());

		// on crée un deuxième événement qui ne sera pas publié pour l'instant
		$event2 = new Event();
		$event2->setTitle('Événement à venir, pas encore publique');

		// on ajoute quelques tags à l'événement
		$webTag = new Tag();
		$webTag->setLabel('web');
		$event->addTag($webTag);

		$codeTag = new Tag();
		$codeTag->setLabel('code');
		$event->addTag($codeTag);

		/* on confie l'objet $event au gestionnaire d'entités,
			l'objet n'est pas encore enregistré en base de données
		 	c'est la persistence de l'objet
		 */
		$entityManager->persist($event);

		// on confie aussi l'objet $event2 au gestionnaire d'entités
		// egalement persistence
		$entityManager->persist($event2);

		/* on exécute maintenant les 2 requêtes qui vont ajouter
		 * les objets $event et $event2 en base de données
		 * ici normalement, je pense qu'il faudrait avt de
		 * flusher, persister nos instances webTag et
		 * codeTag
		 * il y a 1 autre maniere que l'on a
		 * appliqué:
		 * rajouter 1 attribut "cascade" ds la class
		 * Event,
		 * au niveau de la relation entre Event et
		 * Tag
		 * pr dire a doctrine de gerer les 2 flush
		 * restants
		 * voir src/Entity/Event.php
		 * */
		$entityManager->flush();

		return new Response(
		    "Les événements {$event->getTitle()} et {$event2->getTitle()}
			ont bien été enregistrés."
		);
	}

	// Route dynamique qui permet l'affichage d'1 Event
	// spécifique ds sa page dédiée
	// NE PAS OUBLIER L'IMPORT
	#[Route('/events/{id}', name: 'show_event', requirements : ['id' => '\d+'])]
	public function show($id, EventRepository $eventRepository): Response {
		$event = $eventRepository->find($id);
		return $this->render('event/show.html.twig', ['event' => $event]);
	}

	// Route dynamique qui permet l'affichage d'1
	// category spécifique
	// on y accede par la barre d'adresse du nav
	// aucun template twig, codage en dur
	#[Route("/events/{category}", name:"list_events")]
	public function list($category = null): Response {
		$htmlMessage = "<h1>Liste des événements";
		if ($category) {
			$htmlMessage .= " ave la catégorie: ${category}";
		}
		$htmlMessage .= "</h1>";

		return new Response($htmlMessage);
	}

}
