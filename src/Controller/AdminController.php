<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('base_back.html.twig');
    }
    #[Route('/user', name: 'admin_user')]
    public function user(): Response
    {
        return $this->render('backOffice/user.html.twig');
    }

    #[Route('/classes', name: 'classes')]
    public function classes(): Response
    {
        return $this->render('backOffice/classes.html.twig');
    }

    #[Route('/discussions', name: 'discussions')]
    public function descussion(): Response
    {
        return $this->render('backOffice/discussions.html.twig');
    }

    #[Route('/publication', name: 'publication')]
    public function pub(): Response
    {
        return $this->render('backOffice/publication.html.twig');
    }

    #[Route('/club', name: 'club')]
    public function club(): Response
    {
        return $this->render('backOffice/club.html.twig');
    }

    #[Route('/planning', name: 'planning')]
    public function plan(): Response
    {
        return $this->render('backOffice/planning.html.twig');
    }



    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->render('backOffice/auth-normal-sign-in.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('backOffice/auth-normal-sign-in.html.twig');
    }


    #[Route('/profile', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('base_back.html.twig');
    }
}