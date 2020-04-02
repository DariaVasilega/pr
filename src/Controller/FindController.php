<?php

namespace App\Controller;

use App\Entity\ORM\Search;
use App\Form\SearchType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/find", name="find", methods={"GET", "POST"})
 */
class FindController extends AbstractController
{
    /**
     * @Route("/", name="_users_groups", methods={"GET", "POST"})
     *
     * @param Request         $request
     * @param GroupRepository $groupRepository
     * @param UserRepository  $userRepository
     *
     * @return Response
     */
    public function index(Request $request, GroupRepository $groupRepository, UserRepository $userRepository): Response
    {
        $search = new Search();
        $users = [];
        $groups = [];
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $user = $userRepository->findOneBy(['id' => $request->get('userId')]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ('all' === $search->getString()) {
                $groups = $groupRepository->findAll();
                $users = $userRepository->findAll();
            } else {
                if (!empty(substr(strstr($search->getString(), ' '), 1))) {
                    $groups = $groupRepository->findByHalf(strstr($search->getString(), ' ', true),
                        substr(strstr($search->getString(), ' '), 1));
                    $users = $userRepository->findByHalf(strstr($search->getString(), ' ', true),
                        substr(strstr($search->getString(), ' '), 1));
                } else {
                    $groups = $groupRepository->findBy(['name' => $search->getString()]);
                    $users = $userRepository->findByDiff($search->getString());
                }
            }
        }

        return $this->render('profile/find.html.twig', [
            'thisUser' => $user,
            'page' => $request->get('page'),
            'users' => $users,
            'groups' => $groups,
            'string' => $search->getString(),
        ]);
    }
}
