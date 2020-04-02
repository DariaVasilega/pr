<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Form\FriendType;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/friends")
 */
class FriendController extends AbstractController
{
    /**
     * @Route("/", name="friend_index", methods={"GET"})
     *
     * @param FriendRepository $friendRepository
     *
     * @return Response
     */
    public function index(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/index.html.twig', [
            'friends' => $friendRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="friend_new", methods={"GET","POST"})
     *
     * @param UserRepository $userRepository
     * @param Request        $request
     *
     * @return Response
     */
    public function new(UserRepository $userRepository, Request $request): Response
    {
        if ($userRepository->findOneBy(['id' => $request->get('userId')]) === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            if ($request->get('userId') !== $request->get('friendId')) {
                $user = $userRepository->findOneBy(['id' => $request->get('userId')]);
                $friend = $userRepository->findOneBy(['id' => $request->get('friendId')]);
                $user_friends = $user->getFriends();
                foreach ($user_friends as $item) {
                    if ($item->getFriend()->getId() === $friend->getId()) {
                        return $this->redirectToRoute('profile_index',
                            ['id' => $user->getId(), 'page' => $request->get('page')]);
                    }
                }
                $submit = new Friend();
                $submit->setUser($user);
                $submit->setFriend($friend);
                $submit->setCreated(date_create());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($submit);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $request->get('userId'), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="friend_show", methods={"GET"})
     *
     * @param Friend $friend
     *
     * @return Response
     */
    public function show(Friend $friend): Response
    {
        return $this->render('friend/show.html.twig', [
            'friend' => $friend,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="friend_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Friend  $friend
     *
     * @return Response
     */
    public function edit(Request $request, Friend $friend): Response
    {
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('friend_index');
        }

        return $this->render('friend/edit.html.twig', [
            'friend' => $friend,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="friend_delete", methods={"GET","DELETE"})
     *
     * @param Request $request
     * @param Friend  $friend
     *
     * @return Response
     */
    public function delete(Request $request, Friend $friend): Response
    {
        if ($friend->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $form = $this->createFormBuilder(null, [
                'method' => 'DELETE',
                'action' => $this->generateUrl('friend_delete', [
                    'id' => $friend->getId(),
                ]),
            ])->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($friend);
                $entityManager->flush();
            }
        }
        return $this->redirect($request->headers->get('referer'));
    }
}
