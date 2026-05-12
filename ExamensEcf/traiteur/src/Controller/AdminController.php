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
}