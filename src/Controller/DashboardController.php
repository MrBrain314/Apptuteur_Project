<?php

namespace App\Controller;

use App\Entity\Tuteur;
use App\Entity\Etudiant;
use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');

        if (!$tuteurId) {
            return $this->redirectToRoute('login'); // redirection si non connecté
        }

        $tuteur = $em->getRepository(Tuteur::class)->find($tuteurId);

        if (!$tuteur) {
            $session->remove('tuteur_id');
            return $this->redirectToRoute('login');
        }

        // Récupération des étudiants du tuteur
        $etudiants = $em->getRepository(Etudiant::class)->findBy(['tuteur' => $tuteur]);

        // Récupération des prochaines visites planifiées
        $prochainesVisites = $em->getRepository(Visite::class)->findBy(
            ['tuteur' => $tuteur, 'statut' => 'prévue'],
            ['date' => 'ASC']
        );

        return $this->render('dashboard/index.html.twig', [
            'tuteur' => $tuteur,
            'etudiants' => $etudiants,
            'prochainesVisites' => $prochainesVisites,
        ]);
    }
}
