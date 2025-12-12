<?php

namespace App\Controller;

use App\Entity\Visite;
use App\Entity\Etudiant;
use App\Enum\Statut;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VisiteController extends AbstractController
{
    // Liste des visites d'un étudiant avec filtre et tri
    #[Route('/etudiants/{id}/visites', name: 'visites_list')]
    public function list(
        Etudiant $etudiant,
        Request $request,
        SessionInterface $session,
        VisiteRepository $visiteRepo
    ): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        if ($etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('danger', "Vous n'avez pas l'autorisation de voir les visites de cet étudiant.");
            return $this->redirectToRoute('dashboard');
        }

        // Récupérer les paramètres GET
        $statut = $request->query->get('statut') ?? 'toutes';
        $sort = $request->query->get('sort') ?? 'asc';

        // Récupérer les visites filtrées et triées
        $visites = $visiteRepo->findByEtudiantWithFilterAndSort($etudiant, $statut, $sort);

        return $this->render('visite/list.html.twig', [
            'etudiant' => $etudiant,
            'visites' => $visites,
            'statut' => $statut,
            'sort' => $sort,
        ]);
    }

    // Ajouter une visite
    #[Route('/etudiants/{id}/visites/new', name: 'visite_new')]
    public function new(Etudiant $etudiant, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        $tuteur = $em->getRepository('App\Entity\Tuteur')->find($tuteurId);

        $visite = new Visite();
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($tuteur);
        $visite->setStatut(Statut::PREVUE);

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($visite);
            $em->flush();
            $this->addFlash('success', 'Visite ajoutée avec succès !');

            return $this->redirectToRoute('visites_list', ['id' => $etudiant->getId()]);
        }

        return $this->render('visite/form.html.twig', [
            'form' => $form->createView(),
            'etudiant' => $etudiant,
        ]);
    }

    // Modifier une visite
    #[Route('/visites/{id}/edit', name: 'visite_edit')]
    public function edit(Visite $visite, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        if ($visite->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('danger', "Vous n'avez pas l'autorisation de modifier cette visite.");
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->flush();
            $this->addFlash('success', 'Visite modifiée avec succès !');

            return $this->redirectToRoute('visites_list', ['id' => $visite->getEtudiant()->getId()]);
        }

        return $this->render('visite/form.html.twig', [
            'form' => $form->createView(),
            'etudiant' => $visite->getEtudiant(),
        ]);
    }

    // Compte-rendu d'une visite
    #[Route('/visites/{id}/compte-rendu', name: 'visite_compte_rendu')]
    public function compteRendu(Visite $visite, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) return $this->redirectToRoute('login');

        if ($visite->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('danger', "Vous n'avez pas l'autorisation d'éditer ce compte-rendu.");
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createFormBuilder($visite)
            ->add('compteRendu', TextareaType::class, [
                'label' => 'Compte-rendu',
                'required' => true
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compte-rendu sauvegardé avec succès !');
            return $this->redirectToRoute('visites_list', ['id' => $visite->getEtudiant()->getId()]);
        }

        return $this->render('visite/compte_rendu.html.twig', [
            'form' => $form->createView(),
            'visite' => $visite,
        ]);
    }

    // Compte-rendu PDF d'une visite
    #[Route('/visites/{id}/compte-rendu/pdf', name: 'visite_compte_rendu_pdf')]
    public function exportPdf(Visite $visite): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->renderView('visite/compte_rendu_pdf.html.twig', [
            'visite' => $visite
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="compte_rendu_'.$visite->getId().'.pdf"',
        ]);
    }


   
}
