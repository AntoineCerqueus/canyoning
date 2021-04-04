<?php

namespace App\Controller\front;

use App\Entity\Canyon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CanyonRepository;

/**
 * @Route("/front/canyon", name="canyons_")
 */
class CanyonController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(CanyonRepository $canyonRepository): Response
    {
        // Récupération de tout les canyons dans le but de les afficher avec une brève présentation
        return $this->render('front/canyon/index.html.twig', [
            'canyons' => $canyonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/single/{id}", name="single", methods={"GET"})
     */
    public function single(Canyon $canyon):Response
    {
        // Récupération des évènements créés
        $events = $canyon->getEvents();
        // Initialisation d'un tableau vide
        $sessions = [];
        foreach ($events as $event){
            // Pousse dans le tableau sessions tous les attributs d'un événement attendu par fullcalendar
            $sessions[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStartAt()->format('Y-m-d H:i'), // convertit l'objet datetime en string
                'end' => $event->getEndAt()->format('Y-m-d H:i'),
                'textColor' => $event->getTextColor(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
            ];
        }
        // encode les données du tableau en tableau d'objets json $data pour pouvoir les intégrer dans le calendrier
        $data = json_encode($sessions);

        // Rends la vue
        return $this->render('front/canyon/single.html.twig',[
            // Passe l'objet canyon à twig
            'canyon' => $canyon,
            // Passe les données à twig
            'data' => $data
        ]);
    }
}
