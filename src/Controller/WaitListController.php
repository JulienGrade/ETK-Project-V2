<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\WaitList;
use App\Form\WaitListType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WaitListController extends AbstractController
{
    /**
     * Permet de se placer en liste d'attente
     *
     * @Route("/evenements/{slug}/list", name="waitlist_create")
     * @IsGranted("ROLE_USER")
     * @param Event $event
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(Event $event, Request $request, ObjectManager $manager)
    {
        $waitList = new WaitList();

        $form = $this->createForm(WaitListType::class, $waitList);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();

            $waitList->setIsWaiting(true)
                     ->setUser($user)
                     ->setEvent($event);

            $manager->persist($waitList);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre mise en liste d'attente n°<strong>{$waitList->getId()}</strong> a bien été enregistrée !"
            );
            $this->redirectToRoute('events_index');

        }
        return $this->render('waitlist/index.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);



        return $this->redirectToRoute('events_index');
    }
}
