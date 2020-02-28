<?php

namespace App\Controller;


use App\Entity\WaitList;
use App\Repository\WaitListRepository;
use App\Service\PaginationService;
use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminWaitListController extends AbstractController
{
    /**
     * @Route("/admin/attente/{page<\d+>?1}", name="admin_waitlist_index")
     * @param WaitListRepository $waitListRepo
     * @param StatsService $statsService
     * @param $page int
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(WaitListRepository $waitListRepo, StatsService $statsService, $page, PaginationService $pagination)
    {
        $pagination ->setEntityClass(WaitList::class)
            ->setPage($page);

        $stats              = $statsService->getStats();
        $activeStats        = $statsService->getActiveStats();
        $nowStats           = $statsService->getNowStats();
        $cityStats          = $statsService->getCityStats();
        $ageStats           = $statsService->getAgeStats();
        $genderStats        = $statsService->getGenderStats();
        $waitList           = $statsService->getWaitList();

        return $this->render('admin/waitlist/index.html.twig', [
            'stats'             => $stats,
            'activeStats'       => $activeStats,
            'nowStats'          => $nowStats,
            'cityStats'         => $cityStats,
            'ageStats'          => $ageStats,
            'genderStats'       => $genderStats,
            'waitList'          => $waitList,
            'current_menu'      => 'waitlist',
            'pagination'    => $pagination,
        ]);
    }

    /**
     * Permet de supprimer une réservation par un administrateur
     *
     * @Route("/admin/attente/{id}/delete", name="admin_waitlist_delete")
     * @param WaitList $waitList
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(WaitList $waitList, ObjectManager $manager)
    {
        $manager->remove($waitList);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );

        return $this->redirectToRoute('admin_waitlist_index');
    }
}
