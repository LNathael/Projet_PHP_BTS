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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.articles : ~3 rows (environ)
INSERT INTO `articles` (`id_article`, `titre`, `contenu`, `date_creation`, `auteur`) VALUES
	(1, 'Article 1', 'Contenu de l\'article 1', '2025-04-07 12:49:07', 'Auteur 1'),
	(2, 'Article 2', 'Contenu de l\'article 2', '2025-04-07 12:49:07', 'Auteur 2'),
	(3, 'Article 3', 'Contenu de l\'article 3', '2025-04-07 12:49:07', 'Auteur 3');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.commandes : ~7 rows (environ)
INSERT INTO `commandes` (`id_commande`, `id_utilisateur`, `date_commande`, `statut_commande`, `total`) VALUES
	(1, 1, '2025-03-31 13:07:30', 'validée', 40000.00),
	(2, 1, '2025-03-31 13:08:40', 'validée', 40000.00),
	(3, 1, '2025-03-31 13:09:16', 'validée', 10000.00),
	(4, 1, '2025-03-31 13:14:14', 'validée', 10000.00),
	(11, 1, '2025-04-04 18:22:01', 'en attente', 52.30),
	(12, 1, '2025-04-04 18:22:01', 'validée', 52.30),
	(14, 1, '2025-04-04 18:42:45', 'en attente', 29.90);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.details_commande : ~2 rows (environ)
