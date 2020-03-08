<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use ReCaptcha\ReCaptcha;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);


        $recaptcha = new ReCaptcha('6LfVq98UAAAAAEvMMFu8uJCqB3Yuvh-UBGGzr3XT');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        if (!$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $this->addFlash('alert',"Vous n\'avez pas répondu correctement");
        }else{
            // Everything works good ;) your contact has been saved.
            $form->handleRequest($request);
        }


        if ($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('success', 'Votre message a bien été envoyé !');


            $email = (new TemplatedEmail())
                ->from('contact@euratechkids.com')
                ->to('contact@euratechkids.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Vous avez une nouvelle demande de contact')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'contact' => $contact,
                ]);

            $mailer->send($email);


            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }
}
