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
}