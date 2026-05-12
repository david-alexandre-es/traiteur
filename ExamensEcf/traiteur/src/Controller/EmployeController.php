<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employe')]
#[IsGranted('ROLE_EMPLOYE')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig');
    }
}