INSERT INTO `details_commande` (`id_detail_commande`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
	(5, 11, 11, 2, 24.90),
	(6, 11, 13, 1, 2.50);

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

-- Listage des données de la table projet_php_.entrainements : ~20 rows (environ)
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
	(36, 'leg day', 1, '2025-04-08', NULL);

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

-- Listage des données de la table projet_php_.exercices : ~16 rows (environ)
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
	(16, 'Tractions', 'Exercice au poids du corps pour renforcer le dos et les biceps.', NULL, NULL, 'Dos');

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
  CONSTRAINT `fk_reply_to` FOREIGN KEY (`reply_to`) REFERENCES `messages` (`id_message`) ON DELETE SET NULL,
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_salon`) REFERENCES `salons` (`id_salon`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`id_recette`) REFERENCES `recettes` (`id_recette`) ON DELETE SET NULL,
  CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.messages : ~0 rows (environ)
INSERT INTO `messages` (`id_message`, `id_utilisateur`, `id_salon`, `contenu`, `date_message`, `id_recette`, `id_produit`, `reply_to`) VALUES
	(1, 1, 1, 'test', '2025-03-21 10:44:08', NULL, NULL, NULL),
	(2, 1, 1, 'e', '2025-03-21 10:48:57', NULL, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.panier : ~0 rows (environ)

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.produits : ~5 rows (environ)
INSERT INTO `produits` (`id_produit`, `nom_produit`, `description`, `prix`, `quantite_disponible`, `libelle`, `image`, `vues`, `date_creation`) VALUES
	(10, 'NutriStrong Whey Pro – Chocolat Intense', 'NutriStrong Whey Pro – Chocolat Intense est une protéine de lactosérum premium, formulée pour maximiser la prise de muscle et accélérer la récupération musculaire.\r\n✔️ 25g de protéines par portion\r\n✔️ Faible en sucre et sans gluten\r\n✔️ Idéal en post-entraînement ou en collation\r\n✔️ Goût chocolat onctueux sans arrière-goût chimique\r\n\r\nParfait pour les sportifs exigeants qui veulent des résultats visibles tout en se régalant !', 29.90, 0, 'Protéine en poudre – Prise de masse &amp; récupération', 'uploads/produits/produit_67efc9e539b6a.png', 29, '2025-04-04 12:00:37'),
	(11, 'NutriStrong Burner X – Brûleur de graisses naturel', 'NutriStrong Burner X est un brûleur de graisses 100% naturel, conçu pour t’aider à sculpter ton corps et améliorer ton métabolisme.\r\n🔸 Formule thermogénique à base de thé vert, caféine et L-carnitine\r\n🔸 Favorise la combustion des graisses et augmente l’énergie\r\n🔸 Idéal en phase de sèche ou de recomposition corporelle\r\n🔸 Sans effets secondaires, sans OGM\r\n\r\n💥 Combine-le à un entraînement régulier pour des résultats optimaux !\r\n\r\n', 24.90, 200, 'Complément alimentaire – Définition musculaire &amp; perte de poids', 'uploads/produits/produit_67efd2bf268df.png', 18, '2025-04-04 12:38:23'),
	(12, 'NutriStrong Creatine Pure – Monohydrate Micronisée', 'NutriStrong Creatine Pure est une créatine monohydrate 100% pure et micronisée, conçue pour améliorer la force explosive, la congestion musculaire et la performance à l&#039;entraînement.\r\n\r\n🔹 5g de créatine pure par dose\r\n🔹 Améliore les performances sur les efforts courts et intenses\r\n🔹 Micronisation ultra-fine pour une meilleure absorption\r\n🔹 Sans arômes, sans additifs, se mélange facilement à n’importe quelle boisson\r\n\r\n📈 Idéale pour les cycles de prise de masse ou de force.', 19.90, 120, 'Créatine en poudre – Force &amp; performance', 'uploads/produits/produit_67efd5d02851c.png', 8, '2025-04-04 12:51:28'),
	(13, 'NutriStrong SVO Bar – Choco-Noisette Crunch', 'NutriStrong SVO Bar est une barre protéinée gourmande et équilibrée, conçue pour les sportifs en quête de snacks pratiques et sains.\r\n\r\n🥜 18g de protéines\r\n🍫 Saveur chocolat-noisette avec éclats croquants\r\n💪 Faible en sucre – Riche en fibres\r\n⏱️ Idéale en collation post-training ou au goûter\r\n\r\nUne vraie alternative aux barres classiques, sans compromis entre plaisir et nutrition !\r\n\r\n', 2.50, 300, 'Barre protéinée – Encas sain &amp; gourmand', 'uploads/produits/produit_67efd720dc0e3.png', 5, '2025-04-04 12:57:04'),
	(14, 'NutriStrong Green Boost – Complexe de Superaliments', 'NutriStrong Green Boost est un mélange de superaliments verts, idéal pour renforcer ta vitalité, soutenir ta récupération et améliorer ta digestion au quotidien.\r\n\r\n🌱 Spiruline, chlorella, épinards, brocoli, thé vert, maca...\r\n🧠 Riche en antioxydants, vitamines et minéraux\r\n💚 Aide à l’élimination des toxines\r\n💪 Renforce l’immunité et le tonus naturel\r\n\r\nÀ prendre le matin dans un smoothie, un jus ou simplement avec de l’eau.', 21.90, 100, 'Mélange de superfoods – Vitalité &amp; récupération', 'uploads/produits/produit_67efd95fe4e19.png', 0, '2025-04-04 13:06:39');

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
  KEY `fk_programmes_user` (`id_utilisateur`) USING BTREE,
  CONSTRAINT `fk_programmes_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.programmes : ~0 rows (environ)

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.recettes : ~4 rows (environ)
INSERT INTO `recettes` (`id_recette`, `titre`, `description`, `categorie`, `ingredients`, `etapes`, `id_utilisateur`, `date_creation`, `image`, `vues`) VALUES
	(3, 'omelette', 'casser les oeuf dans un bol \r\net mettre dans un poêle avec du lait', 'Prise de masse', 'lait\r\noeuf', '2', 2, '2025-01-02 23:22:42', 'uploads/recettes/recette_677711b23dfc5.jpg', 6),
	(4, 'crepe', 'tres bon', 'Prise de masse', 'oeuf \r\nlait\r\nfarine', '2', 1, '2025-01-18 13:06:22', 'uploads/recettes/recette_678b993eb8f7a.webp', 30),
	(5, 'riz poulettte', 'Voici une délicieuse recette de Riz au Poulet simple et savoureuse !', 'Prise de masse', '2 blancs de poulet\r\n\r\n200 g de riz\r\n\r\n1 oignon\r\n\r\n2 gousses d’ail\r\n\r\n1 poivron rouge\r\n\r\n1 tomate\r\n\r\n1 cube de bouillon de volaille\r\n\r\n500 ml d’eau\r\n\r\n1 c. à café de curcuma (ou paprika)\r\n\r\n1 c. à soupe d’huile d’olive\r\n\r\nSel & poivre', 'Faire revenir le poulet :\r\n\r\nCoupez les blancs de poulet en morceaux.\r\n\r\nDans une grande poêle ou une sauteuse, faites chauffer l’huile d’olive.\r\n\r\nFaites dorer le poulet quelques minutes, puis réservez.\r\n\r\nPréparer les légumes :\r\n\r\nÉmincez l’oignon et l’ail.\r\n\r\nCoupez le poivron en petits dés et la tomate en morceaux.\r\n\r\nCuisson :\r\n\r\nDans la même poêle, faites revenir l’oignon et l’ail jusqu’à ce qu’ils deviennent translucides.\r\n\r\nAjoutez le poivron, la tomate et laissez cuire 5 minutes.\r\n\r\nAjoutez le riz, mélangez bien pour qu’il devienne légèrement translucide.\r\n\r\nVersez l’eau avec le cube de bouillon émietté et ajoutez le curcuma, du sel et du poivre.\r\n\r\nMélangez bien et laissez mijoter à feu doux jusqu’à absorption du liquide (environ 15-20 min).\r\n\r\nFinalisation :\r\n\r\nAjoutez le poulet dans la poêle et mélangez.\r\n\r\nLaissez cuire encore 5 minutes pour que les saveurs se mélangent.\r\n\r\nDégustation :\r\n\r\nServez chaud, éventuellement avec du persil frais pour plus de saveur.', 1, '2025-04-03 16:00:06', 'uploads/recettes/recette_67ee94666585c.webp', 13),
	(6, 'Smoothie Protéiné Banane & Avoine', 'Voici une recette de Smoothie Protéiné parfait pour la récupération musculaire ou un petit-déjeuner énergisant ! 💪🥤', 'Prise de masse', '1 banane 🍌\r\n\r\n200 ml de lait (ou lait végétal : amande, soja…) 🥛\r\n\r\n30 g de flocons d’avoine 🌾\r\n\r\n1 dose de protéine en poudre (vanille, chocolat ou neutre) 💪\r\n\r\n1 c. à soupe de beurre de cacahuète 🥜\r\n\r\n1 c. à café de miel ou sirop d’érable 🍯 (optionnel)\r\n\r\nQuelques glaçons (optionnel pour un effet plus frais ❄️)', 'Mixer tous les ingrédients dans un blender jusqu’à obtenir une texture lisse et onctueuse.\r\n\r\nGoûter et ajuster la texture avec un peu plus de lait si besoin.\r\n\r\nServir immédiatement et déguster bien frais !', 1, '2025-04-03 16:11:17', 'uploads/recettes/recette_67ee9705669e7.webp', 4),
	(7, '🥞 Pancakes Classiques', 'Voici une recette simple et délicieuse de Pancakes ! 🥞✨', 'Maintien', '250 g de farine\r\n\r\n2 œufs\r\n\r\n300 ml de lait\r\n\r\n2 c. à soupe de sucre\r\n\r\n1 sachet de levure chimique (environ 10 g)\r\n\r\n1 pincée de sel\r\n\r\n30 g de beurre fondu (ou huile)\r\n\r\n1 c. à café d\'extrait de vanille (optionnel)', 'Préparer la pâte :\r\n\r\nDans un grand bol, tamisez la farine, la levure chimique et la pincée de sel.\r\n\r\nDans un autre bol, battez les œufs avec le sucre. Ajoutez le lait, le beurre fondu et l\'extrait de vanille (si utilisé). Mélangez bien.\r\n\r\nVersez lentement les ingrédients liquides dans le mélange de farine tout en remuant pour éviter les grumeaux. La pâte doit être lisse et légèrement épaisse.\r\n\r\nCuisson des pancakes :\r\n\r\nChauffez une poêle antiadhésive à feu moyen et ajoutez un peu de beurre ou d\'huile.\r\n\r\nVersez une petite louche de pâte dans la poêle chaude. Faites cuire environ 1 à 2 minutes de chaque côté, ou jusqu’à ce que des bulles apparaissent sur le dessus, puis retournez-les délicatement pour dorer l’autre côté.\r\n\r\nDégustation :\r\n\r\nServez les pancakes immédiatement avec du sirop d’érable, des fruits frais, du miel, ou du chocolat fondu, selon vos préférences !', 1, '2025-04-03 16:19:47', 'uploads/recettes/recette_67ee990355eb5.webp', 25);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.seance_exercice : ~8 rows (environ)
INSERT INTO `seance_exercice` (`id_seance_exercice`, `id_entrainement`, `id_exercice`, `poids`, `repetitions`, `series`, `ressenti`, `date`) VALUES
	(1, 1, 0, 50, 10, 0, NULL, '2025-03-21'),
	(2, 26, 2, 20, 4, 2, 'BIEN', '2025-03-28'),
	(3, 28, 1, 22, 10, 4, 'BIEN', '2025-04-01'),
	(4, 28, 4, 20, 10, 20, 'BIEN', '2025-04-01'),
	(5, 32, 1, 4, 20, 20, 'BIEN', '2025-04-02'),
	(9, 36, 2, 140, 4, 3, 'BIEN', '2025-04-08');

-- Listage de la structure de table projet_php_. user_programs
CREATE TABLE IF NOT EXISTS `user_programs` (
  `id_programme` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `objectif` varchar(255) NOT NULL,
  `frequence` int DEFAULT NULL,
  `niveau` varchar(50) NOT NULL,
  `programme` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_programme`),
  KEY `fk_user_programs_user` (`user_id`),
  CONSTRAINT `fk_user_programs_user` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.user_programs : ~11 rows (environ)
INSERT INTO `user_programs` (`id_programme`, `user_id`, `objectif`, `frequence`, `niveau`, `programme`, `created_at`, `updated_at`) VALUES
	(1, 2, 'perte de poids', 4, 'débutant', 'Lundi : Cardio léger (marche rapide, vélo 30 min)\r\nMercredi : Renforcement musculaire avec poids légers\r\nVendredi : Yoga ou stretching', '2025-01-05 19:16:33', '2025-01-05 19:16:41'),
	(2, 2, 'powerlifting', 4, 'débutant', 'Jour 1 : Squat 3x5, Leg Press 3x10, Crunchs 3x12\nJour 2 : Développé couché 3x5, Pompes 3x10, Planche 3x30s\nJour 3 : Soulevé de terre 3x5, Tractions 3x5, Extensions lombaires 3x10', '2025-01-05 19:16:37', '2025-01-05 19:16:37'),
	(3, 2, 'powerlifting', 5, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-06 08:39:29', '2025-01-06 08:39:29'),
	(4, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:34', '2025-01-06 09:26:34'),
	(5, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:40', '2025-01-06 09:26:40'),
	(7, 1, 'powerlifting', 4, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\r\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\r\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\r\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-20 12:04:06', '2025-03-28 09:55:11'),
	(8, 1, 'powerlifting', 4, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-20 12:04:09', '2025-01-20 12:04:09'),
	(9, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:45', '2025-02-14 16:04:45'),
	(10, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:49', '2025-02-14 16:04:49'),
	(11, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:26', '2025-02-17 12:11:26'),
	(12, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:30', '2025-02-17 12:11:30');

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
  `salt` varchar(32) NOT NULL,
  `role` enum('utilisateur','administrateur','super_administrateur') DEFAULT 'utilisateur',
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
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.utilisateurs : ~6 rows (environ)
INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `bio`, `objectifs_fitness`, `objectif`, `mot_de_passe`, `salt`, `role`, `date_naissance`, `sexe`, `date_creation`, `photo_profil`, `badge`, `tentatives`, `bloque`, `dernier_echec`, `notifications_active`) VALUES
	(1, 'Le Bihan', 'Nathaël', 'xarunax69@gmail.com', '', '', '', '$2y$10$j3VjdNMrOq4gyFpKUDs9MOOLRbT4oCohOvzD.TFOhgz17joIST1Oy', '', 'administrateur', '2005-10-12', 'Homme', '2024-12-11 20:54:21', 'uploads/profils/Fichier_000.png', NULL, 0, 0, NULL, 0),
	(2, 'LE BIHAN', 'Nathaël', 'nathael.lebihan12102005@gmail.com', NULL, NULL, NULL, '$2y$10$D03sL3VkP9kD.LRdaljwu.KpvbtU37P7.4yAwkRWL9JbLLrHX8ONi', '', 'utilisateur', '2005-10-12', 'Homme', '2024-12-20 11:39:16', NULL, NULL, 0, 0, NULL, 1),
	(6, 'Nathael', 'dsdza', 'nathael.lebihanIDBZSDZOILD@gmail.com', NULL, NULL, NULL, '$2y$10$WXEO6NJOY/qJ.K2PXSxavOsnSNexYMMbbP0xjszBmbt.6AO9ec4Y6', '', 'super_administrateur', '2055-10-12', 'Homme', '2025-02-14 16:03:52', NULL, NULL, 0, 0, NULL, 1),
	(7, 'LE BIHAN', 'Nathaël', 'xarunax68@gmail.com', NULL, NULL, NULL, '$2y$10$OPoyVHXYDQGYOlrVt56hiuOPNkrT3NEzx9hUBOpwnf.uqQtKokEaW', '', 'super_administrateur', '2005-10-12', 'Homme', '2025-02-17 10:39:04', NULL, NULL, 0, 0, NULL, 1),
	(8, 'Perin', 'Yanis', 'yanis@perin.fr', NULL, NULL, NULL, '$2y$10$AfRQPB6.us5k/1MJ5Jv0cup6iGaj3kCI301CZPAAnvG7Id1R1Lyzy', '', 'utilisateur', '2025-12-03', 'Femme', '2025-03-17 09:11:54', NULL, NULL, 0, 0, NULL, 1),
	(9, 'yanis', 'Perin', 'yanis.perin@gmail.com', NULL, NULL, NULL, '$2y$10$iU3tBG3BFwZVq5.3x5VGZejDJBxMnAHQoQLS3SAg.qcCibSVZjVUO', '', 'utilisateur', '2005-10-12', 'Homme', '2025-03-17 10:11:58', NULL, NULL, 0, 0, NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
