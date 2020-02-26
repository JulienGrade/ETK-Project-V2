<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/evenements", name="events_index")
     * @param EventRepository $eventRepo
     * @return Response
     */
    public function index(EventRepository $eventRepo)
    {
        $events= $eventRepo->findAll();

        return $this->render('planning/index.html.twig', [
            'events'        => $events,
            'current_menu'  => 'event'
        ]);
    }

    /**
     * Permet d'afficher un seul evenement de la page planning
     *
     * @Route("/evenements/{slug}", name="events_show", requirements={"slug": "[a-z0-9\-]*"})
     *
     * @param Event $event
     * @return Response
     */
    public function show(Event $event)
    {

        return $this->render('planning/show.html.twig', [
            'event'         => $event,
            'current_menu'  => 'event'
        ]);
    }


}
