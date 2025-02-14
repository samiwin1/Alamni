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
        // Vérifier l'utilisateur connecté
        $user = $this->getLoggedInUser($request, $entityManager);
        if (!$user) {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }

        // Si c'est un admin, récupérer la liste des utilisateurs
        $users = [];
        if ($user->getRole() === 'ROLE_ADMIN') {
            $users = $entityManager->getRepository(User::class)->findAll();
        }
        
        return $this->render('base_back.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }
    #[Route('/login', name: 'login')]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
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
        if ($this->getLoggedInUser($request, $entityManager)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $user = new User();
        $user->setDateInscription(new \DateTime());
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $imageContent = file_get_contents($photoFile->getPathname());
                $base64Image = base64_encode($imageContent);
                $user->setPhoto($base64Image);
            }

            $user->setMotDePasse(password_hash($user->getMotDePasse(), PASSWORD_DEFAULT));
            
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
        if (!$user) {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }
        
        return $this->render('backOffice/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/edit', name: 'user_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getLoggedInUser($request, $entityManager);
        if (!$user) {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }
    
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de la photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $imageContent = file_get_contents($photoFile->getPathname());
                $base64Image = base64_encode($imageContent);
                $user->setPhoto($base64Image);
            }
    
            // Gestion du mot de passe
            $newPassword = $form->get('mot_de_passe')->getData();
            if (!empty($newPassword)) {
                $user->setMotDePasse(password_hash($newPassword, PASSWORD_DEFAULT));
            }
            // Si pas de nouveau mot de passe, on garde l'ancien
    
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('user_profile');
        }
    
        return $this->render('backOffice/profile_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/profile/delete', name: 'user_profile_delete', methods: ['POST'])]
    public function deleteProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $user = $this->getLoggedInUser($request, $entityManager);
            if (!$user) {
                $this->addFlash('error', 'Veuillez vous connecter');
                return $this->redirectToRoute('login');
            }
    
            $token = $request->request->get('_token');
            
            // Debug
            dump([
                'token_received' => $token,
                'user_id' => $user->getId(),
                'csrf_valid' => $this->isCsrfTokenValid('delete'.$user->getId(), $token)
            ]);
    
            if (!$this->isCsrfTokenValid('delete'.$user->getId(), $token)) {
                $this->addFlash('error', 'Token de sécurité invalide');
                return $this->redirectToRoute('user_profile');
            }
    
            // Déconnexion de l'utilisateur
            $request->getSession()->clear();
            
            // Suppression du compte
            $entityManager->remove($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre compte a été supprimé avec succès');
            return $this->redirectToRoute('login');
    
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
            return $this->redirectToRoute('user_profile');
        }
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->remove('user_email');
        $request->getSession()->clear();
        
        $this->addFlash('success', 'Vous avez été déconnecté');
        return $this->redirectToRoute('login');
    }

    #[Route('/user/{id}/modify', name: 'user_modify')]
public function modifyUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    // Récupérer l'utilisateur connecté
    $currentUser = $this->getLoggedInUser($request, $entityManager);
    if (!$currentUser) {
        $this->addFlash('error', 'Veuillez vous connecter');
        return $this->redirectToRoute('login');
    }

    // Vérifier si l'utilisateur est un admin
    if ($currentUser->getRole() !== 'ROLE_ADMIN') {
        $this->addFlash('error', 'Accès non autorisé');
        return $this->redirectToRoute('admin_dashboard');
    }

    // Récupérer l'utilisateur à modifier
    $userToModify = $entityManager->getRepository(User::class)->find($id);
    if (!$userToModify) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(UserType::class, $userToModify, [
        'is_edit' => true,
    ]);
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de la photo
        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            $imageContent = file_get_contents($photoFile->getPathname());
            $base64Image = base64_encode($imageContent);
            $userToModify->setPhoto($base64Image);
        }

        // Gestion du mot de passe
        $newPassword = $form->get('mot_de_passe')->getData();
        if (!empty($newPassword)) {
            $userToModify->setMotDePasse(password_hash($newPassword, PASSWORD_DEFAULT));
        }

        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur modifié avec succès');
        return $this->redirectToRoute('admin_dashboard');
    }

    return $this->render('backOffice/user_modify.html.twig', [
        'form' => $form->createView(),
        'user' => $currentUser, // pour le menu
        'userToModify' => $userToModify
    ]);
}
#[Route('/user/{id}/delete', name: 'user_delete', methods: ['POST', 'DELETE'])]
public function deleteUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    // Vérifier si l'utilisateur connecté est admin
    $currentUser = $this->getLoggedInUser($request, $entityManager);
    if (!$currentUser || $currentUser->getRole() !== 'ROLE_ADMIN') {
        $this->addFlash('error', 'Accès non autorisé');
        return $this->redirectToRoute('admin_dashboard');
    }

    // Récupérer l'utilisateur à supprimer
    $userToDelete = $entityManager->getRepository(User::class)->find($id);
    if (!$userToDelete) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Vérifier le token CSRF
    $token = $request->request->get('_token');
    if (!$this->isCsrfTokenValid('delete'.$id, $token)) {
        $this->addFlash('error', 'Token de sécurité invalide');
        return $this->redirectToRoute('admin_dashboard');
    }

    try {
        // Ne pas permettre à un admin de se supprimer lui-même
        if ($userToDelete->getId() === $currentUser->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($userToDelete);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
    } catch (\Exception $e) {
        $this->addFlash('error', 'Une erreur est survenue lors de la suppression');
    }

    return $this->redirectToRoute('admin_dashboard');
}
#[Route('/user/add', name: 'user_add')]
#[Route('/user/add', name: 'user_add')]
public function addUser(Request $request, EntityManagerInterface $entityManager): Response
{
    // Vérifier l'utilisateur connecté
    $currentUser = $this->getLoggedInUser($request, $entityManager);
    if (!$currentUser || $currentUser->getRole() !== 'ROLE_ADMIN') {
        $this->addFlash('error', 'Accès non autorisé');
        return $this->redirectToRoute('admin_dashboard');
    }

    $newUser = new User();
    $form = $this->createForm(UserType::class, $newUser, [
        'is_edit' => false
    ]);
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de la photo
        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            $imageContent = file_get_contents($photoFile->getPathname());
            $base64Image = base64_encode($imageContent);
            $newUser->setPhoto($base64Image);
        }

        // Hash du mot de passe
        $newUser->setMotDePasse(password_hash($newUser->getMotDePasse(), PASSWORD_DEFAULT));
        
        // Date d'inscription
        $newUser->setDateInscription(new \DateTime());

        $entityManager->persist($newUser);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur ajouté avec succès');
        return $this->redirectToRoute('admin_dashboard');
    }

    return $this->render('backOffice/user_add.html.twig', [
        'form' => $form->createView(),
        'user' => $currentUser
    ]);
}
}