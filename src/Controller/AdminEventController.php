<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Service\PaginationService;
use App\Service\StatsService;
use App\Service\Uploader;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventController extends AbstractController
{
    /**
     * @Route("/admin/evenements/{page<\d+>?1}", name="admin_events_index")
     * @param EventRepository $eventRepo
     * @param StatsService $statsService
     * @param $page int
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(EventRepository $eventRepo, StatsService $statsService, $page, PaginationService $pagination)
    {
        $pagination ->setEntityClass(Event::class)
            ->setPage($page);

        $events         = $statsService->getEvents();
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        return $this->render('admin/planning/index.html.twig', [
            'events'        => $events,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'event',
            'pagination'    => $pagination,
        ]);
    }

    /**
     * Permet de créer un événement dans le planning
     *
     * @Route("/admin/evenements/new", name="admin_events_create")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Uploader $uploader
     * @param StatsService $statsService
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager, Uploader $uploader, StatsService $statsService)
    {
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // On réalise l'upload de l'image
            if($image = $form->get('picture')->getData()) {
                $fileName = $uploader->upload($image);
                // On met à jour l'entité
                $event->setPicture($fileName);
            }

            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'événement <strong>{$event->getTitle()}</strong> a bien été enregistré ! "
            );

            return $this->redirectToRoute('admin_events_show', [
                'slug' => $event->getSlug()
            ]);
        }

        return $this->render('admin/planning/new.html.twig', [
            'form' => $form->createView(),
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'createevent',
        ]);
    }

    /**
     * Permet d'afficher un seul evenement de la page planning
     *
     * @Route("/admin/evenements/{slug}", name="admin_events_show")
     *
     * @param Event $event
     * @param StatsService $statsService
     * @return Response
     */
    public function show(Event $event, StatsService $statsService)
    {
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        return $this->render('admin/planning/show.html.twig', [
            'event'         => $event,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu' => 'event',
        ]);
    }

    /**
     * Permet d'éditer un evenement de la page planning
     *
     * @Route("/admin/evenements/{id}/edit", name="admin_events_edit")
     * @param Event $event
     * @param Request $request
     * @param ObjectManager $manager
     * @param Uploader $uploader
     * @param StatsService $statsService
     * @return Response
     */
    public function edit(Event $event, Request $request, ObjectManager $manager, Uploader $uploader, StatsService $statsService)
    {
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            // Upload de l'image
            if($image = $form->get('picture')->getData()){
                // Supprimer l'image existante
                if($event->getPicture()) {
                    $uploader->remove($event->getPicture());
                }
                $fileName = $uploader->upload($image);
                // On met à jour l'entité
                $event->setPicture($fileName);
            }

            $this->addFlash(
                'success',
                "L'événement <strong>{$event->getTitle()}</strong> a bien été modifié !"
            );
            $manager->persist($event);
            $manager->flush();

            // Redirection
            return $this->redirectToRoute('admin_events_index');
        }

        return $this->render('admin/planning/edit.html.twig', [
            'event'         => $event,
            'form'          => $form->createView(),
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'event',
        ]);
    }

    /**
     * Permet d'effacer un événement du planning
     *
     * @Route("/admin/evenements/delete/{id}", name="admin_events_delete", methods={"POST"})
     * @param Request $request
     * @param Event $event
     * @param EntityManagerInterface $entityManager
     * @param Uploader $uploader
     * @return RedirectResponse
     */
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager, Uploader $uploader)
    {
        $this->denyAccessUnlessGranted('edit', $event);
        // On vérifie la validité du token CSRF et on se protège des failles CSRF
        if($this->isCsrfTokenValid('delete', $request->get('token'))) {
            if($event->getPicture()) {
                $uploader->remove($event->getPicture());
            }

            $entityManager->remove($event);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_events_index');
    }

}
