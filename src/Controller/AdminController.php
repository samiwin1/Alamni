<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private function getLoggedInUser(Request $request, EntityManagerInterface $entityManager): ?User
    {
        $email = $request->getSession()->get('user_email');
        if (!$email) {
            return null;
        }
        return $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getLoggedInUser($request, $entityManager);
        if (!$user) {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }
        
        return $this->render('base_back.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si déjà connecté, rediriger vers le dashboard
        if ($this->getLoggedInUser($request, $entityManager)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
            
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            
            if ($user && password_verify($password, $user->getMotDePasse())) {
                $request->getSession()->set('user_email', $user->getEmail());
                $this->addFlash('success', 'Connexion réussie!');
                return $this->redirectToRoute('admin_dashboard');
            } else {
                $this->addFlash('error', 'Email ou mot de passe incorrect');
            }
        }

        return $this->render('backOffice/auth-normal-sign-in.html.twig');
    }

    #[Route('/sign-in', name: 'app_signin')]
    public function signin(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si déjà connecté, rediriger vers le dashboard
        if ($this->getLoggedInUser($request, $entityManager)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $user = new User();
        $user->setDateInscription(date('Y-m-d H:i:s'));
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe
            $user->setMotDePasse(password_hash($user->getMotDePasse(), PASSWORD_DEFAULT));
            
            // Définir un rôle par défaut si non spécifié
            if (!$user->getRole()) {
                $user->setRole('ROLE_USER');
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('login');
        }

        return $this->render('backOffice/auth-sign-up.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profile', name: 'user_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getLoggedInUser($request, $entityManager);
        // if (!$user) {
        //     $this->addFlash('error', 'Veuillez vous connecter');
        //     return $this->redirectToRoute('login');
        // }
        
        return $this->render('backOffice/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->remove('user_email');
        $request->getSession()->clear();
        
        $this->addFlash('success', 'Vous avez été déconnecté');
        return $this->redirectToRoute('login');
    }

    #[Route('/admin/users', name: 'admin_user')]
    public function adminUsers(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getLoggedInUser($request, $entityManager);
        if (!$user) {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }

        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();
        
        return $this->render('backOffice/users.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }
}