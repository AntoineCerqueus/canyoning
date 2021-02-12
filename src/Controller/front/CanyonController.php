<?php

namespace App\Controller\front;

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
     * @Route("/canyon", name="canyon")
     */
    // public function index(): Response
    // {
    //     return $this->render('canyon/index.html.twig', [
    //         'controller_name' => 'CanyonController',
    //     ]);
    // }

    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(CanyonRepository $canyonRepository): Response
    {
        return $this->render('front/canyon/index.html.twig', [
            'canyons' => $canyonRepository->findAll(),
        ]);
    }
}
