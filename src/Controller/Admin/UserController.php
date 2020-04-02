<?php

namespace App\Controller\Admin;

use App\Entity\ORM\Verification;
use App\Form\VerificationUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $verification = new Verification();
        $form = $this->createForm(VerificationUserType::class, $verification);

        return $this->render('admin/index.html.twig', [
            'thisPage' => $request->get('page'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/set-verification", name="admin_set_verification", methods={"GET","POST"})
     */
    public function setVerification(Request $request)
    {
        $verification = new Verification();
        $form = $this->createForm(VerificationUserType::class, $verification, [
            'method' => 'POST',
            'action' => $this->redirectToRoute('admin_set_verification'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userArray = $verification->getUsers();
            if (count($userArray) > 0) {
                foreach ($userArray as $user) {
                    $user->setVerification($verification->getChoice());
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
