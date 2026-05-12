<?php


namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\ThemeRepository;
use App\Repository\RegimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/menus', name: 'app_menus')]
    public function index(
        Request $request,
        MenuRepository $menuRepository,
        ThemeRepository $themeRepository,
        RegimeRepository $regimeRepository
    ): Response {
        $theme = $request->query->get('theme');
        $regime = $request->query->get('regime');

        $menus = $menuRepository->findByFilters($theme, $regime);
        $themes = $themeRepository->findAll();
        $regimes = $regimeRepository->findAll();

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'themes' => $themes,
            'regimes' => $regimes,
            'selectedTheme' => $theme,
            'selectedRegime' => $regime,
        ]);
    }
}