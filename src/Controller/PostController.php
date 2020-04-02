<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Security\PostVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     *
     * @param PostRepository $postRepository
     *
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     *
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        if ($userRepository->findOneBy(['id' => $request->get('userId')]) === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            $post->setUser($userRepository->findOneBy(['id' => $request->get('userId')]));
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($post);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('profile_index',
            ['id' => (int) $request->get('userId'), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     *
     * @param Post $post
     *
     * @return Response
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return Response
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $post->getUser()->getId(), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"POST","DELETE"})
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('profile_index',
            ['id' => $post->getUser()->getId(), 'page' => $request->get('page')]);
    }
}
