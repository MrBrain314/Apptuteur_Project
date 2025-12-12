<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use App\Repository\TuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiants')]
class EtudiantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private EtudiantRepository $etudiantRepository,
        private TuteurRepository $tuteurRepository
    ) {}

    #[Route('', name: 'etudiants_list')]
    public function list(SessionInterface $session): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        $etudiants = $this->etudiantRepository->findBy(['tuteur' => $tuteurId]);

        return $this->render('etudiant/list.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/new', name: 'etudiant_new')]
    public function new(Request $request, SessionInterface $session): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        $tuteur = $this->tuteurRepository->find($tuteurId);

        $etudiant = new Etudiant();
        $etudiant->setTuteur($tuteur);

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

       if ($form->isSubmitted()) {
            $this->em->persist($etudiant);
            $this->em->flush();

            $this->addFlash('success', 'Étudiant ajouté avec succès !');
            return $this->redirectToRoute('etudiants_list');
        }

        return $this->render('etudiant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'etudiant_edit')]
    public function edit(Etudiant $etudiant, Request $request, SessionInterface $session): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('danger', "Vous n'avez pas l'autorisation de modifier cet étudiant.");
            return $this->redirectToRoute('etudiants_list');
        }

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Étudiant modifié avec succès !');

            return $this->redirectToRoute('etudiants_list');
        }

        return $this->render('etudiant/edit.html.twig', [
            'form' => $form->createView(),
            'etudiant' => $etudiant,
        ]);
    }
}
