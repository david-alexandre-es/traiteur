<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository;
use App\Service\LivraisonService;
use App\Service\MailService;
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
    public function commander(Request $request, EntityManagerInterface $em, MenuRepository $menuRepository, MailService $mailService, LivraisonService $livraisonService): Response
    {
        $commande = new Commande();

        $menuId = $request->query->get('menu');
        if ($menuId) {
            $menu = $menuRepository->find($menuId);
            if ($menu) {
                $commande->setMenu($menu);
            }
        }

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setNumeroCommande('CMD-' . uniqid());
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut('en_attente');
            $commande->setPretMateriel(false);
            $commande->setRetourMateriel(false);

            // Calcul du prix de base
            $menu = $commande->getMenu();
            $nombrePersonnes = $commande->getNombrePersonne();
            $prixBase = $menu->getPrixParPersonne() * $nombrePersonnes;

            // Réduction 10% si 5 personnes de plus que le minimum
            if ($nombrePersonnes >= $menu->getNombrePersonneMinimum() + 5) {
                $prixBase = $prixBase * 0.90;
            }

            $commande->setPrixMenu($prixBase);

            // Calcul des frais de livraison
            $fraisLivraison = $livraisonService->calculerFraisLivraison(
                $commande->getAdressePrestation(),
                $commande->getVillePrestation()
            );
            $commande->setPrixLivraison($fraisLivraison);

            $commande->setUtilisateur($this->getUser());

            $em->persist($commande);
            $em->flush();

            $mailService->envoyerConfirmationCommande($commande);

            $this->addFlash('success', 'Votre commande a été passée avec succès !');
            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/commander.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/avis/nouveau', name: 'app_avis_nouveau')]
    public function nouvelAvis(Request $request, EntityManagerInterface $em): Response
    {
        $avis = new \App\Entity\Avis();
        $form = $this->createForm(\App\Form\AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setStatut('en_attente');
            $avis->setUtilisateur($this->getUser());

            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Votre avis a été soumis et sera validé prochainement !');
            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/avis.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request, EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        $form = $this->createForm(\App\Form\ProfilType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('compte/profil.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/commandes/{id}/annuler', name: 'app_commande_annuler', methods: ['POST'])]
    public function annulerCommande(\App\Entity\Commande $commande, EntityManagerInterface $em): Response
    {
        if ($commande->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($commande->getStatut() === 'en_attente') {
            $commande->setStatut('annulee');
            $em->flush();
            $this->addFlash('success', 'Votre commande a été annulée.');
        } else {
            $this->addFlash('error', 'Cette commande ne peut plus être annulée.');
        }

        return $this->redirectToRoute('app_compte');
    }

    #[Route('/commandes/{id}/modifier', name: 'app_commande_modifier')]
public function modifierCommande(\App\Entity\Commande $commande, Request $request, EntityManagerInterface $em, LivraisonService $livraisonService): Response
{
    if ($commande->getUtilisateur() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    if ($commande->getStatut() !== 'en_attente') {
        $this->addFlash('error', 'Cette commande ne peut plus être modifiée.');
        return $this->redirectToRoute('app_compte');
    }

    $form = $this->createForm(CommandeType::class, $commande, [
        'menu_disabled' => true,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $nombrePersonnes = $commande->getNombrePersonne();
        $menu = $commande->getMenu();
        $prixBase = $menu->getPrixParPersonne() * $nombrePersonnes;

        // Réduction 10% si 5 personnes de plus que le minimum
        if ($nombrePersonnes >= $menu->getNombrePersonneMinimum() + 5) {
            $prixBase = $prixBase * 0.90;
        }

        $commande->setPrixMenu($prixBase);

        // Recalcul des frais de livraison
        $fraisLivraison = $livraisonService->calculerFraisLivraison(
            $commande->getAdressePrestation(),
            $commande->getVillePrestation()
        );
        $commande->setPrixLivraison($fraisLivraison);

        $em->flush();
        $this->addFlash('success', 'Votre commande a été modifiée.');
        return $this->redirectToRoute('app_compte');
    }

    return $this->render('compte/modifier_commande.html.twig', [
        'form' => $form,
        'commande' => $commande,
    ]);
}
}