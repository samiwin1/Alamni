<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
final class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('frontOffice/reclamation.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $reclamation = new Reclamation();
    $reclamation->setStatus('En attente'); // ✅ Définit "En attente" par défaut

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        // Contrôle de saisie en PHP avant validation
        if (!$reclamation->getUserEmail()) {
            $this->addFlash('error', "L'email est obligatoire.");
        }
        if (!$reclamation->getAdminMail()) {
            $this->addFlash('error', "Le destinataire est obligatoire.");
        }
        if (!$reclamation->getRole()) {
            $this->addFlash('error', "Veuillez sélectionner un rôle.");
        }
        if (!$reclamation->getObjet()) {
            $this->addFlash('error', "L'objet est obligatoire.");
        }
        if (!$reclamation->getDescription()) {
            $this->addFlash('error', "Veuillez entrer une description.");
        }

        // Validation du formulaire après le contrôle
        if ($form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réclamation a été envoyée avec succès.');

            return $this->redirectToRoute('app_reclamation');
        }
    }

    return $this->render('frontOffice/reclamation.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form->createView(),
    ]);
}

    
    

    #[Route('/{id}', name: 'app_reclamation_front_show', methods: ['GET'])]
    public function showFront(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Ensure all required fields are filled
                if (!$reclamation->getUserEmail() || !$reclamation->getAdminMail() || !$reclamation->getRole() || !$reclamation->getObjet() || !$reclamation->getDescription()) {
                    $this->addFlash('error', 'Tous les champs sont obligatoires.');
                } else {
                    $entityManager->flush();
                    $this->addFlash('success', 'Réclamation mise à jour avec succès !');
                    return $this->redirectToRoute('app_reclamation_front_show', ['id' => $reclamation->getId()]);
                }
            } else {
                $this->addFlash('error', 'Échec de la mise à jour. Veuillez vérifier vos informations.');
            }
        }

        return $this->render('reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Réclamation supprimée avec succès !');
        } else {
            $this->addFlash('error', 'Échec de la suppression. Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_reclamation'); // Redirects to the reclamations list after deletion
    }

    #[Route('/my-reclamations', name: 'app_my_reclamations', methods: ['GET'])]
public function myReclamations(ReclamationRepository $reclamationRepository, Request $request): Response
{
    // 🔴 TEMPORAIRE : Simuler un utilisateur avec un email fixe
    $userEmail = "roua@gmail.com	"; // Changez cet email si nécessaire

    // 🔎 Récupérer toutes les réclamations de cet utilisateur temporaire
    $reclamations = $reclamationRepository->findBy(['user_email' => $userEmail]);

    return $this->render('frontOffice/my_reclamations.html.twig', [
        'reclamations' => $reclamations,
        'userEmail' => $userEmail, // Pour l'afficher dans le front
    ]);
}

}
