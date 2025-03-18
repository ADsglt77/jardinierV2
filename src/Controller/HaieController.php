<?php

namespace App\Controller;

use App\Entity\Haie;
use App\Form\HaieType;
use App\Repository\HaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/haie')]
final class HaieController extends AbstractController
{
    #[Route(name: 'app_haie_index', methods: ['GET'])]
    public function index(HaieRepository $haieRepository): Response
    {
        return $this->render('haie/index.html.twig', [
            'haies' => $haieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_haie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $haie = new Haie();
        $form = $this->createForm(HaieType::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($haie);
            $entityManager->flush();

            return $this->redirectToRoute('app_haie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('haie/new.html.twig', [
            'haie' => $haie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_haie_show', methods: ['GET'])]
    public function show(Haie $haie): Response
    {
        return $this->render('haie/show.html.twig', [
            'haie' => $haie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_haie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Haie $haie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HaieType::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_haie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('haie/edit.html.twig', [
            'haie' => $haie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_haie_delete', methods: ['POST'])]
    public function delete(Request $request, Haie $haie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$haie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($haie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_haie_index', [], Response::HTTP_SEE_OTHER);
    }
}
