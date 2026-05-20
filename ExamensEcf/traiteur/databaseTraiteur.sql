
/* Base de données Traiteur Julie (commande pour recuperer par synfony : schema:create --dump-sql) */


/* Suppression des tables existantes pour éviter les conflits */

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS allergene;
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS commande_statut;
DROP TABLE IF EXISTS horaire;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS menu_regime;
DROP TABLE IF EXISTS menu_plat;
DROP TABLE IF EXISTS menu_image;
DROP TABLE IF EXISTS plat;
DROP TABLE IF EXISTS plat_allergene;
DROP TABLE IF EXISTS regime;
DROP TABLE IF EXISTS reset_password_request;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS theme;
DROP TABLE IF EXISTS messenger_messages;
SET FOREIGN_KEY_CHECKS = 1;


/* Création des tables */


CREATE TABLE allergene (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, description VARCHAR(50) DEFAULT NULL, statut VARCHAR(50) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_8F91ABF0FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, numero_commande VARCHAR(50) NOT NULL, date_commande DATE NOT NULL, date_prestation DATE NOT NULL, heure_livraison VARCHAR(50) DEFAULT NULL, adresse_prestation VARCHAR(255) DEFAULT NULL, ville_prestation VARCHAR(100) DEFAULT NULL, prix_menu DOUBLE PRECISION NOT NULL, prix_livraison DOUBLE PRECISION DEFAULT NULL, nombre_personne INT NOT NULL, statut VARCHAR(50) NOT NULL, pret_materiel TINYINT NOT NULL, retour_materiel TINYINT NOT NULL, motif_annulation VARCHAR(50) DEFAULT NULL, mode_contact VARCHAR(50) DEFAULT NULL, utilisateur_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_6EEAA67DFB88E14F (utilisateur_id), INDEX IDX_6EEAA67DCCD7E912 (menu_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE commande_statut (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(50) NOT NULL, date_changement DATETIME NOT NULL, commande_id INT NOT NULL, INDEX IDX_E7300B6A82EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE horaire (id INT AUTO_INCREMENT NOT NULL, jour VARCHAR(50) NOT NULL, heure_ouverture VARCHAR(50) NOT NULL, heure_fermeture VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, nombre_personne_minimum INT NOT NULL, prix_par_personne DOUBLE PRECISION NOT NULL, regime VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, quantite_restante INT DEFAULT NULL, theme_id INT NOT NULL, INDEX IDX_7D053A9359027487 (theme_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE menu_regime (menu_id INT NOT NULL, regime_id INT NOT NULL, INDEX IDX_79C112A4CCD7E912 (menu_id), INDEX IDX_79C112A435E7D534 (regime_id), PRIMARY KEY (menu_id, regime_id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE menu_plat (menu_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_E8775249CCD7E912 (menu_id), INDEX IDX_E8775249D73DB560 (plat_id), PRIMARY KEY (menu_id, plat_id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE menu_image (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, menu_id INT NOT NULL, INDEX IDX_54912738CCD7E912 (menu_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, titre_plat VARCHAR(50) NOT NULL, note VARCHAR(50) DEFAULT NULL, photo LONGBLOB DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE plat_allergene (plat_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_6FA44BBFD73DB560 (plat_id), INDEX IDX_6FA44BBF4646AB2 (allergene_id), PRIMARY KEY (plat_id, allergene_id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE regime (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(50) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, pays VARCHAR(50) DEFAULT NULL, adresse_postale VARCHAR(50) DEFAULT NULL, role VARCHAR(255) NOT NULL, actif TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;
CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4;


/* Ajout des clés étrangères */


ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);
ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);
ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id);
ALTER TABLE commande_statut ADD CONSTRAINT FK_E7300B6A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id);
ALTER TABLE menu ADD CONSTRAINT FK_7D053A9359027487 FOREIGN KEY (theme_id) REFERENCES theme (id);
ALTER TABLE menu_regime ADD CONSTRAINT FK_79C112A4CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE;
ALTER TABLE menu_regime ADD CONSTRAINT FK_79C112A435E7D534 FOREIGN KEY (regime_id) REFERENCES regime (id) ON DELETE CASCADE;
ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE;
ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE;
ALTER TABLE menu_image ADD CONSTRAINT FK_54912738CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id);
ALTER TABLE plat_allergene ADD CONSTRAINT FK_6FA44BBFD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE;
ALTER TABLE plat_allergene ADD CONSTRAINT FK_6FA44BBF4646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE;
ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id);


/* insérer des données de test */


/* Thèmes d'événements */
INSERT INTO theme (libelle) VALUES ('Mariage'), ('Anniversaire'), ('Séminaire'), ('Cocktail'), ('Baptême'), ('noël');

/* Régimes alimentaires */
INSERT INTO regime (libelle) VALUES ('Végétarien'), ('Vegan'), ('Sans gluten'), ('Halal'), ('Casher');

/* Allergènes courants */
INSERT INTO allergene (libelle) VALUES ('Gluten'), ('Crustacés'), ('Oeufs'), ('Poisson'), ('Arachides'), ('Soja'), ('Lait'), ('Fruits à coque');

/* Utilisateurs , admin et employé (mot de passe : Admin1234!) */
INSERT INTO utilisateur (email, password, nom, prenom, telephone, ville, pays, adresse_postale, role, actif) VALUES
('admin@traiteur.fr', '$2y$13$hKqMPHFHDGlSCUm7YZVGCOXrBMFYkJhvSJOWWMGCHhM8vYqKqBq2u', 'Admin', 'julie', '0600000000', 'Bordeaux', 'France', '1 rue de la Gastronomie', 'ROLE_ADMIN', 1),
('employe@traiteur.fr', '$2y$13$hKqMPHFHDGlSCUm7YZVGCOXrBMFYkJhvSJOWWMGCHhM8vYqKqBq2u', 'Dupont', 'Marie', '0600000001', 'Bordeaux', 'France', '2 rue du Travail', 'ROLE_EMPLOYE', 1),
('penelope@traiteur.fr', '$2y$13$hKqMPHFHDGlSCUm7YZVGCOXrBMFYkJhvSJOWWMGCHhM8vYqKqBq2u', 'Martin', 'Jean', '0600000002', 'Bordeaux', 'France', '3 rue du Client', 'ROLE_USER', 1);

/* Plats proposés */
INSERT INTO plat (titre_plat, note) VALUES
('Salade César', 'Entrée fraîche et légère'),
('Magret de canard', 'Plat principal'),
('Tarte Tatin', 'Dessert maison'),
('Velouté de potiron', 'Entrée chaude de saison'),
('Filet de boeuf', 'Plat principal premium'),
('Mousse au chocolat', 'Dessert gourmand');

/* Menus */
INSERT INTO menu (titre, nombre_personne_minimum, prix_par_personne, description, quantite_restante, theme_id) VALUES
('Menu Prestige', 10, 85.00, 'Notre menu haut de gamme pour vos mariages', 50, 1),
('Menu Découverte', 5, 45.00, 'Un menu équilibré pour vos séminaires et réunions', 30, 3),
('Menu Cocktail', 20, 25.00, 'Des bouchées raffinées pour vos cocktails', 100, 4);

/* Horaires d'ouverture et de fermeture */
INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES
('Lundi', '09:00', '18:00'),
('Mardi', '09:00', '18:00'),
('Mercredi', '09:00', '18:00'),
('Jeudi', '09:00', '18:00'),
('Vendredi', '09:00', '18:00'),
('Samedi', '09:00', '12:00'),
('Dimanche', 'Fermé', 'Fermé');