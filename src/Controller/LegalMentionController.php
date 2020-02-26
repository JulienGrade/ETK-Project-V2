<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LegalMentionController extends AbstractController
{
    /**
     * @Route("/legal-mention", name="legal_mention")
     */
    public function index()
    {
        return $this->render('legal_mention/index.html.twig', [
            'controller_name' => 'LegalMentionController',
        ]);
    }
}
