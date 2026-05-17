<?php

namespace App\Service;

use App\Document\CommandeStat;
use App\Repository\CommandeRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class StatService
{
    public function __construct(
        private DocumentManager $dm,
        private CommandeRepository $commandeRepository
    ) {}

    public function synchroniserStats(): void
    {
        // Récupérer toutes les commandes depuis MySQL
        $commandes = $this->commandeRepository->findAll();

        // Calculer les stats par menu
        $stats = [];
        foreach ($commandes as $commande) {
            $menuId = $commande->getMenu()->getId();
            $menuTitre = $commande->getMenu()->getTitre();

            if (!isset($stats[$menuId])) {
                $stats[$menuId] = [
                    'titre' => $menuTitre,
                    'nombre' => 0,
                    'ca' => 0.0,
                ];
            }

            $stats[$menuId]['nombre']++;
            $stats[$menuId]['ca'] += $commande->getPrixMenu() + ($commande->getPrixLivraison() ?? 0);
        }

        // Sauvegarder dans MongoDB
        foreach ($stats as $menuId => $data) {
            // Chercher si le document existe déjà
            $stat = $this->dm->getRepository(CommandeStat::class)->findOneBy(['menuId' => $menuId]);

            if (!$stat) {
                $stat = new CommandeStat();
                $stat->setMenuId($menuId);
            }

            $stat->setMenuTitre($data['titre']);
            $stat->setNombreCommandes($data['nombre']);
            $stat->setChiffreAffaires($data['ca']);
            $stat->setDateMAJ(new \DateTime());

            $this->dm->persist($stat);
        }

        $this->dm->flush();
    }

    public function getStats(): array
    {
        return $this->dm->getRepository(CommandeStat::class)->findAll();
    }
}