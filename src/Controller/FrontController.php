<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlanningRepository;


class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base_front.html.twig'); 
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('frontOffice/about.html.twig');
    }

    #[Route('/classes', name: 'app_classes')]
    public function classes(): Response
    {
        return $this->render('frontOffice/classes.html.twig'); // No data passed for now
    }

  
    #[Route('/facility', name: 'app_facility')]
    public function facility(PlanningRepository $planningRepository): Response
    {
        // Fetch all planning data
        $planning = $planningRepository->findAll();
    
        return $this->render('frontOffice/facility.html.twig', [
            'planning' => $planning
        ]);
    }
    

    #[Route('/team', name: 'app_team')]
    public function team(): Response
    {
        return $this->render('frontOffice/team.html.twig');
    }

    #[Route('/call-to-action', name: 'app_cta')]
    public function callToAction(): Response
    {
        return $this->render('frontOffice/call-to-action.html.twig');
    }

    #[Route('/appointment', name: 'app_appointment')]
    public function appointment(): Response
    {
        return $this->render('frontOffice/appointment.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('frontOffice/contact.html.twig');
    }

    #[Route('/404', name: 'app_404')]
    public function notFound(): Response
    {
        return $this->render('frontOffice/404.html.twig');
    }
}
