<?php

namespace App\Controller;
use App\Entity\Facture;
use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;


class CheckoutController extends AbstractController
{
    #[Route('/admin/listfac$facture', name: 'app_checkliste')]
    public function index(): Response
    {
        $facture = $this->getDoctrine()->getRepository(facture::class)->findAll();
        return $this->render('checkout/show.html.twig', [
            'facture' => $facture,
        ]);
    }
    #[Route('/checkout', name: 'app_checkout')]
    public function new(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository, SessionInterface $session, Security $security): Response
    {

        $user = $security->getUser();
        if ($user === null) {
            // Redirect the user to the login page or handle the situation as per your requirements
            return $this->redirectToRoute('app_login');
        }

        $cart = $session->get('cart', []);
    $produitIds = array_keys($cart);
    $produits = $produitRepository->findBy(['id' => $produitIds]);
    $totalAmount = 0;
    foreach ($produits as $produit) {
        $quantity = $cart[$produit->getId()]; // Get the quantity from the cart
        $totalAmount += $produit->getPriu() * $quantity;
    }



        $facture = new facture();
        $facture->setUser($user);
    $facture->setName($user->getNom());
    $facture->setEmail($user->getEmail());
    
    $facture->setAddress($user->getAddress());
    $facture->setPrixtotal($totalAmount); 
    
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('checkout/index.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/editc/{id}', name: 'edit_facture')]
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
    #[Route('admin/remove/{id}', name: 'remove_facture')]
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
