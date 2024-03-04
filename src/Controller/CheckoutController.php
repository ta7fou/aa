<?php

namespace App\Controller;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CheckoutController extends AbstractController
{
    #[Route('/checkout/liste', name: 'app_checkliste')]
    public function index(): Response
    {
        $facture = $this->getDoctrine()->getRepository(facture::class)->findAll();
        return $this->render('checkout/show.html.twig', [
            'facture' => $facture,
        ]);
    }
    #[Route('/checkout', name: 'app_checkout')]
    public function new(Request $request): Response
    {
        $facture = new facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_checkout');
        }

        return $this->render('checkout/index.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }
    #[Route('cheakout/editc/{id}', name: 'edit_facture')]
    public function edit(Request $request, Facture $facture): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('checkout/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }
    #[Route('cheakout/remove/{id}', name: 'remove_facture')]
    public function delete($id, EntityManagerInterface $entityManager, FactureRepository $factureRepository): Response
    {
        $facture = $factureRepository->find($id);   // Retrieve the animal to be removed

        if (!$facture) {
            throw $this->createNotFoundException('facture not found');
        }

        $entityManager->remove($facture);         // Perform the removal 
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
