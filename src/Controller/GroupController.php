<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Security\GroupVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     *
     * @param GroupRepository $groupRepository
     *
     * @return Response
     */
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     *
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        if ($userRepository->findOneBy(['id' => $request->get('userId')]) === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $group = new Group();
            $form = $this->createForm(GroupType::class, $group);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $group->addUser($userRepository->findOneBy(['id' => $request->get('userId')]));
                $group->setOwner($userRepository->findOneBy(['id' => $request->get('userId')]));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($group);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $request->get('userId'), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     *
     * @param Group $group
     *
     * @return Response
     */
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     */
    public function edit(Request $request, Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(GroupVoter::EDIT, $group);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_index',
                ['id' => $request->get('userId'), 'page' => $request->get('page')]);
        }

        return $this->render('profile/group_edit.html.twig', [
            'group' => $form->createView(),
            'userId' => $request->get('userId'),
            'page' => $request->get('page'),
        ]);
    }

    /**
     * @Route("/{id}/group_delete", name="group_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     */
    public function delete(Request $request, Group $group): Response
    {
        $form = $this->createFormBuilder(null, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('group_delete', [
                'id' => $group->getId(),
            ]),
        ])->getForm();

        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(GroupVoter::EDIT, $group);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/remove_user", name="group_remove_user", methods={"GET","POST"})
     *
     * @param Request        $request
     * @param Group          $group
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function remove_user(Request $request, Group $group, UserRepository $userRepository): Response
    {
        if ($userRepository->findOneBy(['id' => $request->get('userId')]) === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $group->removeUser($userRepository->findOneBy(['id' => $request->get('userId')]));
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
