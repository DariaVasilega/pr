<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\Group;
use App\Entity\Media;
use App\Entity\ORM\CreateUser;
use App\Entity\ORM\Search;
use App\Entity\Post;
use App\Entity\Status;
use App\Form\CreateUserType;
use App\Form\GroupType;
use App\Form\MediaType;
use App\Form\PostType;
use App\Form\SearchType;
use App\Form\StatusType;
use App\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FormHelperService;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    public $maxPerPage = 2;

    /**
     * @Route("/{id}", name="profile_index")
     *
     * @param $id
     * @param PostRepository    $posts
     * @param UserRepository    $userRepository
     * @param FormHelperService $formHelperService
     *
     * @return Response
     */
    public function index($id, PostRepository $posts, UserRepository $userRepository, FormHelperService $formHelperService)
    {
        $someUser = $userRepository->slugOrId($id);
        $postPager = $this->setPager($posts->findCountByUserPager($someUser->getId()), $this->maxPerPage);
        $page = $postPager->getCurrentPage();
        if ($someUser->getStatus()) {
            $statusForm = $formHelperService->newForm(
                $someUser->getId(), $page, StatusType::class,
                $someUser->getStatus(), 'status_edit',
                $someUser->getStatus()->getId(), '');
        } else {
            $status = new Status();
            $statusForm = $formHelperService->newForm(
                $someUser->getId(),
                $page, StatusType::class,
                $status, 'status_new', '', '');
        }
        $userForm = $formHelperService->newForm(
            $someUser->getId(),
            $page, UserType::class,
            $someUser, 'user_edit',
            $someUser->getId(), '');
        $post = new Post();
        $postForm = $formHelperService->newForm(
            $someUser->getId(), $page, PostType::class,
            $post, 'post_new',
            $someUser->getId(), 'new_post');
        $group = new Group();
        $groupForm = $formHelperService->newForm(
            $someUser->getId(),
            $page, GroupType::class,
            $group, 'group_new', '', '');
        foreach ($postPager as $item) {
            $createdViewFormArray[] = $formHelperService->newForm(
                $someUser->getId(),
                $page, PostType::class,
                $item, 'post_edit',
                $item->getId(), '')->createView();
        }
        $avatar = new Media();
        $avatarForm = $formHelperService->newForm(
            $someUser->getId(),
            $page, MediaType::class,
            $avatar, 'user_edit_avatar',
            $someUser->getAvatar()->getId(), '');
        $search = new Search();
        $searchForm = $formHelperService->newForm(
            $someUser->getId(),
            $page, SearchType::class,
            $search, 'find_users_groups', '', '');
        $createdViewFormArray = false === isset($createdViewFormArray) ? '' : $createdViewFormArray;
        $deleteUserForm = $this->createFormBuilder($someUser)->getForm();
        $deleteGroupUserFrom = $this->createFormBuilder($group)->getForm();
        $friend = new Friend();
        $deleteUserFriendFrom = $this->createFormBuilder($friend)->getForm();

        return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'user' => $someUser,
                'thisPage' => $page,
                'statusForm' => $statusForm->createView(),
                'userForm' => $userForm->createView(),
                'postForm' => $postForm->createView(),
                'postFormArray' => $createdViewFormArray,
                'groupForm' => $groupForm->createView(),
                'pager' => $postPager,
                'searchForm' => $searchForm->createView(),
                'avatarForm' => $avatarForm->createView(),
                'deleteUserForm' => $deleteUserForm->createView(),
                'deleteGroupUserForm' => $deleteGroupUserFrom->createView(),
                'deleteUserFriendForm' => $deleteUserFriendFrom->createView(),
            ]);
    }

    /**
     * @Route("/new/user", name="profile_new_user")
     *
     * @param FormHelperService $formHelperService
     *
     * @return Response
     */
    public function newUser(FormHelperService $formHelperService)
    {
        $createUser = new CreateUser();
        $createUserForm = $formHelperService->newForm('', '', CreateUserType::class, $createUser, 'user_new', '', '');

        return $this->render('profile/new_user.html.twig', [
            'createUserForm' => $createUserForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{userId}/{page}/render", name="profile_render_groups")
     *
     * @param Group $group
     * @param $userId
     * @param $page
     * @param FormHelperService $formHelperService
     *
     * @return Response
     */
    public function renderGroups(Group $group, $userId, $page, FormHelperService $formHelperService)
    {
        $groupForm = $formHelperService->newForm($userId, $page, GroupType::class, $group, 'group_edit', $group->getId(), '')->createView();

        return $this->render('profile/group_edit.html.twig', [
            'group' => $groupForm,
            'page' => $page,
            'userId' => $userId,
        ]);
    }

    protected function setPager($repository, $maxPerPage)
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($repository));
        $pager->setMaxPerPage($maxPerPage);
        if (!empty($_GET['page'])) {
            $pager->setCurrentPage($_GET['page']);
        }

        return $pager;
    }
}
