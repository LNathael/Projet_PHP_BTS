-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour projet_php_
CREATE DATABASE IF NOT EXISTS `projet_php_` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `projet_php_`;

-- Listage de la structure de table projet_php_. articles
CREATE TABLE IF NOT EXISTS `articles` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `auteur` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. avis
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `id_produit` int DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `commentaire` text NOT NULL,
  `note` int DEFAULT NULL,
  `date_avis` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type_contenu` enum('recette','programme','produit') NOT NULL,
  `contenu_id` int NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_produit` (`id_produit`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `avis_chk_1` CHECK ((`note` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. commandes
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `date_commande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut_commande` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_commande`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. details_commande
CREATE TABLE IF NOT EXISTS `details_commande` (
  `id_detail_commande` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail_commande`),
  KEY `id_commande` (`id_commande`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE,
  CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. entrainements
CREATE TABLE IF NOT EXISTS `entrainements` (
  `id_entrainement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) DEFAULT 'Séance sans titre',
  `id_utilisateur` int NOT NULL,
  `date` date NOT NULL,
  `description` text,
  PRIMARY KEY (`id_entrainement`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `entrainements_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. exercices
CREATE TABLE IF NOT EXISTS `exercices` (
  `id_exercice` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_exercice`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_salon` int NOT NULL,
  `contenu` text NOT NULL,
  `date_message` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_recette` int DEFAULT NULL,
  `id_produit` int DEFAULT NULL,
  `reply_to` int DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_salon` (`id_salon`),
  KEY `id_recette` (`id_recette`),
  KEY `id_produit` (`id_produit`),
  KEY `fk_reply_to` (`reply_to`),
  KEY `idx_id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `fk_messages_utilisateurs` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `fk_reply_to` FOREIGN KEY (`reply_to`) REFERENCES `messages` (`id_message`) ON DELETE SET NULL,
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_salon`) REFERENCES `salons` (`id_salon`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`id_recette`) REFERENCES `recettes` (`id_recette`) ON DELETE SET NULL,
  CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. panier
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_panier`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. produits
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `quantite_disponible` int NOT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `vues` int DEFAULT '0',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. programmes
CREATE TABLE IF NOT EXISTS `programmes` (
  `id_programme` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `objectif` varchar(255) NOT NULL,
  `frequence` int DEFAULT NULL,
  `niveau` varchar(50) NOT NULL,
  `programme` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_programme`),
  KEY `fk_user_programs_user` (`id_utilisateur`) USING BTREE,
  CONSTRAINT `fk_user_programs_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. recettes
CREATE TABLE IF NOT EXISTS `recettes` (
  `id_recette` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categorie` enum('Prise de masse','Maintien','Sèche') NOT NULL,
  `ingredients` text NOT NULL,
  `etapes` text NOT NULL,
  `id_utilisateur` int NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL,
  `vues` int DEFAULT '0',
  PRIMARY KEY (`id_recette`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `recettes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. salons
CREATE TABLE IF NOT EXISTS `salons` (
  `id_salon` int NOT NULL AUTO_INCREMENT,
  `nom_salon` varchar(50) NOT NULL,
  `description` text,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_salon`),
  UNIQUE KEY `nom_salon` (`nom_salon`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. seance_exercice
CREATE TABLE IF NOT EXISTS `seance_exercice` (
  `id_seance_exercice` int NOT NULL AUTO_INCREMENT,
  `id_entrainement` int NOT NULL,
  `id_exercice` int NOT NULL,
  `poids` float NOT NULL,
  `repetitions` int NOT NULL,
  `series` int NOT NULL,
  `ressenti` text,
  `date` date NOT NULL,
  PRIMARY KEY (`id_seance_exercice`),
  KEY `id_entrainement` (`id_entrainement`),
  CONSTRAINT `seance_exercice_ibfk_1` FOREIGN KEY (`id_entrainement`) REFERENCES `entrainements` (`id_entrainement`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table projet_php_. utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `bio` text,
  `objectifs_fitness` text,
  `objectif` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','administrateur','super_administrateur','coach','commercial') DEFAULT 'utilisateur',
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('Homme','Femme','Autre') DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `photo_profil` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `tentatives` int DEFAULT '0',
  `bloque` tinyint(1) DEFAULT '0',
  `dernier_echec` datetime DEFAULT NULL,
  `notifications_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `check_role` CHECK ((`role` in (_utf8mb4'utilisateur',_utf8mb4'administrateur',_utf8mb4'super_administrateur',_utf8mb4'coach',_utf8mb4'commercial')))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
