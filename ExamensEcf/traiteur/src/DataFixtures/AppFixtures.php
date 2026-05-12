<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Menu;
use App\Entity\Theme;
use App\Entity\Regime;
use App\Entity\Plat;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Comptes utilisateurs
        $admin = new Utilisateur();
        $admin->setNom('Admin')->setPrenom('Super')->setEmail('admin@traiteur.fr');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Admin1234!'));
        $admin->setRole(RoleEnum::ADMINISTRATEUR);
        $manager->persist($admin);

        $employe = new Utilisateur();
        $employe->setNom('Employe')->setPrenom('Test')->setEmail('employe@traiteur.fr');
        $employe->setPassword($this->passwordHasher->hashPassword($employe, 'Employe1234!'));
        $employe->setRole(RoleEnum::EMPLOYE);
        $manager->persist($employe);

        // Thèmes
        $theme1 = new Theme();
        $theme1->setLibelle('Mariage');
        $manager->persist($theme1);

        $theme2 = new Theme();
        $theme2->setLibelle('Entreprise');
        $manager->persist($theme2);

        // Régimes
        $regime1 = new Regime();
        $regime1->setLibelle('Végétarien');
        $manager->persist($regime1);

        $regime2 = new Regime();
        $regime2->setLibelle('Sans gluten');
        $manager->persist($regime2);

        // Plats
        $plat1 = new Plat();
        $plat1->setTitrePlat('Salade Caesar');
        $plat1->setNote('Salade fraîche avec croûtons');
        $manager->persist($plat1);

        $plat2 = new Plat();
        $plat2->setTitrePlat('Boeuf Bourguignon');
        $plat2->setNote('Plat traditionnel français');
        $manager->persist($plat2);
        
        // Menus
        $menu1 = new Menu();
        $menu1->setTitre('Menu Prestige');
        $menu1->setDescription('Notre menu haut de gamme pour vos mariages');
        $menu1->setPrixParPersonne(85.00);
        $menu1->setNombrePersonneMinimum(10);
        $menu1->setQuantiteRestante(50);
        $menu1->setTheme($theme1);
        $menu1->addRegime($regime1);
        $menu1->addPlat($plat1);
        $menu1->addPlat($plat2);
        $manager->persist($menu1);

        $menu2 = new Menu();
        $menu2->setTitre('Menu Business');
        $menu2->setDescription('Parfait pour vos événements professionnels');
        $menu2->setPrixParPersonne(45.00);
        $menu2->setNombrePersonneMinimum(5);
        $menu2->setQuantiteRestante(30);
        $menu2->setTheme($theme2);
        $menu2->addRegime($regime2);
        $menu2->addPlat($plat1);
        $manager->persist($menu2);

        $manager->flush();
    }
}
