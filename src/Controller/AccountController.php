<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\Role;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\EmailResetType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        // Permet de ne pas avoir à retaper son nom d'utilisateur
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     *
     */
    public function logout()
    {

    }

    /**
     * Permet d'afficher le formulaire d'inscritpion
     *
     * @Route("/register", name="account_register")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $email = (new Email())
                ->from('euratech.project@gmail.com')
                ->to($user->getEmail())
                ->subject('Bienvenue dans la communauté Euratech Kids')
                ->html('email/welcome.html.twig');

            $mailer->send($email);

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form'          => $form->createView(),
            'current_menu'  => 'account'
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur
     *
     * @Route("/compte", name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig', [
            'user'          => $this->getUser(),
            'current_menu'  => 'account'
        ]);
    }

    /**
     * Permet d'afficher et traiter le formulaire de modification de profil
     *
     * @Route("/compte/profil", name="account_profile")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        // Ici on récupère l'utilisateur connecté
        $user = $this->getUser();

        // On crée le formulaire en précisant le type ici account
        $form = $this->createForm(AccountType::class, $user);

        // On demande au formulaire de gérer la requete
        $form->handleRequest($request);

        // Ici on enregistre en bdd apres conditions
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de votre profil ont bien été enregistrées !"
            );
            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/profile.html.twig', [
            'form'          => $form->createView(),
            'current_menu'  => 'account'
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/compte/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $passwordUpdate = new PasswordUpdate();

        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // On crée le formulaire
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        // On demande au formulaire de gérer la requete
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Verifie que le oldPassword est identique au password de l'user ici $user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                // Ici on gère l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapez n'est pas votre mot de passe actuel !"));
            } else {
                // Ici on s'occupe d'enregistrer le nouveau mot de passe car conforme
                $newPassword = $passwordUpdate->getNewPassword();
                // Ici on encode
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form'          => $form->createView(),
            'current_menu'  => 'account'
        ]);
    }

    /**
     * Permet de gerer et afficher le formulaire afin de demander à ré initialiser son mot de passe par mail
     *
     * @Route("/compte/reset", name="account_reset")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param ObjectManager $manager
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function resetPassword(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, ObjectManager $manager)
    {
        // On créé le form afin de renseigner son mail
        $form= $this->createForm(EmailResetType::class);

        // On demande au formulaire de gérer la requete
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On récupère l'utilisateur
            $user = $manager->getRepository(User::class)->loadUserByUsername($form->getData()['email']);

            // aucun email associé à ce compte.
            if (!$user) {
                $request->getSession()->getFlashBag()->add('warning', "Cet email n'existe pas.");
                return $this->redirectToRoute("account_reset");
            }

            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $manager->flush();

            // on utilise le service Mailer

            $email = (new Email())
                ->from('euratech.project@gmail.com')
                ->to($user->getEmail())
                ->subject('Procédure de ré-initialisation de mot de passe')
                ->html('email/forgotPassword.html.twig');

            $mailer->send($email);
            $request->getSession()->getFlashBag()->add('success', "Un mail va vous être envoyé afin que vous puissiez renouveller votre mot de passe. Le lien que vous recevrez sera valide 24h.");

            return $this->redirectToRoute('account_login');
        }
        return $this->render('account/passwordReset.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
