<?php

namespace App\Controller\admin;

use App\Entity\Canyon;
use App\Entity\Picture;
use App\Form\CanyonType;
use App\Repository\CanyonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/canyon", name="admin_canyon_")
 */
class CanyonController extends AbstractController
{
    // INJECTION DE DEPENDANCE (merci le service container)
    // Cette fonction dépends de la fonction findAll de canyonRepository.
    // Donc au lieu d'instancier nous même un repo avec $canyonRepository = $this->getDoctrine()->getRepository(Canyon::class);
    // on le lui injecte en paramètre en précisant qu'il fonctionnera avec une instance de la classe CanyonRepository
    // (Ne pas oublier le use pour que php comprenne ce que nous voulons utiliser)
    /**
     * @Route("/", name="index", methods={"GET"})
     */
                         // Injection de dépendance
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
        // Instanciation d'un nouveau canyon
        $canyon = new Canyon();
        // Création du formulaire basé sur la classe CanyonType que l'on bind au canyon 
        $form = $this->createForm(CanyonType::class, $canyon);
        // Traite la requête reçue en paramètre 
        $form->handleRequest($request);
        // dump($canyon);exit;

        // Traitement du formulaire à condition qu'il est été bien soumis et bien rempli
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération des images transmises via l'input
            $pictures = $form->get('pictures')->getData();
            // Boucle sur un tableau $pictures car plusieurs images
            foreach ($pictures as $picture) {
                // Génération d'un nom de fichier unique
                // md5() génére une chaine de caractère aléatoire
                // uniqid() aussi mais basé sur le temps (timestamp)
                // guessExtension() => Trouve l'extension du fichier
                $fileName = md5(uniqid()) . '.' . $picture->guessExtension(); 
                // Copie du fichier du dossier temporaire du serveur dans le dossier uploads
                $picture->move(
                    // accède au paramètre rentré dans le fichier services.yaml (répertoire de destination)
                    $this->getParameter('images_directory'), 
                    $fileName
                );

                // Création d'un objet image
                $picture = new Picture();
                // Donne le nom à l'image
                $picture->setPath($fileName);
                // Associe l'image au canyon
                $canyon->addPicture($picture);
            }

            // Appel du manager de doctrine pour utiliser persist() et flush()
            $entityManager = $this->getDoctrine()->getManager();
            // Prépare, persiste le nouvel objet avec les images en cascade
            $entityManager->persist($canyon);
             // Stockage du nom de l'image dans la bdd
            $entityManager->flush();
            // Retour à l'index
            return $this->redirectToRoute('admin_canyon_index');
        }
        // Retourne la vue avec un formulaire vide basé sur l'objet canyon
        return $this->render('admin/canyon/new.html.twig', [
            'canyon' => $canyon,
            'form' => $form->createView(),
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
           
            // Récupération des images transmises
            $pictures = $form->get('pictures')->getData();
            // Boucle sur un tableau $images car nous récupérons plusieurs images
            foreach ($pictures as $picture) {
                // Génération d'un nom de fichier unique
                // md5() génére une chaine de caractère aléatoire et uniqud() aussi mais basé sur le temps (timestamp) => méthode php
                $fileName = md5(uniqid()) . '.' . $picture->guessExtension(); // guessExtension() => Trouve l'extension du fichier
                // Copie du fichier dans le dossier uploads
                $picture->move(
                    $this->getParameter('images_directory'), // accède au paramètre rentré dans le fichier services.yaml (répertoire de destination)
                    $fileName
                );

                // Stockage du nom de l'image dans la bdd
                $picture = new Picture();
                $picture->setPath($fileName);
                $canyon->addPicture($picture);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($canyon);
            $entityManager->flush();

            return $this->redirectToRoute('admin_canyon_index', [
                'id' => $canyon->getId(),
            ]);
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
        // méthode comparant le token enregistré et celui récupéré lors de la requête
        // S'ils correspondent, on entre dans la condition
        if ($this->isCsrfTokenValid('delete' . $canyon->getId(), $request->request->get('_token'))) {
            // instanciation du managger de doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Préparation de la suppression du canyon
            $entityManager->remove($canyon);
            // Suppression du canyon dans la base
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_canyon_index');
    }

    /**
     * @Route("/delete/picture/{id}", name="delete_picture", methods={"DELETE"})
     */
    public function deletePicture(Request $request, Picture $picture): Response
    {
        // Décodage du contenu de la requête ajax en json
        $data = json_decode($request->getContent(), true); // true => affiche le nom des colonnes du tableau associatif
        // Vérification du bon token reçu :
        // 'delete' + $picture->getId() => nom du token concaténé à l'id de l'image
        // $data['_token'] => token reçu
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            // Récupération du nom de l'image pour le donner à la méthode unlink()
            $name = $picture->getPath();
            // Suppression du fichier = uploads/nom_image
            unlink($this->getParameter('images_directory') . '/' . $name);

            // Appel du manager de doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Appel de fonction remove du manager avec le nom de l'image à supprimer
            $entityManager->remove($picture);
            // Suppression de l'image en base
            $entityManager->flush();

            // Réponse en cas de succès en Json
            return new JsonResponse(['success' => 1]);
        } else {
            // Réponse en cas d'échec en Json
            return new JsonResponse(['error' => 'Le token est invalide', 400]); // Affiche l'erreur avec une erreur 400
        }
    }

    /**
     * @Route("/{id}/events", name="show_events", methods={"GET"})
     */
    public function showEvents(Canyon $canyon): Response
    {
        // recupère les évènements par canyon
        $events = $canyon->getEvents();
        return $this->render('admin/canyon/show_events.html.twig', [
            'canyon' => $canyon,
            'events' => $events
        ]);
    }

    /**
     * @Route("/{id}/prices", name="show_prices", methods={"GET"})
     */
    public function showPrices(Canyon $canyon): Response
    {
        // recupère les prix par canyon
        $prices = $canyon->getPrices();
        return $this->render('admin/canyon/show_prices.html.twig', [
            'canyon' => $canyon,
            'prices' => $prices
        ]);
    }
}
