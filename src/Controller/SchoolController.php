<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    /**
     * @Route("/ecole", name="school_index")
     */
    public function index()
    {
        return $this->render('school/index.html.twig', [
            'current_menu' => 'school',
        ]);
    }
}
