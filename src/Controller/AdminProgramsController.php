<?php

namespace App\Controller;

use App\Entity\Programs;
use App\Form\ProgramsType;
use App\Repository\ProgramsRepository;
use App\Service\StatsService;
use App\Service\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProgramsController extends AbstractController
{

    /**
     * Permet d'afficher la liste des prgrammes dans la partie admin
     *
     * @Route("/admin/programmes", name="admin_programs_index")
     *
     * @param ProgramsRepository $programsRepository
     * @param StatsService $statsService
     * @return Response
     */
    public function index(ProgramsRepository $programsRepository, StatsService $statsService)
    {
        $programs       = $statsService->getPrograms();
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        return $this->render('admin/programs/index.html.twig', [
            'programs'      => $programs,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'program',
        ]);
    }

    /**
     * @Route("/admin/programmes/new", name="admin_programs_create", methods={"GET","POST"})
     * @param Request $request
     * @param Uploader $uploader
     * @param StatsService $statsService
     * @return Response
     */
    public function new(Request $request, Uploader $uploader, StatsService $statsService): Response
    {
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();
        $program = new Programs();
        $form = $this->createForm(ProgramsType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /**@var UploadedFile $image*/
            //On fait l'upload de l'image
            if ($image = $form->get('image')->getData()) {
                $fileName = $uploader->upload($image);
                //mettre à jour l'entité
                $program->setImage($fileName);
            }

            /**@var UploadedFile $image*/
            //On fait l'upload de l'image
            if ($image = $form->get('illustration')->getData()) {
                $fileName = $uploader->upload($image);
                //mettre à jour l'entité
                $program->setIllustration($fileName);
            }



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('admin_programs_index');
        }

        return $this->render('admin/programs/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'createprogram',
        ]);
    }

    /**
     * @Route("/admin/programmes/{id}/edit", name="admin_programs_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Programs $program
     * @return Response
     */
    public function edit(Request $request, Programs $program): Response
    {
        $form = $this->createForm(ProgramsType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_programs_index');
        }

        return $this->render('admin/programs/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/programmes/{id}", name="admin_programs_delete", methods={"DELETE"})
     * @param Request $request
     * @param Programs $program
     * @return Response
     */
    public function delete(Request $request, Programs $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_programs_index');
    }

}