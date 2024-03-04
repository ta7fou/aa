<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Animals;
use App\Form\DemandeType;
use App\Repository\AnimalsRepository;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DemandeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

#[Route('/demande/{animalId}', name: 'app_demande')]
public function demande(Request $request, Animals $animalId , AnimalsRepository $animalsRepository): Response
{
    $user = $this->getUser(); // Get current user

    // Check if user is authenticated
    if (!$user) {
        // Handle non-authenticated users, for example:
        throw $this->createAccessDeniedException('You must be logged in to access this page.');
    }

    // Check if user has an email
    $email = $user->getEmail();
    if ($email === null) {
        // Handle the case where user's email is null, for example:
        throw new \Exception('User email cannot be null.'); // Or handle this case differently
    }
    $animal = $animalsRepository->find($animalId);

        if (!$animal) {
            throw $this->createNotFoundException('Animal not found');
        }
    $demande = new Demande();
    $demande->setAnimalId($animalId);
    $demande->setIdUser($user->getId()); // Set id_user property
    $demande->setEmail($email); // Set email property

    // Create the form
    $form = $this->createForm(DemandeType::class, $demande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission
        // For example, persist the demande entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($demande);
        $entityManager->flush();

        // Redirect to a success page or do something else
        return $this->redirectToRoute('app_home');
    }

    return $this->render('demande/index.html.twig', [
        'demande' => $demande,
        'form' => $form->createView(),
        'animalId' => $animalId,
    ]);
}


    #[Route('/demandes/{userId}', name: 'demandes')]
    public function showDemandes(Request $request, DemandeRepository $demandeRepository, $userId): Response
    {
        // Fetch demandes associated with the provided user ID
        $demandes = $demandeRepository->findByUserId($userId);

        // Pass demandes and user ID to the Twig template
        return $this->render('demande/show.html.twig', [
            'demandes' => $demandes,
            'userId' => $userId,
        ]);
    }

    #[Route('/demandes/{id}/edit', name: 'edit_demande')] // the demande's id
    public function edit(Request $request, Demande $demande): Response
    {
        $demandeId = $request->query->get('id');
        $form = $this->createForm(DemandeType::class, $demande, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_animals');
        }

        return $this->render('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form->createView(),
            'demandeId' => $demandeId,
        ]);
    }

    #[Route('/demandes/remove/{id}', name: 'remove_demande')] // the demande's id
    public function delete($id, EntityManagerInterface $entityManager, DemandeRepository $demandeRepository): Response
    {
        $animal = $demandeRepository->find($id);   // Retrieve the animal to be removed

        if (!$animal) {
            throw $this->createNotFoundException('demande not found');
        }

        $entityManager->remove($animal);         // Perform the removal 
        $entityManager->flush();

        return $this->redirectToRoute('app_animals');
    }
}

