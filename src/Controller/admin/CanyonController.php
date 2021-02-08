<?php

namespace App\Controller\admin;

use App\Entity\Canyon;
use App\Form\CanyonType;
use App\Repository\CanyonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
       
        // Vérification que le formulaire à été bien soumis et bien rempli
        if ($form->isSubmitted() && $form->isValid()) {
            $canyonPictures = $canyon->getPictures();
            // $key corresponds à l'index du tableau
            foreach ($canyonPictures as $key => $canyonPicture) {
                $canyonPicture->setCanyon($canyon);
                $canyonPictures->set($key, $canyonPicture);
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
            $canyonPictures = $canyon->getPictures();
            // $key corresponds à l'index du tableau
            foreach ($canyonPictures as $key => $canyonPicture) {
                $canyonPicture->setCanyon($canyon);
                $canyonPictures->set($key, $canyonPicture);
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
