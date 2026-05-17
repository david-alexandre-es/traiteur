<?php

namespace App\Controller;

use App\Entity\CommandeStatut;
use App\Repository\CommandeRepository;
use App\Service\MailService;
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
    public function changerStatut(\App\Entity\Commande $commande, Request $request, EntityManagerInterface $em, MailService $mailService): Response
    {
        $statut = $request->request->get('statut');
        $commande->setStatut($statut);

        // Enregistrer dans l'historique
        $commandeStatut = new CommandeStatut();
        $commandeStatut->setStatut($statut);
        $commandeStatut->setDateChangement(new \DateTime());
        $commandeStatut->setCommande($commande);
        $em->persist($commandeStatut);

        // Mail selon le statut
        if ($statut === 'terminee') {
            $mailService->envoyerMailAvisDisponible($commande);
        }

        if ($statut === 'en_attente_retour_materiel') {
            $mailService->envoyerMailRetourMateriel($commande);
        }

        $em->flush();
        $this->addFlash('success', 'Statut mis à jour !');
        return $this->redirectToRoute('app_employe');
    }

    #[Route('/commandes/{id}/annuler', name: 'app_employe_commande_annuler')]
public function annulerCommande(\App\Entity\Commande $commande, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(\App\Form\AnnulationCommandeType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $commande->setStatut('annulee');
        $commande->setMotifAnnulation($data['motif_annulation']);
        $commande->setModeContact($data['mode_contact']);
        $em->flush();
        $this->addFlash('success', 'Commande annulée avec succès !');
        return $this->redirectToRoute('app_employe');
    }

    return $this->render('employe/annuler_commande.html.twig', [
        'form' => $form,
        'commande' => $commande,
    ]);
}

}