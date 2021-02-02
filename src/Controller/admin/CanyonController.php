<?php

namespace App\Controller\admin;

use App\Entity\Canyon;
use App\Form\CanyonType;
use App\Repository\CanyonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/canyon", name="admin_canyon_")
 */
class CanyonController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CanyonRepository $canyonRepository): Response
    {
        return $this->render('admin/canyon/index.html.twig', [
            'canyons' => $canyonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $canyon = new Canyon();
        $form = $this->createForm(CanyonType::class, $canyon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($canyon);
            $entityManager->flush();

            return $this->redirectToRoute('admin_canyon_index');
        }

        return $this->render('admin/canyon/new.html.twig', [
            'canyon' => $canyon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Canyon $canyon): Response
    {
        return $this->render('admin/canyon/show.html.twig', [
            'canyon' => $canyon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Canyon $canyon): Response
    {
        $form = $this->createForm(CanyonType::class, $canyon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_canyon_index');
        }

        return $this->render('admin/canyon/edit.html.twig', [
            'canyon' => $canyon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Canyon $canyon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$canyon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($canyon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_canyon_index');
    }
}