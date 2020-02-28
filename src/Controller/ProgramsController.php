<?php

namespace App\Controller;

use App\Entity\Programs;
use App\Repository\ProgramsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgramsController extends AbstractController
{
    /**
     * @Route("/program", name="programs_index", methods={"GET"})
     * @param ProgramsRepository $programsRepository
     * @return Response
     */
    public function index(ProgramsRepository $programsRepository): Response
    {
        return $this->render('programs/index.html.twig', [
            'programs'      => $programsRepository->findAll(),
            'current_menu'  => 'program'
        ]);
    }

    /**
     * @Route("/programme/{id}", name="programs_show", methods={"GET"})
     * @param Programs $program
     * @return Response
     */
    public function show(Programs $program): Response
    {
        return $this->render('programs/show.html.twig', [
            'program'       => $program,
            'current_menu'  => 'program'
        ]);
    }

}
