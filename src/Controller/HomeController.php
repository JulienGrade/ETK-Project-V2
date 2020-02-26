<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use DrewM\MailChimp\MailChimp;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
//    /**
//     * @Route("/", name="homepage")
//     *
//     * @return Response
//     */
//    public function home()
//    {
//        return $this->render('home.html.twig');
//    }


    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function home(Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $MailChimp = new MailChimp('b38b2f8c87a2e2ea6d8ceb99905eb782-us4');
            $MailChimp->verify_ssl = false;

//            dump('salut');
//            dump($MailChimp->get('lists'));

            $list_id = 'e351e67e77';

            $MailChimp->post("lists/$list_id/members", [
                'email_address' => $form->get('email')->getData(),
                'status'        => 'subscribed',
            ]);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('home.html.twig',[
            'form'          => $form->createView(),
            'current_menu'  => 'homepage'
        ]);
    }


//    public function newsletter(Request $request)
//    {
//        $newsletter = new Newsletter();
//        $form = $this->createForm(NewsletterType::class, $newsletter);
//        $form->handleRequest($request);
//
//        $MailChimp = new MailChimp('b38b2f8c87a2e2ea6d8ceb99905eb782-us4');
//        $result = $MailChimp->get('lists');
//
//        $list_id = 'b1234346';
//
//        $result = $MailChimp->post("lists/$list_id/members", [
//            'email_address' => 'davy@example.com',
//            'status'        => 'subscribed',
//        ]);
//    }
}
