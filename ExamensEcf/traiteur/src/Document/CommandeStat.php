<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: "commande_stats")]
class CommandeStat
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private string $menuTitre;

    #[MongoDB\Field(type: 'int')]
    private int $menuId;

    #[MongoDB\Field(type: 'int')]
    private int $nombreCommandes;

    #[MongoDB\Field(type: 'float')]
    private float $chiffreAffaires;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $dateMAJ;

    public function getId(): ?string { return $this->id; }

    public function getMenuTitre(): string { return $this->menuTitre; }
    public function setMenuTitre(string $menuTitre): static { $this->menuTitre = $menuTitre; return $this; }

    public function getMenuId(): int { return $this->menuId; }
    public function setMenuId(int $menuId): static { $this->menuId = $menuId; return $this; }

    public function getNombreCommandes(): int { return $this->nombreCommandes; }
    public function setNombreCommandes(int $nombreCommandes): static { $this->nombreCommandes = $nombreCommandes; return $this; }

    public function getChiffreAffaires(): float { return $this->chiffreAffaires; }
    public function setChiffreAffaires(float $chiffreAffaires): static { $this->chiffreAffaires = $chiffreAffaires; return $this; }

    public function getDateMAJ(): \DateTime { return $this->dateMAJ; }
    public function setDateMAJ(\DateTime $dateMAJ): static { $this->dateMAJ = $dateMAJ; return $this; }
}