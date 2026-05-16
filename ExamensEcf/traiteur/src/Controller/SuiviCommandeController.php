<?php

namespace App\Controller;

use App\Entity\CommandeStatut;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SuiviCommandeController extends AbstractController
{
    #[Route('/suivi-commande', name: 'app_suivi_commande')]
    #[IsGranted('ROLE_USER')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $utilisateur = $this->getUser();
        $commandes = $commandeRepository->findBy(['utilisateur' => $utilisateur]);

        return $this->render('suivi_commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/suivi-commande/{id}', name: 'app_suivi_commande_detail')]
    #[IsGranted('ROLE_USER')]
    public function detail(int $id, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande || $commande->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('suivi_commande/detail.html.twig', [
            'commande' => $commande,
        ]);
    }
}