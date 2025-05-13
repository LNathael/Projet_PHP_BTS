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

-- Listage des données de la table projet_php_.articles : ~5 rows (environ)
DELETE FROM `articles`;
INSERT INTO `articles` (`id_article`, `titre`, `contenu`, `date_creation`, `auteur`) VALUES
	(4, 'Les 5 meilleurs exercices pour perdre du poids', 'Découvrez les exercices les plus efficaces pour brûler des calories et perdre du poids rapidement. Ces exercices incluent le HIIT, la course à pied, le vélo, la natation et les exercices de musculation.', '2025-04-12 22:00:00', 'Jean Dupont'),
	(5, 'Comment planifier vos repas pour une alimentation équilibrée', 'Planifier vos repas est essentiel pour maintenir une alimentation saine. Apprenez à équilibrer vos macronutriments, à inclure des légumes dans chaque repas et à éviter les aliments transformés.', '2025-04-11 22:00:00', 'Marie Curie'),
	(6, 'Les bienfaits de la musculation pour la santé mentale', 'La musculation ne se limite pas à la construction musculaire. Elle améliore également la santé mentale en réduisant le stress, en augmentant la confiance en soi et en libérant des endorphines.', '2025-04-10 22:00:00', 'Paul Martin'),
	(7, 'Recettes saines et rapides pour les sportifs', 'Découvrez des recettes faciles et rapides à préparer pour les sportifs. Ces recettes incluent des smoothies protéinés, des salades riches en protéines et des snacks énergétiques faits maison.', '2025-04-09 22:00:00', 'Sophie Lambert'),
	(8, 'Les erreurs courantes à éviter en salle de sport', 'Beaucoup de débutants commettent des erreurs en salle de sport, comme soulever des poids trop lourds ou négliger l\'échauffement. Apprenez à éviter ces erreurs pour maximiser vos résultats.', '2025-04-08 22:00:00', 'Alexandre Rousseau');

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

