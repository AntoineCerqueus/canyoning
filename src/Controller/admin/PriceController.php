<?php

namespace App\Controller\admin;

use App\Entity\Canyon;
use App\Entity\Price;
use App\Form\PriceType;
use App\Repository\PriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/price", name="admin_price_")
 */
class PriceController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PriceRepository $priceRepository): Response
    {
        return $this->render('admin/price/index.html.twig', [
            'prices' => $priceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, Canyon $canyon): Response
    {
        $price = new Price();
        $price->setCanyon($canyon);
        $form = $this->createForm(PriceType::class, $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Instancie un manager et on lui demande de "discuter" avec Doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Dis à mon manager de faire persister mon nouvel event dans le temps
            // Prépare ma modification à intégrer dans la bdd
            $entityManager->persist($price);
            // Flush lance la requête sql qui permet d'inscrire ces nouvelles modifications dans la bdd
            $entityManager->flush();

            // Redirige vers le listing des prix par canyon avec le canyon ajouté grâce à l'id donné
            return $this->redirectToRoute('admin_canyon_show_prices', [
                'id' => $price->getCanyon()->getId()
            ]);
        }

        return $this->render('admin/price/new.html.twig', [
            'price' => $price,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Price $price): Response
    {
        return $this->render('admin/price/show.html.twig', [
            'price' => $price,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Price $price): Response
    {
        $form = $this->createForm(PriceType::class, $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // Redirige vers le listing des prix par canyon avec le canyon ajouté grâce à l'id donné
            return $this->redirectToRoute('admin_canyon_show_prices', [
                'id' => $price->getCanyon()->getId()
            ]);
        }

        return $this->render('admin/price/edit.html.twig', [
            'price' => $price,
            'form' => $form->createView(),
        ]);
    }
}
