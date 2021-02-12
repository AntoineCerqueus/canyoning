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
        // Permets d'afficher le canyon dans son intégralité en récuprant son id grâce à le boucle du 'findAll'
        return $this->render('front/canyon/single.html.twig',[
            'canyon' => $canyon
        ]);
    }
}
