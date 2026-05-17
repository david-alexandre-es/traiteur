<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/menus', name: 'app_admin_menus')]
    public function menus(MenuRepository $menuRepository): Response
    {
        return $this->render('admin/menus/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('/menus/nouveau', name: 'app_admin_menu_nouveau')]
    public function nouveauMenu(Request $request, EntityManagerInterface $em): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();
            $this->addFlash('success', 'Menu créé avec succès !');
            return $this->redirectToRoute('app_admin_menus');
        }

        return $this->render('admin/menus/form.html.twig', [
            'form' => $form,
            'titre' => 'Nouveau menu',
        ]);
    }

    #[Route('/menus/{id}/modifier', name: 'app_admin_menu_modifier')]
    public function modifierMenu(Menu $menu, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Menu modifié avec succès !');
            return $this->redirectToRoute('app_admin_menus');
        }

        return $this->render('admin/menus/form.html.twig', [
            'form' => $form,
            'titre' => 'Modifier le menu',
        ]);
    }

    #[Route('/menus/{id}/supprimer', name: 'app_admin_menu_supprimer', methods: ['POST'])]
    public function supprimerMenu(Menu $menu, EntityManagerInterface $em): Response
    {
        $em->remove($menu);
        $em->flush();
        $this->addFlash('success', 'Menu supprimé avec succès !');
        return $this->redirectToRoute('app_admin_menus');
    }

    #[Route('/plats', name: 'app_admin_plats')]
public function plats(\App\Repository\PlatRepository $platRepository): Response
{
    return $this->render('admin/plats/index.html.twig', [
        'plats' => $platRepository->findAll(),
    ]);
}

#[Route('/plats/nouveau', name: 'app_admin_plat_nouveau')]
public function nouveauPlat(Request $request, EntityManagerInterface $em): Response
{
    $plat = new \App\Entity\Plat();
    $form = $this->createForm(\App\Form\PlatType::class, $plat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($plat);
        $em->flush();
        $this->addFlash('success', 'Plat créé avec succès !');
        return $this->redirectToRoute('app_admin_plats');
    }

    return $this->render('admin/plats/form.html.twig', [
        'form' => $form,
        'titre' => 'Nouveau plat',
    ]);
}

#[Route('/plats/{id}/modifier', name: 'app_admin_plat_modifier')]
public function modifierPlat(\App\Entity\Plat $plat, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(\App\Form\PlatType::class, $plat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Plat modifié avec succès !');
        return $this->redirectToRoute('app_admin_plats');
    }

    return $this->render('admin/plats/form.html.twig', [
        'form' => $form,
        'titre' => 'Modifier le plat',
    ]);
}

#[Route('/plats/{id}/supprimer', name: 'app_admin_plat_supprimer', methods: ['POST'])]
public function supprimerPlat(\App\Entity\Plat $plat, EntityManagerInterface $em): Response
{
    $em->remove($plat);
    $em->flush();
    $this->addFlash('success', 'Plat supprimé avec succès !');
    return $this->redirectToRoute('app_admin_plats');
}

#[Route('/commandes', name: 'app_admin_commandes')]
public function commandes(\App\Repository\CommandeRepository $commandeRepository): Response
{
    return $this->render('admin/commandes/index.html.twig', [
        'commandes' => $commandeRepository->findAll(),
    ]);
}

#[Route('/commandes/{id}/statut', name: 'app_admin_commande_statut', methods: ['POST'])]
public function changerStatut(\App\Entity\Commande $commande, Request $request, EntityManagerInterface $em): Response
{
    $statut = $request->request->get('statut');
    $commande->setStatut($statut);
    $em->flush();
    $this->addFlash('success', 'Statut mis à jour !');
    return $this->redirectToRoute('app_admin_commandes');
}
#[Route('/avis', name: 'app_admin_avis')]
public function avis(\App\Repository\AvisRepository $avisRepository): Response
{
    return $this->render('admin/avis/index.html.twig', [
        'avis' => $avisRepository->findAll(),
    ]);
}

#[Route('/avis/{id}/valider', name: 'app_admin_avis_valider', methods: ['POST'])]
public function validerAvis(\App\Entity\Avis $avis, EntityManagerInterface $em): Response
{
    $avis->setStatut('valide');
    $em->flush();
    $this->addFlash('success', 'Avis validé !');
    return $this->redirectToRoute('app_admin_avis');
}

