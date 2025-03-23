<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Tailler;
use App\Repository\DevisRepository;
use App\Repository\HaieRepository;
use App\Repository\TaillerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;

class DevisController extends AbstractController
{
    #[Route('/devis/{id}', name: 'devis')]
    public function index(int $id, TaillerRepository $taillerRepository, DevisRepository $devisRepository, ManagerRegistry $doctrine, HaieRepository $haieRepository, UserRepository $utilisateurRepository, UserInterface $userInterface): Response
    {

        $identifantUser = $userInterface->getUserIdentifier();
        $utilisateur = $utilisateurRepository->findOneBy(['email' => $identifantUser]);

        $taillers = $taillerRepository->findBy(['devis' => $id]);

        // (*) Prix unitaire = 30 € pour le Laurier, 35 € pour le Thuya, 28€ pour le Troène */
        $total = 0;
        // Prix = Prix unitaire(*) x Longueur haie
        foreach ($taillers as $tailler) {
            $ligne = $tailler->getHaie()->getPrix() * $tailler->getLongueur();

            //  Si Hauteur > 1m50, multiplier le prix par 1.5
            if ($tailler->getHauteur() > 150) {
                $total = $ligne * 1.5 + $total;
            } else {
                $total = $ligne + $total;
            }
        }

        $devis = $devisRepository->find($id);

        // Si l'utilisateur est une entreprise, appliquer une remise de 10%
        if (in_array("ROLE_ENTREPRISE", $utilisateur->getRoles())) {
            $total = $total - $total * 0.10;
        }

        return $this->render('devis/index.html.twig', [
            'controller_name' => 'DevisController',
            'choix' => $utilisateur->getRoles(),
            'total' => $total,
            'taillers' => $taillers,
            'devis' => $devis,
            'utilisateur' => $utilisateur
        ]);

    }

    #[Route('/suppression/tailler/devis/{id}', name: 'app_suppression_tailler_devis')]
    public function supression(int $id, ManagerRegistry $doctrine, Request $request, TaillerRepository $taillerRepository ): Response
    {
        $tailler = $taillerRepository->find($id);

        $devis = $tailler->getDevis();

        $entityManager = $doctrine->getManager();

        $entityManager->remove($tailler);
        $entityManager->flush();


        return $this->redirectToRoute('devis',  array(
            'id' => $devis->getId(),
        ));    }
}
