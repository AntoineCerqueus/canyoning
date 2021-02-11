<?php

namespace App\Controller\front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('front/default/home.html.twig'
        // , [
        //     'controller_name' => 'Tonio',
        // ]
    );
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function cgv(): Response
    {
        return $this->render('front/default/cgv.html.twig');
    }

    /**
     * @Route("/mentions-légales", name="legal_mentions")
     */
    public function legalMentions(): Response
    {
        return $this->render('front/default/legal-mentions.html.twig');
    }

}
