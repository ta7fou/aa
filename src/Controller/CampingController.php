<?php

namespace App\Controller;

use App\Entity\Camping;
use App\Entity\Objectif;
use App\Form\CampingType;
use App\Repository\CampingRepository;
use App\Repository\ObjectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampingController extends AbstractController
{

    #[Route('/camping/{id}/objectif', name: 'app_camping_objectif', methods: ['GET'])]
    public function showObjectifsByCamping(Camping $camping): Response
    {
        $objectif = $camping->getObjectif();
    
        return $this->render('camping/objectifs.html.twig', [
            'camping' => $camping,
            'objectif' => $objectif,
        ]);
    }
    
    #[Route('/camping', name: 'app_camping')]
    public function index(CampingRepository $campingRepository): Response
    {
        $campings = $campingRepository->findAll();

        return $this->render('camping/index.html.twig', [
            'campings' => $campings,
        ]);

    }

    #[Route('/camping/new', name: 'app_camping_new')]
    public function new(Request $request): Response
    {

        $campings = new Camping();
       // Assuming you have an instance of the Camping class


// Accessing the objid property using the getObjectif() method
$objectif = $campings->getObjectif();

// Now you can use $objectif as needed
if ($objectif !== null) {
    // Assuming getObjid() is a method in the Objectif class
    $objidValue = $objectif->getObjid();
    // Do something with $objidValue
    echo "objid value: " . $objidValue;
} else {
    // Handle the case where objid is null
    echo "objid is null";
}

        $form = $this->createForm(CampingType::class, $campings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campings);
            $entityManager->flush();

            return $this->redirectToRoute('app_camping');
        }

        return $this->render('camping/new.html.twig', [
            'campings' => $campings,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/camping/{id}/edit', name: 'edit_camping_item')]
    public function edit(Request $request, Camping $campings): Response
    {
        $form = $this->createForm(CampingType::class, $campings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_camping');
        }

        return $this->render('camping/edit.html.twig', [
            'campings' => $campings,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/camping/{id}', name: 'app_camping_show', methods: ['GET'])]
public function show(Camping $camping, ObjectifRepository $objectifRepository): Response
{
    $objectif = $camping->getObjectif();

    return $this->render('camping/objectifs.html.twig', [
        'camping' => $camping,
        'objectif' => $objectif,
    ]);
}



    #[Route('/camping/remove/{id}', name: 'remove_camping_item')]
    public function delete($id, EntityManagerInterface $entityManager, CampingRepository $campingRepository): Response
    {
        $campings = $campingRepository->find($id);   

        if (!$campings) {
            throw $this->createNotFoundException('Camping item not found');
        }

        $entityManager->remove($campings);          
        $entityManager->flush();

        return $this->redirectToRoute('app_camping');
    }

}