-- Listage des données de la table projet_php_.avis : ~28 rows (environ)
DELETE FROM `avis`;
INSERT INTO `avis` (`id_avis`, `id_produit`, `id_utilisateur`, `commentaire`, `note`, `date_avis`, `type_contenu`, `contenu_id`) VALUES
	(2, NULL, 2, 'miam super bonn !!!!!', 5, '2025-01-05 22:57:03', 'recette', 3),
	(3, NULL, 1, 'incroyablement bon !!!', 5, '2025-04-04 18:26:28', 'recette', 5),
	(4, NULL, 1, 'Recette délicieuse et facile à préparer !', 5, '2025-04-08 08:52:10', 'recette', 3),
	(5, NULL, 2, 'Très bon goût, mais un peu trop salé à mon goût.', 4, '2025-04-08 08:52:10', 'recette', 4),
	(6, NULL, 1, 'Une recette parfaite pour un dîner rapide.', 5, '2025-04-08 08:52:10', 'recette', 5),
	(7, NULL, 2, 'Le smoothie est excellent, très rafraîchissant.', 5, '2025-04-08 08:52:10', 'recette', 6),
	(8, NULL, 1, 'Les pancakes sont moelleux et délicieux !', 5, '2025-04-08 08:52:10', 'recette', 7),
	(9, NULL, 1, 'La protéine a un goût incroyable, parfait pour mes entraînements', 5, '2025-04-08 08:56:47', 'produit', 10),
	(10, 10, 2, 'Excellent produit, le goût est parfait et les résultats sont visibles rapidement.', 5, '2025-04-08 08:57:49', 'produit', 10),
	(11, 10, 1, 'Bon produit, mais un peu cher pour la quantité.', 4, '2025-04-08 08:57:49', 'produit', 10),
	(12, 11, 2, 'Très efficace pour la perte de poids, je recommande !', 5, '2025-04-08 08:57:49', 'produit', 11),
	(13, 11, 1, 'Bon brûleur de graisses, mais il faut être régulier pour voir les effets.', 4, '2025-04-08 08:57:49', 'produit', 11),
	(14, 12, 2, 'La créatine est de très bonne qualité, je l’utilise depuis un mois et je vois déjà des résultats.', 5, '2025-04-08 08:57:49', 'produit', 12),
	(15, 12, 1, 'Produit efficace, mais il faut bien respecter les doses.', 4, '2025-04-08 08:57:49', 'produit', 12),
	(16, 13, 2, 'Délicieuse barre protéinée, parfaite pour une collation rapide.', 5, '2025-04-08 08:57:49', 'produit', 13),
	(17, 13, 1, 'Un peu trop sucrée à mon goût, mais très pratique.', 4, '2025-04-08 08:57:49', 'produit', 13),
	(18, 14, 2, 'Super mélange de superaliments, je me sens plus énergique depuis que je l’utilise.', 5, '2025-04-08 08:57:49', 'produit', 14),
	(19, 14, 1, 'Bon produit, mais le goût est un peu fort si pris seul.', 4, '2025-04-08 08:57:49', 'produit', 14),
	(20, NULL, 2, 'Une recette simple et rapide, parfaite pour les soirs de semaine.', 4, '2025-04-08 08:59:54', 'recette', 3),
	(21, NULL, 1, 'J\'ai adoré cette recette, elle est devenue un classique chez moi.', 5, '2025-04-08 08:59:54', 'recette', 4),
	(22, NULL, 2, 'Le goût est bon, mais j\'ai dû ajuster les quantités pour que ce soit parfait.', 3, '2025-04-08 08:59:54', 'recette', 5),
	(23, NULL, 1, 'Très rafraîchissant, idéal pour l\'été !', 5, '2025-04-08 08:59:54', 'recette', 6),
	(24, NULL, 2, 'Les pancakes étaient moelleux, mais un peu trop sucrés à mon goût.', 4, '2025-04-08 08:59:54', 'recette', 7),
	(25, NULL, 1, 'Une recette délicieuse et facile à suivre, je recommande !', 5, '2025-04-08 08:59:54', 'recette', 3),
	(26, NULL, 2, 'La texture était parfaite, mais j\'ai ajouté un peu plus d\'épices pour relever le goût.', 4, '2025-04-08 08:59:54', 'recette', 4),
	(27, NULL, 1, 'Un vrai régal, mes enfants ont adoré !', 5, '2025-04-08 08:59:54', 'recette', 5),
	(28, NULL, 2, 'Le smoothie est très bon, mais un peu trop épais à mon goût.', 3, '2025-04-08 08:59:54', 'recette', 6),
	(29, NULL, 1, 'Les pancakes étaient parfaits, je les referai sans hésiter.', 5, '2025-04-08 08:59:54', 'recette', 7);

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.commandes : ~12 rows (environ)
DELETE FROM `commandes`;
INSERT INTO `commandes` (`id_commande`, `id_utilisateur`, `date_commande`, `statut_commande`, `total`) VALUES
	(1, 1, '2025-03-31 13:07:30', 'validée', 40000.00),
	(2, 1, '2025-03-31 13:08:40', 'validée', 40000.00),
	(3, 1, '2025-03-31 13:09:16', 'validée', 10000.00),
	(4, 1, '2025-03-31 13:14:14', 'validée', 10000.00),
	(11, 1, '2025-04-04 18:22:01', 'validée', 52.30),
	(12, 1, '2025-04-04 18:22:01', 'validée', 52.30),
	(14, 1, '2025-04-04 18:42:45', 'validée', 29.90),
	(17, 10, '2025-04-15 09:13:17', 'validée', 24.90),
	(18, 10, '2025-04-15 09:13:17', 'validée', 24.90),
	(19, 10, '2025-04-15 11:32:49', 'validée', 24.90),
	(20, 10, '2025-04-15 11:32:49', 'validée', 24.90),
	(21, 1, '2025-05-12 11:46:14', 'validée', 24.90),
	(22, 1, '2025-05-12 11:46:14', 'en attente', 24.90);

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.details_commande : ~5 rows (environ)
DELETE FROM `details_commande`;
INSERT INTO `details_commande` (`id_detail_commande`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
	(5, 11, 11, 2, 24.90),
	(6, 11, 13, 1, 2.50),
	(9, 17, 11, 1, 24.90),
	(10, 19, 11, 1, 24.90),
	(11, 21, 11, 1, 24.90);

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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.entrainements : ~18 rows (environ)
DELETE FROM `entrainements`;
INSERT INTO `entrainements` (`id_entrainement`, `titre`, `id_utilisateur`, `date`, `description`) VALUES
	(1, '', 1, '2025-03-21', 'Entraînement de musculation'),
	(11, '', 1, '2025-03-28', NULL),
	(13, '', 1, '2025-03-28', NULL),
	(15, '', 1, '2025-03-28', NULL),
	(17, '', 1, '2025-03-28', NULL),
	(19, '', 1, '2025-03-28', NULL),
	(21, '', 1, '2025-03-28', NULL),
	(23, '', 1, '2025-03-28', NULL),
	(25, '', 1, '2025-03-28', NULL),
	(26, '', 1, '2025-03-28', NULL),
	(27, '', 1, '2025-04-01', NULL),
	(28, '', 1, '2025-04-01', NULL),
	(29, 'Séance sans titre', 1, '2025-04-02', NULL),
	(30, 'Séance sans titre', 1, '2025-04-02', NULL),
	(31, 'Séance sans titre', 1, '2025-04-02', NULL),
	(32, 'pecs', 1, '2025-04-02', NULL),
	(33, 'Séance de musculation', 1, '2025-04-01', 'Musculation pour le haut du corps'),
	(34, 'Séance de cardio', 1, '2025-04-03', 'Course à pied de 5 km'),
	(35, 'Séance sans titre', 1, '2025-04-08', NULL),
	(36, 'leg day', 1, '2025-04-08', NULL),
	(37, 'Entraînement Pectoraux', 1, '2025-05-01', 'Séance axée sur le développement des pectoraux.'),
	(38, 'Entraînement Jambes', 1, '2025-05-03', 'Renforcement musculaire des jambes.'),
	(39, 'Cardio et Abdominaux', 1, '2025-05-05', 'Séance combinant cardio et exercices pour les abdominaux.');

-- Listage de la structure de table projet_php_. exercices
CREATE TABLE IF NOT EXISTS `exercices` (
  `id_exercice` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_exercice`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.exercices : ~16 rows (environ)
DELETE FROM `exercices`;
INSERT INTO `exercices` (`id_exercice`, `nom`, `description`, `image_path`, `video_path`, `categorie`) VALUES
	(1, 'Développé couché', 'Exercice pour les pectoraux', NULL, NULL, 'Pectoraux'),
	(2, 'Squat', 'Exercice pour les jambes', NULL, NULL, 'Jambes'),
	(3, 'Tractions', 'Exercice pour le dos', NULL, NULL, 'Dos'),
	(4, 'Dumbbell Goblet Squat', 'Hold the weight tucked into your upper chest area, keeping your elbows in. Your feet should be slightly wider than shoulder width.\r\n2\r\nSink down into the squat, keeping your elbows inside the track of your knees.\r\n3\r\nPush through your heels while keeping your chest up and return to starting position.', '', '../../uploads/male-dumbbell-goblet-squat-front.mp4', NULL),
	(5, 'Dumbbell Goblet Squat', 'Hold the weight tucked into your upper chest area, keeping your elbows in. Your feet should be slightly wider than shoulder width.\r\n2\r\nSink down into the squat, keeping your elbows inside the track of your knees.\r\n3\r\nPush through your heels while keeping your chest up and return to starting position.', '', '../../uploads/male-dumbbell-goblet-squat-front.mp4', NULL),
	(6, 'Développé couché', 'Exercice pour les pectoraux', NULL, NULL, NULL),
	(7, 'Pompes', 'Exercice de musculation au poids du corps pour renforcer les pectoraux, les triceps et les épaules.', NULL, NULL, 'Pectoraux'),
	(8, 'Fentes', 'Exercice pour renforcer les jambes et améliorer l’équilibre.', NULL, NULL, 'Jambes'),
	(9, 'Planche', 'Exercice de gainage pour renforcer les abdominaux et le bas du dos.', NULL, NULL, 'Abdominaux'),
	(10, 'Développé militaire', 'Exercice avec haltères ou barre pour renforcer les épaules.', NULL, NULL, 'Épaules'),
	(11, 'Rowing barre', 'Exercice pour renforcer les muscles du dos et les biceps.', NULL, NULL, 'Dos'),
	(12, 'Burpees', 'Exercice complet pour améliorer l’endurance et renforcer tout le corps.', NULL, NULL, 'Cardio'),
	(13, 'Crunchs', 'Exercice classique pour renforcer les abdominaux.', NULL, NULL, 'Abdominaux'),
	(14, 'Soulevé de terre', 'Exercice de musculation pour renforcer les jambes, le dos et les fessiers.', NULL, NULL, 'Dos'),
	(15, 'Mountain climbers', 'Exercice cardio pour renforcer les abdominaux et améliorer l’endurance.', NULL, NULL, 'Cardio'),
	(16, 'Tractions', 'Exercice au poids du corps pour renforcer le dos et les biceps.', NULL, NULL, 'Dos'),
	(17, 'Leg Press', 'Exercice pour renforcer les jambes.', NULL, NULL, 'Jambes'),
	(18, 'Pull-Ups', 'Exercice pour le dos et les biceps.', NULL, NULL, 'Dos'),
	(19, 'Plank to Push-Up', 'Exercice combiné pour les abdominaux et les bras.', NULL, NULL, 'Abdominaux');

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.messages : ~12 rows (environ)
DELETE FROM `messages`;
INSERT INTO `messages` (`id_message`, `id_utilisateur`, `id_salon`, `contenu`, `date_message`, `id_recette`, `id_produit`, `reply_to`) VALUES
	(1, 1, 1, 'test', '2025-03-21 10:44:08', NULL, NULL, NULL),
	(2, 1, 1, 'e', '2025-03-21 10:48:57', NULL, NULL, NULL),
	(3, 7, 1, 'test', '2025-04-11 09:52:47', NULL, NULL, NULL),
	(4, 10, 1, 'salut', '2025-04-11 11:43:01', NULL, NULL, NULL),
	(5, 7, 1, 're', '2025-04-11 12:08:53', NULL, NULL, NULL),
	(6, 1, 2, 'pecs dos', '2025-04-13 09:35:36', NULL, NULL, NULL),
	(7, 10, 1, 'test', '2025-04-13 19:59:18', NULL, NULL, NULL),
	(8, 10, 1, 'test', '2025-04-13 20:43:20', NULL, NULL, NULL),
	(9, 1, 1, '1', '2025-04-15 09:20:54', NULL, NULL, NULL),
	(10, 10, 1, 'salut', '2025-04-15 11:27:11', NULL, NULL, NULL),
	(11, 10, 3, 'miam', '2025-04-15 11:27:26', 4, NULL, NULL),
	(12, 10, 4, 'je recommande', '2025-04-15 11:29:00', NULL, 11, NULL),
	(13, 10, 1, '@Yanis Perrin Réponse à [message 10]:  test', '2025-05-05 08:54:57', NULL, NULL, 10),
	(14, 1, 1, 'Quelqu’un a essayé le produit NutriStrong Whey Pro ?', '2025-05-01 07:00:00', NULL, NULL, NULL),
	(15, 1, 2, 'Quels exercices recommandez-vous pour les jambes ?', '2025-05-02 08:15:00', NULL, NULL, NULL),
	(16, 1, 3, 'J’ai testé la recette de riz au poulet, c’est top !', '2025-05-03 09:30:00', 5, NULL, NULL),
	(17, 1, 1, 'Bienvenue à tous les nouveaux membres ! N’hésitez pas à poser vos questions.', '2025-05-01 08:00:00', NULL, NULL, NULL),
	(18, 7, 1, 'Merci pour l’accueil ! Je suis nouveau ici, ravi de vous rejoindre.', '2025-05-01 08:05:00', NULL, NULL, NULL),
	(19, 9, 1, 'Salut tout le monde, si vous avez besoin de conseils sur vos entraînements, je suis là.', '2025-05-01 08:10:00', NULL, NULL, NULL),
	(20, 9, 2, 'Pour ceux qui veulent améliorer leur squat, pensez à travailler votre mobilité des hanches.', '2025-05-02 07:00:00', NULL, NULL, NULL),
	(21, 1, 2, 'Merci pour le conseil ! Je vais essayer ça lors de ma prochaine séance.', '2025-05-02 07:15:00', NULL, NULL, 17),
	(22, 8, 2, 'Quelqu’un a des astuces pour progresser sur les tractions ? Je stagne depuis un moment.', '2025-05-02 07:30:00', NULL, NULL, NULL),
	(23, 10, 3, 'J’ai testé la recette de pancakes, c’est vraiment délicieux et facile à faire.', '2025-05-03 09:00:00', 7, NULL, NULL),
	(24, 1, 3, 'Content que ça te plaise ! Si tu veux d’autres idées, regarde la recette de smoothie protéiné.', '2025-05-03 09:15:00', 6, NULL, 20),
	(25, 7, 3, 'Est-ce que quelqu’un a une recette de repas post-entraînement rapide à préparer ?', '2025-05-03 09:30:00', NULL, NULL, NULL),
	(26, 10, 4, 'Le brûleur de graisses NutriStrong Burner X est vraiment efficace, je le recommande.', '2025-05-04 12:00:00', NULL, 11, NULL),
	(27, 8, 4, 'Merci pour ton avis ! Est-ce qu’il y a des effets secondaires à signaler ?', '2025-05-04 12:15:00', NULL, NULL, 23),
	(28, 10, 4, 'Non, aucun effet secondaire pour moi. Juste une bonne énergie pendant mes séances.', '2025-05-04 12:30:00', NULL, NULL, 24),
	(29, 1, 5, '🚨 Promo spéciale cette semaine : -20% sur tous les produits NutriStrong !', '2025-05-05 07:00:00', NULL, NULL, NULL),
	(30, 7, 5, 'Merci pour l’info ! Je vais en profiter pour commander des barres protéinées.', '2025-05-05 07:10:00', NULL, NULL, 26),
	(31, 9, 5, 'Super promo ! Je vais aussi en profiter pour tester la créatine.', '2025-05-05 07:15:00', NULL, NULL, 26),
	(32, 11, 5, '🚨 Nouvelle promotion : -30% sur tous les produits NutriStrong jusqu’à la fin du mois ! Profitez-en vite !', '2025-05-12 08:00:00', NULL, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.panier : ~2 rows (environ)
DELETE FROM `panier`;
INSERT INTO `panier` (`id_panier`, `id_utilisateur`, `id_produit`, `quantite`, `date_ajout`) VALUES
	(44, 10, 11, 1, '2025-05-05 09:00:11'),
	(45, 10, 12, 1, '2025-05-05 09:00:14'),
	(46, 10, 14, 1, '2025-05-05 09:00:17');

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

-- Listage des données de la table projet_php_.produits : ~14 rows (environ)
DELETE FROM `produits`;
INSERT INTO `produits` (`id_produit`, `nom_produit`, `description`, `prix`, `quantite_disponible`, `libelle`, `image`, `vues`, `date_creation`) VALUES
	(10, 'NutriStrong Whey Pro – Chocolat Intense', 'NutriStrong Whey Pro – Chocolat Intense est une protéine de lactosérum premium, formulée pour maximiser la prise de muscle et accélérer la récupération musculaire.\r\n✔️ 25g de protéines par portion\r\n✔️ Faible en sucre et sans gluten\r\n✔️ Idéal en post-entraînement ou en collation\r\n✔️ Goût chocolat onctueux sans arrière-goût chimique\r\n\r\nParfait pour les sportifs exigeants qui veulent des résultats visibles tout en se régalant !', 29.90, 0, 'Protéine en poudre – Prise de masse &amp; récupération', 'uploads/produits/produit_67efc9e539b6a.png', 36, '2025-04-04 12:00:37'),
	(11, 'NutriStrong Burner X – Brûleur de graisses naturel', 'NutriStrong Burner X est un brûleur de graisses 100% naturel, conçu pour t’aider à sculpter ton corps et améliorer ton métabolisme.\r\n🔸 Formule thermogénique à base de thé vert, caféine et L-carnitine\r\n🔸 Favorise la combustion des graisses et augmente l’énergie\r\n🔸 Idéal en phase de sèche ou de recomposition corporelle\r\n🔸 Sans effets secondaires, sans OGM\r\n\r\n💥 Combine-le à un entraînement régulier pour des résultats optimaux !\r\n\r\n', 24.90, 201, 'Complément alimentaire – Définition musculaire &amp; perte de poids', 'uploads/produits/produit_67efd2bf268df.png', 37, '2025-04-04 12:38:23'),
	(12, 'NutriStrong Creatine Pure – Monohydrate Micronisée', 'NutriStrong Creatine Pure est une créatine monohydrate 100% pure et micronisée, conçue pour améliorer la force explosive, la congestion musculaire et la performance à l&#039;entraînement.\r\n\r\n🔹 5g de créatine pure par dose\r\n🔹 Améliore les performances sur les efforts courts et intenses\r\n🔹 Micronisation ultra-fine pour une meilleure absorption\r\n🔹 Sans arômes, sans additifs, se mélange facilement à n’importe quelle boisson\r\n\r\n📈 Idéale pour les cycles de prise de masse ou de force.', 19.90, 119, 'Créatine en poudre – Force &amp; performance', 'uploads/produits/produit_67efd5d02851c.png', 15, '2025-04-04 12:51:28'),
	(13, 'NutriStrong SVO Bar – Choco-Noisette Crunch', 'NutriStrong SVO Bar est une barre protéinée gourmande et équilibrée, conçue pour les sportifs en quête de snacks pratiques et sains.\r\n\r\n🥜 18g de protéines\r\n🍫 Saveur chocolat-noisette avec éclats croquants\r\n💪 Faible en sucre – Riche en fibres\r\n⏱️ Idéale en collation post-training ou au goûter\r\n\r\nUne vraie alternative aux barres classiques, sans compromis entre plaisir et nutrition !\r\n\r\n', 2.50, 300, 'Barre protéinée – Encas sain &amp; gourmand', 'uploads/produits/produit_67efd720dc0e3.png', 10, '2025-04-04 12:57:04'),
	(14, 'NutriStrong Green Boost – Complexe de Superaliments', 'NutriStrong Green Boost est un mélange de superaliments verts, idéal pour renforcer ta vitalité, soutenir ta récupération et améliorer ta digestion au quotidien.\r\n\r\n🌱 Spiruline, chlorella, épinards, brocoli, thé vert, maca...\r\n🧠 Riche en antioxydants, vitamines et minéraux\r\n💚 Aide à l’élimination des toxines\r\n💪 Renforce l’immunité et le tonus naturel\r\n\r\nÀ prendre le matin dans un smoothie, un jus ou simplement avec de l’eau.', 21.90, 99, 'Mélange de superfoods – Vitalité &amp; récupération', 'uploads/produits/produit_67efd95fe4e19.png', 1, '2025-04-04 13:06:39'),
	(15, 'ProMix Gainer 1000', 'ProMix Gainer 1000 est un gainer nouvelle génération spécialement conçu pour les pratiquants de musculation qui veulent prendre du poids proprement. Contrairement aux gainers classiques bourrés de sucres simples, notre formule mise sur la qualité nutritionnelle :\r\n\r\n35 g de protéines végétales et animales par portion (isolate de pois + whey native sans lactose)\r\n\r\n60 g de glucides complexes (farine d’avoine, patate douce en poudre, quinoa)\r\n\r\n10 g de bons lipides (huile de coco MCT et graines de lin)\r\n\r\nCréatine monohydrate microfiltrée – 3 g par portion\r\n\r\nEnzymes digestives et probiotiques pour une digestion optimale\r\n\r\nSans édulcorants chimiques, sans lactose, sans soja.', 39.90, 20, ' Nutrition Sportive – Gainer Clean et Naturel', 'uploads/produits/produit_67fb801700e4a.png', 1, '2025-04-13 09:12:55'),
	(16, 'AminoFuel XT', 'AminoFuel XT est une formule intra-training avancée combinant BCAA 2:1:1, EAA complets, électrolytes naturels (eau de coco lyophilisée, sel rose d’Himalaya) et un complexe anti-fatigue à base de Rhodiola et Ginseng.\r\n\r\nElle booste :\r\n\r\nla récupération musculaire,\r\n\r\nla résistance à l’effort,\r\n\r\nl’hydratation cellulaire,\r\n\r\ntout en préservant la masse musculaire même en déficit calorique.', 29.90, 20, 'Acides Aminés – BCAA + EAA + Hydratation', 'uploads/produits/produit_6810b8d3d7f52.png', 2, '2025-04-29 11:32:35'),
	(17, 'Recovery Max Z3', 'Recovery Max Z3 est une formule de récupération musculaire nocturne ultra complète, conçue pour les athlètes et pratiquants exigeants.\r\nElle combine :\r\n\r\ndes minéraux essentiels pour booster la testostérone naturelle (Zinc, Magnésium, Vitamine B6),\r\n\r\ndes acides aminés anti-cataboliques (Glutamine, L-Ornithine, L-Arginine),\r\n\r\net des plantes apaisantes pour favoriser l’endormissement (Mélisse, Valériane, Ashwagandha).\r\n\r\n\r\nAvantages :\r\nAméliore la qualité du sommeil profond\r\n\r\nFavorise la production hormonale naturelle (testostérone)\r\n\r\nAccélère la récupération musculaire\r\n\r\nRéduction du stress et du cortisol', 34.90, 15, 'Récupération – Sommeil, Anti-Catabolisme et Testostérone Naturelle', 'uploads/produits/produit_6810ba1df057b.png', 5, '2025-04-29 11:38:05'),
	(18, 'NutriStrong – Protéine Naturelle Vanille (500g)', 'Découvrez NutriStrong, la protéine végétale par excellence. Un mélange équilibré de pois, riz et chanvre, 100% naturel, sans OGM, sans lactose et sans sucre ajouté. Idéale pour booster vos performances sportives, favoriser la récupération et compléter votre apport en protéines au quotidien.\r\n✅ Saveur vanille naturelle\r\n✅ 24g de protéines par portion\r\n✅ Convient aux régimes végétariens et vegans\r\n✅ Fabriqué en France', 24.99, 150, 'Protéine végétale bio en poudre', 'uploads/produits/produit_681b692a1cb50.png', 1, '2025-05-07 14:07:38'),
	(19, '🍫 NutriStrong – Barre Protéinée Cacahuète &amp; Chocolat (50g)', 'Savourez l’alliance parfaite entre plaisir et performance avec la Barre Protéinée NutriStrong. Enrobée de chocolat noir, avec un cœur fondant à la cacahuète, elle offre 18g de protéines végétales, sans sucres ajoutés, et convient parfaitement pour une collation saine après l’effort ou en journée.\r\n✅ Source de fibres\r\n✅ Sans gluten, sans conservateurs\r\n✅ Texture moelleuse et goût intense\r\n✅ 100% vegan', 2.49, 300, 'Barre énergétique riche en protéines', 'uploads/produits/produit_681b6a12a71d9.png', 1, '2025-05-07 14:11:30'),
	(20, '🥤 Shaker NutriStrong – 700ml', 'Le Shaker NutriStrong est l’accessoire indispensable pour mélanger vos protéines, compléments et boissons énergétiques. Fabriqué en plastique sans BPA, il dispose d’un bec verseur anti-fuite, d’une grille mélangeuse pour éviter les grumeaux, et d’un design ergonomique pour une prise en main facile.\r\n✅ Capacité : 700ml\r\n✅ Résistant au lave-vaisselle\r\n✅ Graduations visibles pour un dosage précis\r\n✅ Design moderne avec logo NutriStrong\r\n', 9.99, 200, 'Shaker sport haute performance', 'uploads/produits/produit_681b6b112a8da.jpg', 1, '2025-05-07 14:15:45'),
	(21, '🎒 NutriStrong – Sac de Sport Multifonction', 'Le Sac de Sport NutriStrong est conçu pour les sportifs modernes. Robuste, léger et stylé, il offre une grande capacité de rangement pour transporter vos vêtements, accessoires de fitness et compléments alimentaires.\r\nCompartiment principal spacieux, poche latérale pour chaussures, pochette intérieure zippée, et bandoulière ajustable pour un confort optimal.\r\n✅ Tissu imperméable\r\n✅ Fermetures éclair renforcées\r\n✅ Logo NutriStrong brodé\r\n✅ Disponible en noir et gris carbone', 34.90, 100, ' Sac de sport compact et résistant', 'uploads/produits/produit_681b6f14110d0.png', 2, '2025-05-07 14:32:52'),
	(22, '🎒 NutriStrong – Gourde Isotherme Sport', 'Restez hydraté à la salle comme en déplacement avec la gourde isotherme NutriStrong. Conçue pour les sportifs exigeants, elle allie élégance, durabilité et praticité.\r\nGrâce à son corps en acier inoxydable, elle garde vos boissons fraîches jusqu&#039;à 24h ou chaudes jusqu&#039;à 12h. Le bouchon étanche avec poignée intégrée permet un transport facile et sans fuite.\r\n\r\n✅ Acier inoxydable double paroi\r\n✅ Étanche et résistante aux chocs\r\n✅ Garde au froid 24h / au chaud 12h\r\n✅ Logo NutriStrong blanc imprimé\r\n✅ Contenance : 750 ml\r\n✅ Disponible en noir mat', 19.90, 200, 'Gourde en acier inoxydable, résistante et stylée', 'uploads/produits/produit_681b7084e270b.png', 1, '2025-05-07 14:39:00'),
	(23, '🎧 NutriStrong – Écouteurs Sans Fil Sport Pro', 'Améliorez vos séances d&#039;entraînement avec les écouteurs NutriStrong Sport Pro. Conçus pour bouger avec vous, ils offrent un son puissant, une tenue parfaite et une autonomie longue durée. Idéals pour rester motivé pendant vos workouts 💥🎶\r\n✅ Son clair et basses puissantes\r\n✅ Connexion Bluetooth 5.3 rapide et stable\r\n✅ Résistants à la transpiration (IPX5)\r\n✅ Autonomie jusqu’à 10 h (30 h avec boîtier)\r\n✅ Embouts intra-auriculaires confortables\r\n✅ Micro intégré pour appels mains libres\r\n✅ Logo NutriStrong sur le boîtier de charge', 44.90, 80, 'Écouteurs Bluetooth résistants à la sueur, pour l&#039;entraînement', 'uploads/produits/produit_681b72345aacb.jpg', 2, '2025-05-07 14:46:12');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.programmes : ~15 rows (environ)
DELETE FROM `programmes`;
INSERT INTO `programmes` (`id_programme`, `id_utilisateur`, `objectif`, `frequence`, `niveau`, `programme`, `created_at`, `updated_at`) VALUES
	(1, 2, 'perte de poids', 4, 'débutant', 'Lundi : Cardio léger (marche rapide, vélo 30 min)\r\nMercredi : Renforcement musculaire avec poids légers\r\nVendredi : Yoga ou stretching', '2025-01-05 19:16:33', '2025-01-05 19:16:41'),
	(2, 2, 'powerlifting', 4, 'débutant', 'Jour 1 : Squat 3x5, Leg Press 3x10, Crunchs 3x12\nJour 2 : Développé couché 3x5, Pompes 3x10, Planche 3x30s\nJour 3 : Soulevé de terre 3x5, Tractions 3x5, Extensions lombaires 3x10', '2025-01-05 19:16:37', '2025-01-05 19:16:37'),
	(3, 2, 'powerlifting', 5, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-06 08:39:29', '2025-01-06 08:39:29'),
	(4, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:34', '2025-01-06 09:26:34'),
	(5, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:40', '2025-01-06 09:26:40'),
	(8, 1, 'powerlifting', 4, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-20 12:04:09', '2025-01-20 12:04:09'),
	(9, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:45', '2025-02-14 16:04:45'),
	(10, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:49', '2025-02-14 16:04:49'),
	(11, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:26', '2025-02-17 12:11:26'),
	(12, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:30', '2025-02-17 12:11:30'),
	(15, 1, 'perte de poids', 5, 'débutant', 'Lundi : Cardio léger (marche rapide, vélo 30 min)\nMercredi : Renforcement musculaire avec poids légers\nVendredi : Yoga ou stretching', '2025-04-15 09:31:34', '2025-04-15 09:31:34'),
	(17, 10, 'gain de masse', 4, 'intermédiaire', 'Lundi : Haut du corps (pectoraux, biceps)\r\nMercredi : Bas du corps (squats, fentes, mollets)\r\nVendredi : Circuit complet avec charges lourdes\r\nSamedi : Focus sur la nutrition riche en protéines', '2025-04-15 11:57:56', '2025-04-15 11:58:04'),
	(18, 10, 'gain de masse', 4, 'intermédiaire', 'Lundi : Haut du corps (pectoraux, biceps)\nMercredi : Bas du corps (squats, fentes, mollets)\nVendredi : Circuit complet avec charges lourdes\nSamedi : Focus sur la nutrition riche en protéines', '2025-04-15 11:58:01', '2025-04-15 11:58:01'),
	(19, 10, 'perte de poids', 3, 'débutant', 'Lundi : Cardio léger (marche rapide, vélo 30 min)\r\nMercredi : Renforcement musculaire avec poids légers\r\nVendredi : Yoga ou stretching', '2025-04-15 12:02:37', '2025-04-15 12:02:41'),
	(20, 10, 'gain de masse', 4, 'intermédiaire', 'Lundi : Haut du corps (pectoraux, biceps)\nMercredi : Bas du corps (squats, fentes, mollets)\nVendredi : Circuit complet avec charges lourdes\nSamedi : Focus sur la nutrition riche en protéines', '2025-05-05 08:57:44', '2025-05-05 08:57:44'),
	(21, 1, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-05-12 11:56:42', '2025-05-12 11:56:42');

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

-- Listage des données de la table projet_php_.recettes : ~9 rows (environ)
DELETE FROM `recettes`;
INSERT INTO `recettes` (`id_recette`, `titre`, `description`, `categorie`, `ingredients`, `etapes`, `id_utilisateur`, `date_creation`, `image`, `vues`) VALUES
	(3, 'omelette', 'casser les oeuf dans un bol \r\net mettre dans un poêle avec du lait', 'Prise de masse', 'lait\r\noeuf', '2', 2, '2025-01-02 23:22:42', 'uploads/recettes/recette_677711b23dfc5.jpg', 8),
	(4, 'crepe', 'tres bon', 'Prise de masse', 'oeuf \r\nlait\r\nfarine', '2', 1, '2025-01-18 13:06:22', 'uploads/recettes/recette_678b993eb8f7a.webp', 41),
	(5, 'riz poulettte', 'Voici une délicieuse recette de Riz au Poulet simple et savoureuse !', 'Prise de masse', '2 blancs de poulet\r\n\r\n200 g de riz\r\n\r\n1 oignon\r\n\r\n2 gousses d’ail\r\n\r\n1 poivron rouge\r\n\r\n1 tomate\r\n\r\n1 cube de bouillon de volaille\r\n\r\n500 ml d’eau\r\n\r\n1 c. à café de curcuma (ou paprika)\r\n\r\n1 c. à soupe d’huile d’olive\r\n\r\nSel & poivre', 'Faire revenir le poulet :\r\n\r\nCoupez les blancs de poulet en morceaux.\r\n\r\nDans une grande poêle ou une sauteuse, faites chauffer l’huile d’olive.\r\n\r\nFaites dorer le poulet quelques minutes, puis réservez.\r\n\r\nPréparer les légumes :\r\n\r\nÉmincez l’oignon et l’ail.\r\n\r\nCoupez le poivron en petits dés et la tomate en morceaux.\r\n\r\nCuisson :\r\n\r\nDans la même poêle, faites revenir l’oignon et l’ail jusqu’à ce qu’ils deviennent translucides.\r\n\r\nAjoutez le poivron, la tomate et laissez cuire 5 minutes.\r\n\r\nAjoutez le riz, mélangez bien pour qu’il devienne légèrement translucide.\r\n\r\nVersez l’eau avec le cube de bouillon émietté et ajoutez le curcuma, du sel et du poivre.\r\n\r\nMélangez bien et laissez mijoter à feu doux jusqu’à absorption du liquide (environ 15-20 min).\r\n\r\nFinalisation :\r\n\r\nAjoutez le poulet dans la poêle et mélangez.\r\n\r\nLaissez cuire encore 5 minutes pour que les saveurs se mélangent.\r\n\r\nDégustation :\r\n\r\nServez chaud, éventuellement avec du persil frais pour plus de saveur.', 1, '2025-04-03 16:00:06', 'uploads/recettes/recette_67ee94666585c.webp', 14),
	(6, 'Smoothie Protéiné Banane & Avoine', 'Voici une recette de Smoothie Protéiné parfait pour la récupération musculaire ou un petit-déjeuner énergisant ! 💪🥤', 'Prise de masse', '1 banane 🍌\r\n\r\n200 ml de lait (ou lait végétal : amande, soja…) 🥛\r\n\r\n30 g de flocons d’avoine 🌾\r\n\r\n1 dose de protéine en poudre (vanille, chocolat ou neutre) 💪\r\n\r\n1 c. à soupe de beurre de cacahuète 🥜\r\n\r\n1 c. à café de miel ou sirop d’érable 🍯 (optionnel)\r\n\r\nQuelques glaçons (optionnel pour un effet plus frais ❄️)', 'Mixer tous les ingrédients dans un blender jusqu’à obtenir une texture lisse et onctueuse.\r\n\r\nGoûter et ajuster la texture avec un peu plus de lait si besoin.\r\n\r\nServir immédiatement et déguster bien frais !', 1, '2025-04-03 16:11:17', 'uploads/recettes/recette_67ee9705669e7.webp', 10),
	(7, '🥞 Pancakes Classiques', 'Voici une recette simple et délicieuse de Pancakes ! 🥞✨', 'Maintien', '250 g de farine\r\n\r\n2 œufs\r\n\r\n300 ml de lait\r\n\r\n2 c. à soupe de sucre\r\n\r\n1 sachet de levure chimique (environ 10 g)\r\n\r\n1 pincée de sel\r\n\r\n30 g de beurre fondu (ou huile)\r\n\r\n1 c. à café d\'extrait de vanille (optionnel)', 'Préparer la pâte :\r\n\r\nDans un grand bol, tamisez la farine, la levure chimique et la pincée de sel.\r\n\r\nDans un autre bol, battez les œufs avec le sucre. Ajoutez le lait, le beurre fondu et l\'extrait de vanille (si utilisé). Mélangez bien.\r\n\r\nVersez lentement les ingrédients liquides dans le mélange de farine tout en remuant pour éviter les grumeaux. La pâte doit être lisse et légèrement épaisse.\r\n\r\nCuisson des pancakes :\r\n\r\nChauffez une poêle antiadhésive à feu moyen et ajoutez un peu de beurre ou d\'huile.\r\n\r\nVersez une petite louche de pâte dans la poêle chaude. Faites cuire environ 1 à 2 minutes de chaque côté, ou jusqu’à ce que des bulles apparaissent sur le dessus, puis retournez-les délicatement pour dorer l’autre côté.\r\n\r\nDégustation :\r\n\r\nServez les pancakes immédiatement avec du sirop d’érable, des fruits frais, du miel, ou du chocolat fondu, selon vos préférences !', 1, '2025-04-03 16:19:47', 'uploads/recettes/recette_67ee990355eb5.webp', 32),
	(8, 'Smoothie Vert Détox Kiwi & Épinards', 'Un smoothie ultra frais et revitalisant, parfait pour faire le plein d’énergie et de vitamines tout en aidant le corps à se détoxifier ! 🌿🥝', 'Sèche', '1 kiwi bien mûr 🥝\r\n1 poignée d’épinards frais 🥬\r\n1/2 pomme verte 🍏\r\n200 ml d’eau de coco 🥥\r\n1 c. à soupe de graines de chia 🌱\r\nLe jus d’1/2 citron 🍋\r\nQuelques feuilles de menthe (optionnel) 🌿\r\nQuelques glaçons ❄️', 'Éplucher le kiwi et la pomme, les couper en morceaux.\r\n\r\nMettre tous les ingrédients dans un blender.\r\n\r\nMixer jusqu’à obtenir un smoothie bien lisse et homogène.\r\n\r\nServir immédiatement, décoré d’une feuille de menthe si désiré.', 1, '2025-05-07 16:10:34', 'uploads/recettes/recette_681b69da5f500.jpg', 0),
	(9, 'Smoothie Protéiné Chocolat & Noisette', 'Un smoothie riche et gourmand pour combiner plaisir chocolaté et apport en protéines après une bonne séance de sport ! 🍫💪', 'Prise de masse', '📝 Ingrédients\r\n250 ml de lait (ou lait d’amande) 🥛\r\n1 banane bien mûre 🍌\r\n1 dose de protéine en poudre goût chocolat 💪🍫\r\n1 c. à soupe de purée de noisette 🌰\r\n1 c. à café de cacao en poudre non sucré 🍫\r\n1/2 c. à café de cannelle (optionnel) ✨\r\nQuelques glaçons ❄️', 'Placer tous les ingrédients dans un blender.\r\n\r\nMixer jusqu’à obtenir une texture lisse.\r\n\r\nGoûter et ajuster selon vos préférences (plus de cacao ou de lait si nécessaire).\r\n\r\nVerser dans un grand verre et savourer immédiatement.', 10, '2025-05-07 16:14:16', 'uploads/recettes/recette_681b6ab8aa818.webp', 0),
	(10, 'Bowl Poulet & Patate Douce', '💪 Description\r\nUn repas complet et équilibré riche en protéines, glucides complexes et bons lipides, parfait pour favoriser la récupération musculaire après l’effort. 🍗🥔💪', 'Prise de masse', '150 g de blanc de poulet 🐔\r\n1 patate douce moyenne 🍠\r\n1/2 avocat 🥑\r\n1 poignée de roquette ou d’épinards frais 🥬\r\n1 c. à soupe d’huile d’olive extra vierge 🫒\r\nÉpices : paprika, ail, sel, poivre 🌶️\r\nJus d’1/2 citron 🍋', 'Éplucher et couper la patate douce en cubes. La faire cuire à la vapeur ou au four (environ 25 min à 180°C).\r\n\r\nCouper le blanc de poulet en morceaux, assaisonner avec les épices, puis cuire à la poêle avec un peu d’huile d’olive.\r\n\r\nDans un bol, disposer les feuilles de roquette, les cubes de patate douce, le poulet chaud, et l’avocat tranché.\r\n\r\nArroser d’un filet de citron et d’un peu d’huile d’olive.', 10, '2025-05-07 16:18:26', 'uploads/recettes/recette_681b6bb2ee507.jpg', 0),
	(11, 'Barres Protéinées Maison Avoine & Dattes', 'Idéales pour une collation rapide avant ou après l’entraînement, ces barres faites maison sont naturelles, riches en fibres et en protéines ! 🏋️‍♂️🍫', 'Maintien', '100 g de flocons d’avoine 🌾\r\n100 g de dattes dénoyautées (trempées 10 min dans l’eau chaude) 🌴\r\n30 g de poudre de protéine (chocolat ou vanille) 💪\r\n1 c. à soupe de cacao en poudre non sucré 🍫\r\n2 c. à soupe de purée d’amande ou de cacahuète 🥜\r\n1 c. à soupe de graines de chia ou de lin 🌱\r\n2 à 3 c. à soupe d’eau (si besoin pour la texture)', 'Mixer les dattes jusqu’à obtenir une pâte.\r\n\r\nAjouter tous les autres ingrédients dans un saladier, puis incorporer la pâte de dattes.\r\n\r\nBien mélanger (ajouter un peu d’eau si la pâte est trop sèche).\r\n\r\nTasser dans un moule rectangulaire recouvert de papier cuisson.\r\n\r\nPlacer au frais 1h, puis découper en barres.', 10, '2025-05-07 16:20:23', 'uploads/recettes/recette_681b6c27e8eff.jpg', 2);

-- Listage de la structure de table projet_php_. salons
CREATE TABLE IF NOT EXISTS `salons` (
  `id_salon` int NOT NULL AUTO_INCREMENT,
  `nom_salon` varchar(50) NOT NULL,
  `description` text,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_salon`),
  UNIQUE KEY `nom_salon` (`nom_salon`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.salons : ~5 rows (environ)
DELETE FROM `salons`;
INSERT INTO `salons` (`id_salon`, `nom_salon`, `description`, `date_creation`) VALUES
	(1, '#Général', 'Chat libre entre membres.', '2025-02-17 13:57:45'),
	(2, '#Entraînement', 'Conseils et retours sur les exercices.', '2025-02-17 13:57:45'),
	(3, '#Nutrition', 'Idées de repas et plans alimentaires.', '2025-02-17 13:57:45'),
	(4, '#Produits', 'Avis sur les produits en vente.', '2025-02-17 13:57:45'),
	(5, '#Annonces', 'News et promos du site.', '2025-02-17 13:57:45');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.seance_exercice : ~7 rows (environ)
DELETE FROM `seance_exercice`;
INSERT INTO `seance_exercice` (`id_seance_exercice`, `id_entrainement`, `id_exercice`, `poids`, `repetitions`, `series`, `ressenti`, `date`) VALUES
	(1, 1, 0, 50, 10, 0, NULL, '2025-03-21'),
	(2, 26, 2, 20, 4, 2, 'BIEN', '2025-03-28'),
	(3, 28, 1, 22, 10, 4, 'BIEN', '2025-04-01'),
	(4, 28, 4, 20, 10, 20, 'BIEN', '2025-04-01'),
	(5, 32, 1, 4, 20, 20, 'BIEN', '2025-04-02'),
	(9, 36, 2, 140, 4, 3, 'BIEN', '2025-04-08'),
	(10, 37, 1, 80, 10, 4, 'Bonne progression', '2025-05-01'),
	(11, 37, 7, 0, 15, 3, 'Échauffement efficace', '2025-05-01'),
	(12, 38, 2, 100, 8, 4, 'Très intense', '2025-05-03'),
	(13, 38, 8, 50, 12, 3, 'Bon équilibre', '2025-05-03'),
	(14, 39, 9, 0, 60, 3, 'Excellente endurance', '2025-05-05'),
	(15, 39, 15, 0, 30, 4, 'Bonne intensité', '2025-05-05');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.utilisateurs : ~7 rows (environ)
DELETE FROM `utilisateurs`;
INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `bio`, `objectifs_fitness`, `objectif`, `mot_de_passe`, `role`, `date_naissance`, `sexe`, `date_creation`, `photo_profil`, `badge`, `tentatives`, `bloque`, `dernier_echec`, `notifications_active`) VALUES
	(1, 'Le Bihan', 'Nathaël', 'xarunax69@gmail.com', '', '', '', '$2y$10$j3VjdNMrOq4gyFpKUDs9MOOLRbT4oCohOvzD.TFOhgz17joIST1Oy', 'administrateur', '2005-10-12', 'Homme', '2024-12-11 20:54:21', 'uploads/profils/Fichier_000.png', NULL, 0, 0, '2025-04-11 13:29:01', 0),
	(2, 'LE BIHAN', 'Nathaël', 'nathael.lebihan12102005@gmail.com', NULL, NULL, NULL, '$2y$10$D03sL3VkP9kD.LRdaljwu.KpvbtU37P7.4yAwkRWL9JbLLrHX8ONi', 'utilisateur', '2005-10-12', 'Homme', '2024-12-20 11:39:16', NULL, NULL, 0, 0, NULL, 1),
	(6, 'Nathael', 'dsdza', 'nathael.lebihanIDBZSDZOILD@gmail.com', NULL, NULL, NULL, '$2y$10$WXEO6NJOY/qJ.K2PXSxavOsnSNexYMMbbP0xjszBmbt.6AO9ec4Y6', 'super_administrateur', '2055-10-12', 'Homme', '2025-02-14 16:03:52', NULL, NULL, 0, 0, NULL, 1),
	(7, 'LE BIHAN', 'Nathaël', 'xarunax68@gmail.com', '', '', '', '$2y$10$OPoyVHXYDQGYOlrVt56hiuOPNkrT3NEzx9hUBOpwnf.uqQtKokEaW', 'super_administrateur', '2005-10-12', 'Homme', '2025-02-17 10:39:04', 'uploads/profils/ChatGPT Image 30 mars 2025, 23_51_26.png', NULL, 0, 0, NULL, 1),
	(8, 'Perin', 'Yanis', 'yanis@perin.fr', NULL, NULL, NULL, '$2y$10$AfRQPB6.us5k/1MJ5Jv0cup6iGaj3kCI301CZPAAnvG7Id1R1Lyzy', 'utilisateur', '2025-12-03', 'Femme', '2025-03-17 09:11:54', NULL, NULL, 0, 0, NULL, 1),
	(9, 'yanis', 'Perin', 'yanis.perin@gmail.com', NULL, NULL, NULL, '$2y$10$iU3tBG3BFwZVq5.3x5VGZejDJBxMnAHQoQLS3SAg.qcCibSVZjVUO', 'coach', '2005-10-12', 'Homme', '2025-03-17 10:11:58', NULL, NULL, 0, 0, NULL, 1),
	(10, 'Perrin', 'Yanis', 'yanisperrin03@gmail.com', '', '', '', '$2y$10$KPt9qa..v1m3S6eWUZYw5OrbUrnzNYT3LR0JBd6L/JPgERGHcyVdG', 'utilisateur', '2005-10-03', 'Homme', '2025-04-11 11:39:08', 'uploads/profils/yanis.jpg', NULL, 0, 0, NULL, 0),
	(11, 'Dupont', 'Marie', 'marie.dupont@commercial.com', NULL, NULL, NULL, '$2y$10$eW5J3VjdNMrOq4gyFpKUDs9MOOLRbT4oCohOvzD.TFOhgz17joIST1Oy', 'commercial', '1990-05-15', 'Femme', '2025-05-12 12:14:08', 'uploads/profils/default.png', NULL, 0, 0, NULL, 1),
	(12, 'Dupont', 'Alice', 'alice.dupont@superadmin.com', NULL, NULL, NULL, '$2y$10$eW5J3VjdNMrOq4gyFpKUDs9MOOLRbT4oCohOvzD.TFOhgz17joIST1Oy', 'super_administrateur', '1990-01-01', 'Femme', '2025-05-12 12:25:57', 'uploads/profils/default.png', NULL, 5, 1, '2025-05-12 14:28:04', 1),
	(13, 'SUPER', 'ADMIN', 'superadmin@gmail.com', '', '', '', '$2y$10$yQZWb9vqHpowZXR.Y4Jb7eT4hTItg2tZi8rzQfJxWRINB.PFEwy9.', 'super_administrateur', '2005-10-12', 'Autre', '2025-05-12 12:29:14', NULL, NULL, 0, 0, NULL, 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
