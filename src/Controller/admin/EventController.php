<?php

namespace App\Controller\admin;

use App\Entity\Canyon;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/event", name="admin_event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function findCanyonById(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, Canyon $canyon): Response
    {
        $event = new Event();
        $event->setCanyon($canyon);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Instancie un manager et on lui demande de "discuter" avec Doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Dis à mon manager de faire persister mon nouvel event dans le temps
            // Prépare ma modification à intégrer dans la bdd
            $entityManager->persist($event);
            // Flush lance la requête sql qui permet d'inscrire ces nouvelles modifications dans la bdd
            $entityManager->flush();

            // Ajouter dans generation de path admin event new l'id du canyon en parameter {{ path ('admin_event_new', {'id':canyon.id})}}
            // Redirige vers le listing des évènements pat canyon avec le canyon ajouté grâce à l'id donné
            return $this->redirectToRoute('admin_canyon_show_events', [
                'id' => $event->getCanyon()->getId()
            ]);
        }

        return $this->render('admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('admin_event_index');
            // Redirige vers le listing des évènements par canyon avec le canyon modifié grâce à l'id donné
             return $this->redirectToRoute('admin_canyon_show_events', [
                'id' => $event->getCanyon()->getId()
            ]);
        }

        return $this->render('admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        // Redirige vers le listing des évènements par canyon avec le canyon supprimé grâce à l'id donné
        return $this->redirectToRoute('admin_canyon_show_events', [
            'id' => $event->getCanyon()->getId()
        ]);
    }
}
