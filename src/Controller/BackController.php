<?php

namespace App\Controller;
use App\Repository\CampingRepository;
use App\Repository\ObjectifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
    #[Route('/tables/camping', name: 'app_mescampings')]
        public function index1(CampingRepository $campingRepository): Response
    {
        // Get the list of campaigns from the database
        $campings = $campingRepository->findAll();

        // Render the template and pass the list of campaigns
        return $this->render('camping/campingback.html.twig', [
            'campings' => $campings,

        ]);
    }
    #[Route('/tables/objectif', name: 'app_objectif')]
    public function index2(ObjectifRepository $objectifRepository): Response
{
    // Get the list of campaigns from the database
    $objectifs = $objectifRepository->findAll();

    // Render the template and pass the list of campaigns
    return $this->render('objectif/objectifback.html.twig', [
        'objectifs' => $objectifs,

    ]);
}
}
