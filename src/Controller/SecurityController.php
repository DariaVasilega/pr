<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\MailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param Request             $request
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $verification = $request->get('verification') or true;
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'verification' => $verification,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/verification", name="app_verification")
     *
     * @param UserRepository $userRepository
     * @param Request        $request
     * @param MailSender     $mailSender
     *
     * @return RedirectResponse
     */
    public function setVerification(UserRepository $userRepository, Request $request, MailSender $mailSender)
    {
        $user = $userRepository->findOneBy(['token' => $request->get('token')]);
        $user->setVerification(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $mailSender->sendMessage($user, 'emails/registration.html.twig', 'artem.lohvynenko@ekreative.com');

        return $this->redirectToRoute('app_login');
    }
}
