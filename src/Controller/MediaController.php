<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media")
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/", name="media_index", methods={"GET"})
     *
     * @param MediaRepository $mediaRepository
     *
     * @return Response
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('media/index.html.twig', [
            'media' => $mediaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="media_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medium);
            $entityManager->flush();

            return $this->redirectToRoute('media_index');
        }

        return $this->render('media/new.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="media_show", methods={"GET"})
     *
     * @param Media $medium
     *
     * @return Response
     */
    public function show(Media $medium): Response
    {
        return $this->render('media/show.html.twig', [
            'medium' => $medium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="media_edit", methods={"GET","POST"})
     *
     * @param Request        $request
     * @param Media          $medium
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function edit(Request $request, Media $medium, UserRepository $userRepository): Response
    {
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            echo 'ok';
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $userRepository->findOneBy(['avatar' => $medium->getId()])->getId(), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="media_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Media   $medium
     *
     * @return Response
     */
    public function delete(Request $request, Media $medium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('media_index');
    }
}
