<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route('/reclamations', name: 'app_reclamation_back')]
    public function reclamation(ReclamationRepository $reclamationRepository): Response
    {
         $reclamations = $reclamationRepository->findAll();

         return $this->render('backOffice/reclamation.html.twig', [
             'reclamations' => $reclamations,
         ]);
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

    #[Route('/{id}/delete', name: 'app_reclamation_delete_back', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Réclamation supprimée avec succès !');
    
            // Redirect to the back office route (update with the correct route name)
            return $this->redirectToRoute('app_reclamation_back');  
        }
    
        $this->addFlash('error', 'Échec de la suppression.');
        return $this->redirectToRoute('app_reclamation_back'); // Ensure fallback redirection
    }
    
}