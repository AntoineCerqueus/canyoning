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
    // Cette fonction dépends de la fonction findAll de  canyonRepository.
    // Donc au lien d'instancier nous même un repo avec $canyonRepository = $this->getDoctrine()->getRepository(Canyon::class);
    // on le lui injecte en paramètre en précisant qu'il fonctionnera avec une instance de la classe CanyonRepository
    // (Ne pas oublier le use pour que php comprenne ce que nous voulons utiliser)
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
        // Instanciation d'un nouveau canyon
        $canyon = new Canyon();
        // Création du formulaire basé sur la classe CanyonType que l'on bind au canyon 
        $form = $this->createForm(CanyonType::class, $canyon);
        // Traite la requête reçue en paramètre 
        $form->handleRequest($request);
        // dump($canyon);exit;

        // Traitement du formulaire => Vérification que le formulaire à été bien soumis et bien rempli
        if ($form->isSubmitted() && $form->isValid()) {
            // // On récupère les images transmises
            // $canyonPictures = $canyon->getPictures();
            // // $key corresponds à l'index du tableau
            // foreach ($canyonPictures as $key => $canyonPicture) {
            //     $canyonPicture->setCanyon($canyon);
            //     $canyonPictures->set($key, $canyonPicture);
            // }

            // Récupération des images transmises
            $pictures = $form->get('images')->getData();
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

            return $this->redirectToRoute('admin_canyon_index');
        }

        return $this->render('admin/canyon/new.html.twig', [
            'canyon' => $canyon,
            'form' => $form->createView(),
        ]);
    }
    // INJECTION DE DEPENDANCE (merci le service container)
    // Ici grâce à l'id passé en paramètre de ma route, plus besoin de faire appel au repo. Symfony comprends avec l'id 
    // et la variable Canyon de classe Canyon passée en paramètre de la fonction que nous voulons récupérer l'article en question
    // Plus besoin d'instancier des classes nous même => fonction plus propre => Merci Symfony!
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
            // $canyonPictures = $canyon->getPictures();
            // // $key corresponds à l'index du tableau
            // foreach ($canyonPictures as $key => $canyonPicture) {
            //     $canyonPicture->setCanyon($canyon);
            //     $canyonPictures->set($key, $canyonPicture);
            // }
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

                // Stockage du nom de l'image dnas la bdd
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
        if ($this->isCsrfTokenValid('delete' . $canyon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($canyon);
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
        // Vérification du bon token reçu
        // 'delete' et $picture->getId() => nom du token concaténé à l'id de l'image et $data['_token'] => token reçu
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            // Récupération du nom de l'image
            $name = $picture->getPath();
            // Suppression du fichier
            unlink($this->getParameter('images_directory') . '/' . $name);

            // Suppression de l'entrée de la base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();

            // Réponse en Json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Le token est invalide', 400]); // Affiche l'erreur avec une erreur 400
        }
    } // https://www.youtube.com/watch?v=apWjiEuDS0k

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
}
