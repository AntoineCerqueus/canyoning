<?php

namespace App\Controller\front;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/front/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Création du formulaire de type contact
        $form = $this->createForm(ContactType::class);
        // Récupération, traitement du message et de ses informations envoyé en post
        $form->handleRequest($request);

        // Si le formulaire a été soumis (donen methode post et bien rempli)
        if ($form->isSubmitted() && $form->isvalid()) {
            // Récupération des informations
            $informations = $form->getData();
            // Instanciation du mail
            $message = (new Email())
                ->from($informations['email'])
                ->to('antoine.cerqueus@gmail.com')
                ->subject('Demande d\'informations')
                ->text($informations['message'])
                // Création du message avec la vue twig "informations/html.twig"
                ->html(
                    $this->renderView(
                        'emails/informations.html.twig',
                        [
                            'informations' => $informations
                        ]
                    )
                );

            $mailer->send($message);
            $this->addFlash('message', 'Votre message a bien été envoyé, nous vous répondrons dans les plus brefs délais.');
            // return $this->redirectToRoute('home');
        }


        return $this->render('front/contact/index.html.twig', [
            // Création de la variable pour twig dans laquelle nous lui donnons la vue du formulaire
            'contactForm' => $form->createView()
        ]);
    }
}
