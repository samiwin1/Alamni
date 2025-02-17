<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ReponseRepository;
use App\Repository\MessageRepository;
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
    public function descussion(ReponseRepository $reponseRepository, MessageRepository $messageRepository): Response
    { $messages = $messageRepository->findAll();
        $reponses = $reponseRepository->findAll(); 
        return $this->render('backOffice/discussions.html.twig',[
            'messages' => $messages,
            'reponses' => $reponses,
        ]);

    }

    #[Route('/publication', name: 'publication')]
    public function pub(): Response
    {
        return $this->render('post/indexa.html.twig');
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
    #[Route('/singin', name: 'app_singin')]
    public function signup(Request $request)
    {
        // Crée un nouvel utilisateur
        $user = new UserType();
 
        // Crée le formulaire
        $form = $this->createForm(UserType::class, $user);
 
        // Gère la requête et les soumissions du formulaire
        $form->handleRequest($request);
 
        // Si le formulaire est valide, effectue l'action (par exemple, enregistrer l'utilisateur)
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'utilisateur, etc.
            // Par exemple : $entityManager->persist($user); $entityManager->flush();
        }
 
        // Passe le formulaire au template
        return $this->render('backOffice/auth-sign-up.html.twig', [
            'form' => $form->createView(),  // Assure-toi de passer cette variable
        ]);
    }
    #[Route('/profile', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('base_back.html.twig');
    }

   
  
}