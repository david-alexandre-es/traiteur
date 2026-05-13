<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Enum\RoleEnum;
use App\Form\RegistrationFormType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailService $mailService
    ): Response {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword(
                $passwordHasher->hashPassword($utilisateur, $form->get('plainPassword')->getData())
            );

            $utilisateur->setRole(RoleEnum::UTILISATEUR);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Envoi du mail de bienvenue
            $mailService->envoyerMailBienvenue($utilisateur);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}