#[Route('/avis/{id}/rejeter', name: 'app_admin_avis_rejeter', methods: ['POST'])]
public function rejeterAvis(\App\Entity\Avis $avis, EntityManagerInterface $em): Response
{
    $avis->setStatut('rejete');
    $em->flush();
    $this->addFlash('success', 'Avis rejeté !');
    return $this->redirectToRoute('app_admin_avis');
}
#[Route('/utilisateurs', name: 'app_admin_utilisateurs')]
public function utilisateurs(\App\Repository\UtilisateurRepository $utilisateurRepository): Response
{
    return $this->render('admin/utilisateurs/index.html.twig', [
        'utilisateurs' => $utilisateurRepository->findAll(),
    ]);
}

#[Route('/utilisateurs/{id}/role', name: 'app_admin_utilisateur_role', methods: ['POST'])]
public function changerRole(\App\Entity\Utilisateur $utilisateur, Request $request, EntityManagerInterface $em): Response
{
    $role = $request->request->get('role');
    $utilisateur->setRole(\App\Enum\RoleEnum::from($role));
    $em->flush();
    $this->addFlash('success', 'Rôle mis à jour !');
    return $this->redirectToRoute('app_admin_utilisateurs');
}

#[Route('/utilisateurs/{id}/supprimer', name: 'app_admin_utilisateur_supprimer', methods: ['POST'])]
public function supprimerUtilisateur(\App\Entity\Utilisateur $utilisateur, EntityManagerInterface $em): Response
{
    $em->remove($utilisateur);
    $em->flush();
    $this->addFlash('success', 'Utilisateur supprimé !');
    return $this->redirectToRoute('app_admin_utilisateurs');
}
#[Route('/allergenes', name: 'app_admin_allergenes')]
public function allergenes(\App\Repository\AllergeneRepository $allergeneRepository): Response
{
    return $this->render('admin/allergenes/index.html.twig', [
        'allergenes' => $allergeneRepository->findAll(),
    ]);
}

#[Route('/allergenes/nouveau', name: 'app_admin_allergene_nouveau')]
public function nouveauAllergene(Request $request, EntityManagerInterface $em): Response
{
    $allergene = new \App\Entity\Allergene();
    $form = $this->createForm(\App\Form\AllergeneType::class, $allergene);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($allergene);
        $em->flush();
        $this->addFlash('success', 'Allergène créé !');
        return $this->redirectToRoute('app_admin_allergenes');
    }

    return $this->render('admin/allergenes/form.html.twig', [
        'form' => $form,
        'titre' => 'Nouvel allergène',
    ]);
}

#[Route('/allergenes/{id}/supprimer', name: 'app_admin_allergene_supprimer', methods: ['POST'])]
public function supprimerAllergene(\App\Entity\Allergene $allergene, EntityManagerInterface $em): Response
{
    $em->remove($allergene);
    $em->flush();
    $this->addFlash('success', 'Allergène supprimé !');
    return $this->redirectToRoute('app_admin_allergenes');
}
#[Route('/utilisateurs/{id}/toggle-actif', name: 'app_admin_utilisateur_toggle_actif', methods: ['POST'])]

public function toggleActif(\App\Entity\Utilisateur $utilisateur, EntityManagerInterface $em): Response
{
    $utilisateur->setActif(!$utilisateur->isActif());
    $em->flush();
    $statut = $utilisateur->isActif() ? 'activé' : 'désactivé';
    $this->addFlash('success', 'Compte ' . $statut . ' avec succès !');
    return $this->redirectToRoute('app_admin_utilisateurs');
}
#[Route('/stats', name: 'app_admin_stats')]

public function stats(\App\Service\StatService $statService): Response
{
    $statService->synchroniserStats();
    $stats = $statService->getStats();

    return $this->render('admin/stats.html.twig', [
        'stats' => $stats,
    ]);
}

#[Route('/chiffre-affaires', name: 'app_admin_ca')]
public function chiffreAffaires(Request $request, \App\Repository\CommandeRepository $commandeRepository, \App\Repository\MenuRepository $menuRepository): Response
{
    $menuId = $request->query->get('menu');
    $dateDebut = $request->query->get('date_debut');
    $dateFin = $request->query->get('date_fin');

    $commandes = $commandeRepository->findByFiltersCA($menuId, $dateDebut, $dateFin);

    $total = 0;
    foreach ($commandes as $commande) {
        $total += $commande->getPrixMenu() + ($commande->getPrixLivraison() ?? 0);
    }

    return $this->render('admin/chiffre_affaires.html.twig', [
        'commandes' => $commandes,
        'total' => $total,
        'menus' => $menuRepository->findAll(),
        'selectedMenu' => $menuId,
        'dateDebut' => $dateDebut,
        'dateFin' => $dateFin,
    ]);
}

}