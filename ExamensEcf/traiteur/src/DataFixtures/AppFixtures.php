<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Compte Admin
        $admin = new Utilisateur();
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setEmail('admin@traiteur.fr');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Admin1234!'));
        $admin->setRole(RoleEnum::ADMINISTRATEUR);
        $manager->persist($admin);

        // Compte Employé
        $employe = new Utilisateur();
        $employe->setNom('Employe');
        $employe->setPrenom('Test');
        $employe->setEmail('employe@traiteur.fr');
        $employe->setPassword($this->passwordHasher->hashPassword($employe, 'Employe1234!'));
        $employe->setRole(RoleEnum::EMPLOYE);
        $manager->persist($employe);

        $manager->flush();
    }
}