<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/compte')]
#[IsGranted('ROLE_USER')]
class CompteController extends AbstractController
{
    #[Route('/', name: 'app_compte')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findBy(['utilisateur' => $this->getUser()]);

        return $this->render('compte/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commander', name: 'app_commander')]
    public function commander(Request $request, EntityManagerInterface $em): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setNumeroCommande('CMD-' . uniqid());
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut('en_attente');
            $commande->setPretMateriel(false);
            $commande->setRetourMateriel(false);
            $commande->setPrixMenu($commande->getMenu()->getPrixParPersonne() * $commande->getNombrePersonne());
            $commande->setUtilisateur($this->getUser());

            $em->persist($commande);
            $em->flush();

            $this->addFlash('success', 'Votre commande a été passée avec succès !');
            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/commander.html.twig', [
            'form' => $form,
        ]);
    }
}