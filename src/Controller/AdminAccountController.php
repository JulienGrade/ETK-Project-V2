<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Entity\Role;
use App\Entity\User;
use App\Form\AdminRegistrationType;
use App\Form\RegistrationType;
use App\Service\PaginationService;
use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        // Permet de ne pas avoir à retaper son nom d'utilisateur
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de déconnecter de l'espace admin
     *
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout()
    {

    }

    /**
     * @Route("/admin/register", name="admin_account_register")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param StatsService $statsService
     * @return Response
     */
    public function register2(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder,StatsService $statsService)
    {
        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        $adminUser = new User();

        $form = $this->createForm(AdminRegistrationType::class, $adminUser);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($adminUser, $adminUser->getHash());
            $adminUser->setHash($hash);

            $adminRole  = new Role();
            $adminRole  ->setTitle('ROLE_ADMIN');
            $manager    ->persist($adminRole);
            $adminUser->addUserRole($adminRole);


            $manager->persist($adminUser);
            $manager->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/account/adminRegistration.html.twig', [
            'form'=> $form->createView(),
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
        ]);
    }

    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_list")
     * @param StatsService $statsService
     * @param $page int
     * @param PaginationService $pagination
     * @return Response
     */
    public function list2(StatsService $statsService, $page, PaginationService $pagination)
    {
        $pagination ->setEntityClass(User::class)
                    ->setPage($page);

        $stats          = $statsService->getStats();
        $activeStats    = $statsService->getActiveStats();
        $nowStats       = $statsService->getNowStats();
        $cityStats      = $statsService->getCityStats();
        $ageStats       = $statsService->getAgeStats();
        $genderStats    = $statsService->getGenderStats();

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('admin/account/adminList.html.twig', [
            'users' => $users,
            'stats'         => $stats,
            'activeStats'   => $activeStats,
            'nowStats'      => $nowStats,
            'cityStats'     => $cityStats,
            'ageStats'      => $ageStats,
            'genderStats'   => $genderStats,
            'current_menu'  => 'userList',
            'pagination'    => $pagination,
        ]);
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete", methods={"POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user)
    {

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_users_list');
    }


}