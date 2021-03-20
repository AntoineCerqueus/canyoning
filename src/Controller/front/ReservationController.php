<?php

namespace App\Controller\front;

use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

class ReservationController extends AbstractController
{
    /**
     * @Route("/front/reservation", name="reservation")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Création du formulaire de type réservation
        $form = $this->createForm(ReservationType::class);
        // Récupération, traitement du message et de ses informations envoyé en post
        $form->handleRequest($request);

        // Si le formulaire a été soumis (donc en méthode post et bien rempli)
        if ($form->isSubmitted() && $form->isvalid()) {
            // Récupération des informations
            $informations = $form->getData();
            // dd($informations);
            // Instanciation du mail
            $message = (new Email())
                ->from($informations['email'])
                ->to('antoine.cerqueus@gmail.com')
                ->subject('Demande de réservation')
                ->text($informations['message'])
                // Création du message avec la vue twig "reservations/html.twig"
                ->html(
                    $this->renderView(
                        'emails/reservations.html.twig',
                        [
                            'informations' => $informations
                        ]
                    )
                );

            $mailer->send($message);
            $this->addFlash('message', 'Votre message a bien été envoyé ! Nous traitons votre demande dans les plus brefs délais et nous vous contacterons pour la validation.');
            // return $this->redirectToRoute('home');
        }


        return $this->render('front/reservation/index.html.twig', [
            // Création de la variable pour twig dans laquelle nous lui donnons la vue du formulaire
            'reservationForm' => $form->createView()
        ]);
    }
}
