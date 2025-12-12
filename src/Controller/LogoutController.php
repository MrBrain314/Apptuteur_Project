<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session)
    {
        $session->remove('tuteur_id');

        $this->addFlash('success', 'Vous avez été déconnecté.');

        return $this->redirectToRoute('login');
    }
}
