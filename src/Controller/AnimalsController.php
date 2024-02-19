<?php

namespace App\Controller;

use App\Entity\Animals;
use App\Form\AnimalType;
use App\Repository\AnimalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalsController extends AbstractController
{
    #[Route('/animals', name: 'app_animals')]
    public function index(): Response
    {
        // Get the list of animals from the database
        $animals = $this->getDoctrine()->getRepository(Animals::class)->findAll();

        // Render the template and pass the list of animals
        return $this->render('animals/index.html.twig', [
            'animals' => $animals,
        ]);
    }

    #[Route('/animals/new', name: 'app_animals_new')]
    
    public function new(Request $request): Response
    {
        $animal = new Animals();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('app_animals');
        }

        return $this->render('animals/new.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/animals/{id}/edit', name: 'edit_animal')]
    public function edit(Request $request, Animals $animal): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_animals');
        }

        return $this->render('animals/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/animals/remove/{id}', name: 'remove_animal')]
    public function delete($id, EntityManagerInterface $entityManager, AnimalsRepository $animalRepository): Response
    {
        $animal = $animalRepository->find($id);   // Retrieve the animal to be removed

        if (!$animal) {
            throw $this->createNotFoundException('Animal not found');
        }

        $entityManager->remove($animal);         // Perform the removal 
        $entityManager->flush();

        return $this->redirectToRoute('app_animals');
    }
    



}