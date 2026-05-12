<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employe')]
#[IsGranted('ROLE_EMPLOYE')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'app_employe')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('employe/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/commandes/{id}/statut', name: 'app_employe_commande_statut', methods: ['POST'])]
    public function changerStatut(\App\Entity\Commande $commande, Request $request, EntityManagerInterface $em): Response
    {
        $statut = $request->request->get('statut');
        $commande->setStatut($statut);
        $em->flush();
        $this->addFlash('success', 'Statut mis à jour !');
        return $this->redirectToRoute('app_employe');
    }
}