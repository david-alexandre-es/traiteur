# Traiteur julie

Application web et web mobile de gestion de traiteur pour évenement développée avec Symfony 7.

## Les Technologies utilisées sont :

- PHP 8.2     ( pour le language back end )
- Symfony 7   ( le framework php pour le back end )
- Twig        ( pour les templates html avec synfony)
- Doctrine ORM   (pour gerer la base de données )
- MySQL          ( pour la base de donnée relationnelle )
- Bootstrap 5    ( framework css pour le language front end )
- VichUploaderBundle   ( pour gerer les telechargement des images )
- SymfonyCasts Reset Password Bundle   ( pour reinitialiser le mot de passe )

## Les Prérequis sont :

- PHP 8.2 ou supérieur ( pour le back end )
- Composer  ( pour les dépendances avec php )
- synfony cli  ( pour generer un serveur en local )
- xampp ou autres  ( pour gerer le serveur local a MySql)
- heidi sql ou autres  ( pour gerer la base de données )
- navigateur web chrome ou autre  ( pour tester le site )
- vs code ou autres  ( pour éditer du code )

## Installation en local

### en premier Cloner le repository

```bash ( dans la console )
git clone https://github.com/Votre_pseudo_git/traiteur.git
cd traiteur
```

### en deuxième Installer les dépendances

```bash ( dans la console )
composer install
```

### en troisième pour Configurer l'environnement

pour copier le fichier `.env` et le configurer :

```bash
cp .env .env.local
```

aprés Modifier le fichier `.env.local` avec les informations de votre base de données :

DATABASE_URL="mysql://VOTRE_NOM:VOTRE_MOT_DE_PASSE@127.0.0.1:3306/traiteur"


### en quatrième pour créer la base de données

```bash ( dans la console )
php bin/console doctrine:database:create
```

### en cinquième pour importer le fichier SQL

Importer le fichier `databasetraiteur.sql` dans votre base de données sur HeidiSQL ou autres logiciels utilisant mysql ou alors en ligne de commande :

```bash ( dans la console )
mysql -u VOTRE_NOM -p traiteur < databasetraiteur.sql
```

### en sixème pour créer le dossier uploads ( pour emplacements des images )

```bash ( dans la console )
mkdir -p public/uploads/menus
```

### en septième pour demarer le serveur symfony

```bash ( dans la console )
symfony server:start
```

L'application est accessible sur l'url  `http://localhost:8000`

## pour les comptes de test

     | Rôle |          |  Email |          | Mot de passe |

| Administrateur | admin@traiteur.fr        | Admin1234! |
| Employé        | employe@traiteur.fr      | Admin1234! |
| Utilisateur    | penelope@traiteur.fr     | Admin1234! |

## pour les fonctionnalités

### pour espace visiteurs
- Catalogue des menus avec filtres
- Détail des menus
- Formulaire de contact
- Mentions légales et CGV

### pour espace utilisateurs
- Inscription et connexion
- Passer une commande
- Suivi des commandes
- Modifier/annuler une commande
- Laisser un avis
- Modifier son profil

###  pour espace employé
- Gestion des commandes
- Mise à jour des statuts

### et pour espace administrateur
- Gestion des menus et plats
- Gestion des utilisateurs
- Gestion des avis
- Gestion des allergènes
- Chiffre d'affaires
- Statistiques

## Auteur

**David-Alexandre Escavabaja**

## mon lien git

https://github.com/david-alexandre-es

Merci pour la lecture ! 