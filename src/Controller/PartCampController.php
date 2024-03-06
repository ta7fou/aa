<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\PartCamp;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PartcampType;
use App\Repository\PartCampRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class PartCampController extends AbstractController
{
    #[Route('/partcamp', name: 'app_partcamp')]
    public function new(Request $request, EntityManagerInterface $entityManager, PartCampRepository $PartCampRepository, SessionInterface $session, Security $security): Response
    {
        $user = $security->getUser();
        $PartCamp = new PartCamp();
    
        // Check if the User is authenticated
        if ($user !== null) {
            // If the User is authenticated, populate the PartCamp with User information
            $PartCamp->setUser($user)
                    ->setNomuser($user->getNom())
                    ->setEmailuser($user->getEmail());
        }
        $form = $this->createForm(PartCampType::class, $PartCamp);
        $form->handleRequest($request);
    
        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($PartCamp);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_home');
        }


        return $this->render('partcamp/index.html.twig', [
            'PartCamp' => $PartCamp,
            'form' => $form->createView(),
        ]);
    }

    
}
