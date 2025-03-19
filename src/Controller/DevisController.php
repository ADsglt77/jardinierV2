<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Set auto fields
            $devis->setDate(new \DateTime());
            $devis->setUser($this->getUser());

            $entityManager->persist($devis);
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_show', ['id' => $devis->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('devis/new.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devis): Response
    {
        return $this->render('devis/show.html.twig', [
            'devis' => $devis,
        ]);
    }
    
    // ... you can add other methods if needed ...
}
