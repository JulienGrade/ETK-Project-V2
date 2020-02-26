<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Children;
use App\Entity\Event;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BookingController extends AbstractController
{
    /**
     * Permet de réserver un evenement du planning
     *
     * @Route("/evenements/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @param Event $event
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function book(Event $event, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setEvent($event);
            $childrens=$booking->getChildrens();
            $tickets= count($childrens);

            $event->setSeats($event->getSeats() - $tickets);
            $manager->persist($event);

            foreach ($childrens as $children) {
                $children -> setBooking($booking);
                if($children->getAge() < $event->getAgeMin() || $children->getAge() > $event->getAgeMax()){
                    $this->addFlash(
                        'warning',
                        "L'âge de votre enfant doit correspondre à l'âge requit pour cet événement"
                    );
                }else{

                    $manager->persist($children);
                    $manager->persist($booking);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre réservation n°<strong>{$booking->getId()}</strong> a bien été enregistrée !"
                    );
                    return $this->redirectToRoute('booking_show', [
                        'id'        => $booking->getId(),
                        'withAlert' => true
                    ]);
                }

            }

        }

        return $this->render('booking/book.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition de réservation
     *
     * @Route("/reservation/{id}/edit", name="booking_edit")
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()",message="Cette réservation ne vous appartient pas,vous ne pouvez pas la modifier !")
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        $childrensOld=count($booking->getChildrens());

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $event = $booking->getEvent();

            $booking->setBooker($user);
            $childrens=$booking->getChildrens();

            $ticket= count($childrens);

            $event->setSeats($event->getSeats() - $ticket + $childrensOld);
            $manager->persist($event);


            foreach ($childrens as $children) {
                $children -> setBooking($booking);
                $manager->persist($children);
            }

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de votre réservation n°<strong>{$booking->getId()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('booking_show', [
                'id'        => $booking->getId(),
                'withAlert' => true
            ]);
        }

        return $this->render('booking/edit.html.twig', [
            'form'      => $form->createView(),
            'booking'   => $booking,
        ]);
    }

    /**
     * Permet à l'utilisateur d'annuler une réservation
     *
     * @Route("/reservation/{id}/delete", name="booking_delete")
     *
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()",message="Cette réservation ne vous appartient pas,vous ne pouvez pas la modifier !")
     * @param Booking $booking
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager)
    {
        $manager->remove($booking);

        $event = $booking->getEvent();
        $childrens=count($booking->getChildrens());
        $event->setSeats($event->getSeats()  + $childrens);
        $manager->persist($event);

        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée !"
        );
        return $this->redirectToRoute('account_index');
    }

    /**
     * Permet d'afficher une réservation
     *
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()",message="Cette réservation ne vous appartient pas,vous ne pouvez pas la modifier !")
     * @Route("/reservation/{id}", name="booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking)
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }

}
