<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param ObjectManager $manager
     * @param StatsService $statsService
     * @return Response
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {
        $stats                  = $statsService->getStats();
        $activeStats            = $statsService->getActiveStats();
        $nowStats               = $statsService->getNowStats();
        $cityStats              = $statsService->getCityStats();
        $ageStats               = $statsService->getAgeStats();
        $genderStats            = $statsService->getGenderStats();
        $eventTypeStats         = $statsService->getEventTypeStats();
        $monthlyStats           = $statsService->getMonthlyStats();
        $newUser                = $statsService->getNewUser();
        $waitList               = $statsService->getWaitList();
        $monthlyWaitList        = $statsService->getMonthlyWaitList();


        return $this->render('admin/dashboard/index.html.twig', [
            'stats'                 => $stats,
            'activeStats'           => $activeStats,
            'nowStats'              => $nowStats,
            'cityStats'             => $cityStats,
            'ageStats'              => $ageStats,
            'genderStats'           => $genderStats,
            'eventTypeStats'        => $eventTypeStats,
            'monthlyStats'          => $monthlyStats,
            'newUser'               => $newUser,
            'waitList'              => $waitList,
            'monthlyWaitList'       => $monthlyWaitList,
            'current_menu'          => 'dashboard',
        ]);
    }
}
