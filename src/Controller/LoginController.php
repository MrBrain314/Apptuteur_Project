<?php

namespace App\Controller;

use App\Entity\Tuteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password'); // inutilisé mais demandé par le sujet

            // Chercher le tuteur par email
            $tuteur = $em->getRepository(Tuteur::class)->findOneBy(['email' => $email]);

            if ($tuteur) {
                // Stocker l'id du tuteur en session
                $session->set('tuteur_id', $tuteur->getId());
                
                return $this->redirectToRoute('dashboard');
            } else {
                $error = "Email incorrect, tuteur introuvable.";
            }
        }

        return $this->render('login/index.html.twig', [
            'error' => $error,
        ]);
    }
}
