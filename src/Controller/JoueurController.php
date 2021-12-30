<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
    public function joueurAdd(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $joueur = new Joueur();

        $joueurForm = $this->createForm(JoueurType::class, $joueur);

        $joueurForm->handleRequest($request);

        if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {
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
    public function joueurUpdate($id, JoueurRepository  $joueurRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $joueur = $joueurRepository->find($id);

        $joueurForm = $this->createForm(JoueurType::class, $joueur);

        $joueurForm->handleRequest($request);

        if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {
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
    public function joueurDelete($id, JoueurRepository  $joueurRepository, EntityManagerInterface $entityManagerInterface)
    {
        $joueur = $joueurRepository->find($id);
        $entityManagerInterface->remove($joueur);
        $entityManagerInterface->flush();
            
        return $this->redirectToRoute('joueur_list');
    }
}
