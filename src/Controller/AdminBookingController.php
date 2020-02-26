<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingsRepository;
use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="admin_booking_index")
     * @param BookingsRepository $repo
     * @param StatsService $statsService
     * @return Response
     */
    public function index(BookingsRepository $repo, StatsService $statsService)
    {
        $bookings       = $statsService->getBookings();
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        return $this->render('admin/booking/index.html.twig', [
            'bookings'      => $bookings,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'booking',

        ]);
    }

    /**
     * Permet a l'admin d'afficher une réservation
     *
     * @Route("/admin/reservations/{id}", name="admin_bookings_show")
     *
     * @param Booking $booking
     * @param StatsService $statsService
     * @return Response
     */
    public function show(Booking $booking, StatsService $statsService){

        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        return $this->render('admin/booking/show.html.twig', [
            'booking'      => $booking,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu' => 'booking',

        ]);
    }
}
