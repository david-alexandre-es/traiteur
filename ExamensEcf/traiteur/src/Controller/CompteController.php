<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/compte')]
#[IsGranted('ROLE_USER')]
class CompteController extends AbstractController
{
    #[Route('/', name: 'app_compte')]
    public function index(): Response
    {
        return $this->render('compte/index.html.twig');
    }
}