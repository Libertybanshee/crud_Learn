<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JoueurController extends AbstractController
{
    /**
     * @Route("/joueurs/", name="joueur_list")
     */
    public function joueurList(JoueurRepository $joueurRepository)
    {
        $joueurs = $joueurRepository->findAll();

        return $this->render('joueurs.html.twig', ['joueurs' => $joueurs]);
    }

    /**
     * @Route("/joueur/{id}", name="joueur_focus")
     */
    public function joueurFocus($id, JoueurRepository $joueurRepository)
    {
        $joueur = $joueurRepository->find($id);

        return $this->render('joueur.html.twig', ['joueur' => $joueur]);
    }

    /**
     * @Route("/add/joueur", name="joueur_add")
     */
    public function joueurAdd(EntityManagerInterface $entityManagerInterface, Request $request, SluggerInterface $sluggerInterface)
    {
        $joueur = new Joueur();

        $joueurForm = $this->createForm(JoueurType::class, $joueur);

        $joueurForm->handleRequest($request);

        if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {

            $mediaFile = $joueurForm->get('src')->getData();

            if ($mediaFile) {
                // On créé un nom unique avec le nom original de l'image pour éviter
                // tout problème
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // on utilise slug sur le nom original de l'image pour avoir un nom valide
                $safeFilename = $sluggerInterface->slug($originalFilename);
                // on ajoute un id unique au nom de l'image
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();


                // On déplace le fichier dans le dossier public/media
                // la destination du fichier est enregistré dans 'images_directory'
                // qui est défini dans le fichier config\services.yaml
                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $joueur->setSrc($newFilename);
            }

            $entityManagerInterface->persist($joueur);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("joueur_list");
        } else {
            
            return $this->render('joueurUpdate.html.twig', ['joueurForm' => $joueurForm->createView()]);
        }

    }

    /**
     * @Route("/update/joueur/{id}", name="joueur_update")
     */
    public function joueurUpdate($id, JoueurRepository  $joueurRepository, EntityManagerInterface $entityManagerInterface, Request $request, SluggerInterface $sluggerInterface)
    {
        $joueur = $joueurRepository->find($id);

        $joueurForm = $this->createForm(JoueurType::class, $joueur);

        $joueurForm->handleRequest($request);

        if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {

            $mediaFile = $joueurForm->get('src')->getData();

            if ($mediaFile) {
                // On créé un nom unique avec le nom original de l'image pour éviter
                // tout problème
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // on utilise slug sur le nom original de l'image pour avoir un nom valide
                $safeFilename = $sluggerInterface->slug($originalFilename);
                // on ajoute un id unique au nom de l'image
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();


                // On déplace le fichier dans le dossier public/media
                // la destination du fichier est enregistré dans 'images_directory'
                // qui est défini dans le fichier config\services.yaml
                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $joueur->setSrc($newFilename);
            }

            $entityManagerInterface->persist($joueur);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("joueur_list");
        } else {
            
            return $this->render('joueurUpdate.html.twig', ['joueurForm' => $joueurForm->createView()]);
        }

    }

    /**
     * @Route("/delete/joueur/{id}", name="joueur_delete")
     */
    public function joueurDelete($id, JoueurRepository  $joueurRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $joueur = $joueurRepository->find($id);

        $joueurForm = $this->createForm(JoueurType::class, $joueur);

        $joueurForm->handleRequest($request);

        echo('ATTENTION : VOUS ALLEZ SUPPRIMER LE JOUEUR <br/><br/>');

        if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {
            $entityManagerInterface->remove($joueur);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("joueur_list");
        } else {
            
            return $this->render('joueurUpdate.html.twig', ['joueurForm' => $joueurForm->createView()]);
        }
    }
}
