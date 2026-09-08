-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 20 mai 2026 à 09:11
-- Version du serveur : 9.1.0
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `abemarket`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

DROP TABLE IF EXISTS `adresses`;
CREATE TABLE IF NOT EXISTS `adresses` (
  `id_adresse` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `type_adresse` enum('domicile','travail','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'domicile',
  `est_par_defaut` tinyint(1) DEFAULT '0',
  `nom_complet` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_province` int DEFAULT NULL,
  `id_commune` int DEFAULT NULL,
  `id_quartier` int DEFAULT NULL,
  `id_zone` int DEFAULT NULL,
  `id_colline` int DEFAULT NULL,
  `adresse_ligne` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point_repere` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `instructions_livraison` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photo_repere_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_adresse`),
  KEY `idx_adresses_utilisateur` (`id_utilisateur`),
  KEY `idx_adresses_gps` (`latitude`,`longitude`),
  KEY `id_province` (`id_province`),
  KEY `id_commune` (`id_commune`),
  KEY `id_quartier` (`id_quartier`),
  KEY `id_zone` (`id_zone`),
  KEY `id_colline` (`id_colline`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnements`
--

DROP TABLE IF EXISTS `approvisionnements`;
CREATE TABLE IF NOT EXISTS `approvisionnements` (
  `id_appro` int NOT NULL AUTO_INCREMENT,
  `id_produit` int NOT NULL,
  `id_variante` int DEFAULT NULL,
  `id_vendeur` int NOT NULL,
  `quantite_initiale` int NOT NULL,
  `quantite_recue` int NOT NULL,
  `quantite_apres` int NOT NULL,
  `prix_achat_unitaire` decimal(12,2) DEFAULT NULL,
  `cout_total` decimal(12,2) DEFAULT NULL,
  `fournisseur` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_bon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `enregistre_par` int DEFAULT NULL,
  `date_appro` date NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_appro`),
  KEY `idx_appro_produit` (`id_produit`),
  KEY `idx_appro_variante` (`id_variante`),
  KEY `idx_appro_vendeur` (`id_vendeur`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles_commande`
--

DROP TABLE IF EXISTS `articles_commande`;
CREATE TABLE IF NOT EXISTS `articles_commande` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_produit` int DEFAULT NULL,
  `id_variante` int DEFAULT NULL,
  `id_vendeur` int NOT NULL,
  `nom_produit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_produit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_produit_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attributs_variante` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `prix_unitaire` decimal(12,2) NOT NULL,
  `quantite` int NOT NULL,
  `prix_total` decimal(12,2) NOT NULL,
  `taux_commission` decimal(5,2) DEFAULT NULL,
  `montant_commission` decimal(12,2) DEFAULT NULL,
  `revenus_vendeur` decimal(12,2) DEFAULT NULL,
  `statut_article` enum('en_attente','prepare','expedie','livre','retourne','annule') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `est_retourne` tinyint(1) DEFAULT '0',
  `motif_retour` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_demande_retour` timestamp NULL DEFAULT NULL,
  `avis_laisse` tinyint(1) DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_article`),
  KEY `idx_articles_commande` (`id_commande`),
  KEY `idx_articles_vendeur` (`id_vendeur`),
  KEY `idx_articles_produit` (`id_produit`),
  KEY `id_variante` (`id_variante`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `avis_produits`
--

DROP TABLE IF EXISTS `avis_produits`;
CREATE TABLE IF NOT EXISTS `avis_produits` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `id_produit` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_commande` int DEFAULT NULL,
  `note` tinyint(1) NOT NULL,
  `titre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `urls_medias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `achat_verifie` tinyint(1) DEFAULT '0',
  `est_approuve` tinyint(1) DEFAULT '0',
  `reponse_vendeur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_reponse_vendeur` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_avis`),
  UNIQUE KEY `uk_avis_produit_commande` (`id_produit`,`id_utilisateur`,`id_commande`),
  KEY `idx_avis_produit` (`id_produit`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ;

--
-- Déchargement des données de la table `avis_produits`
--

INSERT INTO `avis_produits` (`id_avis`, `id_produit`, `id_utilisateur`, `id_commande`, `note`, `titre`, `commentaire`, `urls_medias`, `achat_verifie`, `est_approuve`, `reponse_vendeur`, `date_reponse_vendeur`, `date_creation`) VALUES
(3, 4, 1, NULL, 5, 'Excellent produit', 'Ces chaussures sont très confortables et de bonne qualité.', NULL, 1, 1, 'hjh', '2026-04-22 13:39:11', '2026-04-22 15:06:49');

-- --------------------------------------------------------

--
-- Structure de la table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id_banner` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('home_main','home_top_right','home_bottom','category','product') COLLATE utf8mb4_unicode_ci DEFAULT 'home_main',
  `ordre_affichage` int DEFAULT '0',
  `est_actif` tinyint(1) DEFAULT '1',
  `date_debut` timestamp NULL DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banners`
--

INSERT INTO `banners` (`id_banner`, `title`, `subtitle`, `image`, `link`, `position`, `ordre_affichage`, `est_actif`, `date_debut`, `date_fin`, `date_creation`) VALUES
(3, 'dfdgs', 'sdgs', 'uploads/banners/banner_20260517_001114_6a0907a257c62.png', 'http://localhost/abemarket/banners/add', 'home_main', 0, 1, NULL, NULL, '2026-05-16 21:23:00');

-- --------------------------------------------------------

--
-- Structure de la table `blacklist_ips`
--

DROP TABLE IF EXISTS `blacklist_ips`;
CREATE TABLE IF NOT EXISTS `blacklist_ips` (
  `id_blacklist` int NOT NULL AUTO_INCREMENT,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `raison` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `cree_par` int DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_blacklist`),
  UNIQUE KEY `adresse_ip` (`adresse_ip`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blacklist_ips`
--

INSERT INTO `blacklist_ips` (`id_blacklist`, `adresse_ip`, `raison`, `date_fin`, `cree_par`, `date_creation`) VALUES
(5, '::1', 'Tentatives de connexion suspectes', NULL, 1, '2026-04-23 12:17:44');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `id_parent` int DEFAULT NULL,
  `nom_categorie` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug_categorie` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau` int DEFAULT '0',
  `icone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `est_actif` tinyint(1) DEFAULT '1',
  `ordre_affichage` int DEFAULT '0',
  `nombre_produits` int DEFAULT '0',
  `taux_commission_specifique` decimal(5,2) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_categorie`),
  UNIQUE KEY `slug_categorie` (`slug_categorie`),
  KEY `idx_categories_parent` (`id_parent`),
  KEY `idx_categories_actif` (`est_actif`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `id_parent`, `nom_categorie`, `slug_categorie`, `niveau`, `icone`, `url_image`, `description`, `est_actif`, `ordre_affichage`, `nombre_produits`, `taux_commission_specifique`, `date_creation`) VALUES
(1, NULL, 'Électronique', 'electronique', 0, '', 'uploads/categories/categorie_20260511_172533_6a02110d37ec0.png', '', 1, 1, 0, 5.00, '2026-04-09 03:32:51'),
(2, NULL, 'Mode & Habillement', 'mode-habillement', 0, '', 'uploads/categories/categorie_20260511_172552_6a02112009442.png', '', 0, 2, 0, NULL, '2026-04-09 03:32:51'),
(3, NULL, 'Maison & Cuisine', 'maison-cuisine', 0, '', 'uploads/categories/categorie_20260511_172608_6a021130b2a1a.jpg', '', 1, 3, 0, NULL, '2026-04-09 03:32:51'),
(4, NULL, 'Beauté & Santé', 'beaute-sante', 0, NULL, NULL, NULL, 1, 4, 0, NULL, '2026-04-09 03:32:51'),
(5, NULL, 'Alimentation', 'alimentation', 0, '', 'uploads/categories/categorie_20260511_172626_6a021142d973b.jpeg', '', 1, 5, 0, NULL, '2026-04-09 03:32:51'),
(6, NULL, 'Sports & Loisirs', 'sports-loisirs', 0, NULL, NULL, NULL, 1, 6, 0, NULL, '2026-04-09 03:32:51'),
(7, NULL, 'Agriculture & Élevage', 'agriculture-elevage', 0, NULL, NULL, NULL, 1, 7, 0, NULL, '2026-04-09 03:32:51'),
(8, 1, 'Téléphones', 't-l-phones', 1, '', NULL, '', 1, 1, 0, NULL, '2026-04-09 03:32:51'),
(9, 1, 'Informatique', 'informatique', 1, NULL, NULL, NULL, 1, 2, 0, NULL, '2026-04-09 03:32:51'),
(10, 1, 'TV & Audio', 'tv-audio', 1, '', NULL, '', 1, 3, 0, NULL, '2026-04-09 03:32:51'),
(11, 2, 'Vêtements Homme', 'vetements-homme', 1, NULL, NULL, NULL, 1, 1, 0, NULL, '2026-04-09 03:32:51'),
(12, 2, 'Vêtements Femme', 'vetements-femme', 1, NULL, NULL, NULL, 1, 2, 0, NULL, '2026-04-09 03:32:51'),
(13, 2, 'Chaussures', 'chaussures', 1, NULL, NULL, NULL, 1, 3, 0, NULL, '2026-04-09 03:32:51'),
(14, 5, 'Épicerie', 'epicerie', 1, NULL, NULL, NULL, 1, 1, 0, NULL, '2026-04-09 03:32:51'),
(15, 5, 'Produits Locaux', 'produits-locaux', 1, NULL, NULL, NULL, 1, 2, 0, NULL, '2026-04-09 03:32:51'),
(16, 7, 'Semences & Engrais', 'semences-engrais', 1, NULL, NULL, NULL, 1, 1, 0, NULL, '2026-04-09 03:32:51'),
(17, 7, 'Matériel Agricole', 'materiel-agricole', 1, NULL, NULL, NULL, 1, 2, 0, NULL, '2026-04-09 03:32:51'),
(18, 8, 'Accessoires Téléphones', 'accessoires-telephones', 2, 'bx-mobile', 'uploads/categories/categorie_20260511_172647_6a0211572f247.jpg', 'Coques, chargeurs, écouteurs', 1, 4, 0, 8.00, '2026-04-13 20:05:49'),
(19, 2, 'Accessoires Mode', 'accessoires-mode', 1, 'bx-watch', NULL, 'Montres, ceintures, lunettes', 1, 4, 0, 10.00, '2026-04-13 20:05:49'),
(20, 5, 'Boissons', 'boissons', 1, 'bx-drink', NULL, 'Jus, sodas, eaux', 1, 3, 0, 8.00, '2026-04-13 20:05:49'),
(22, 1, 'ZXASD', 'zxasd', 1, 'BX', 'uploads/categories/categorie_20260422_160939_69e8f2c35c65c.png', '', 1, 4, 0, 30.00, '2026-04-22 14:09:39');

-- --------------------------------------------------------

--
-- Structure de la table `codes_otp`
--

DROP TABLE IF EXISTS `codes_otp`;
CREATE TABLE IF NOT EXISTS `codes_otp` (
  `id_otp` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_otp` enum('connexion','verification_telephone','verification_email','reinitialisation_mdp') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tentatives` int DEFAULT '0',
  `date_expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `utilise` tinyint(1) DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_otp`),
  KEY `idx_otp_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `codes_otp`
--

INSERT INTO `codes_otp` (`id_otp`, `id_utilisateur`, `code`, `type_otp`, `telephone`, `email`, `tentatives`, `date_expiration`, `utilise`, `date_creation`) VALUES
(1, 1, '123456', 'connexion', '+25761234567', 'admin@abemarket.com', 0, '2026-04-23 17:07:41', 0, '2026-04-23 16:57:41'),
(2, 2, '789012', 'verification_telephone', '+25762345678', 'client@example.com', 0, '2026-04-23 17:02:41', 0, '2026-04-23 16:57:41'),
(4, 3, '025948', 'verification_telephone', '349583953', '', 0, '2026-04-23 15:14:16', 0, '2026-04-23 14:59:16'),
(5, 4, '158672', 'verification_telephone', '3463646', '', 0, '2026-04-23 15:14:37', 0, '2026-04-23 14:59:37'),
(13, 18, '870111', 'verification_email', NULL, 'dushimepaul51@gmail.com', 0, '2026-05-18 22:57:18', 1, '2026-05-18 20:56:52');

-- --------------------------------------------------------

--
-- Structure de la table `collines`
--

DROP TABLE IF EXISTS `collines`;
CREATE TABLE IF NOT EXISTS `collines` (
  `id_colline` int NOT NULL AUTO_INCREMENT,
  `id_zone` int NOT NULL,
  `colline_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_colline`),
  KEY `idx_collines_zone` (`id_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `numero_commande` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_adresse_livraison` int DEFAULT NULL,
  `sous_total` decimal(12,2) NOT NULL,
  `frais_livraison` decimal(10,2) DEFAULT '0.00',
  `montant_reduction` decimal(10,2) DEFAULT '0.00',
  `montant_total` decimal(12,2) NOT NULL,
  `code_coupon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reduction_coupon` decimal(10,2) DEFAULT '0.00',
  `id_mode_payement` int NOT NULL,
  `statut_paiement` enum('en_attente','en_cours','paye','echoue','rembourse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `date_paiement` timestamp NULL DEFAULT NULL,
  `type_livraison` enum('domicile','point_relais') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'domicile',
  `id_point_relais` int DEFAULT NULL,
  `id_transporteur` int DEFAULT NULL,
  `statut_commande` enum('en_attente','confirme','en_preparation','expedie','en_livraison','livre','annule','retourne') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `numero_suivi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_expedition` timestamp NULL DEFAULT NULL,
  `date_livraison_prevue` timestamp NULL DEFAULT NULL,
  `date_livraison_reelle` timestamp NULL DEFAULT NULL,
  `qr_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reception_confirmee` tinyint(1) DEFAULT '0',
  `date_confirmation_reception` timestamp NULL DEFAULT NULL,
  `note_client` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `note_interne` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `canal_commande` enum('web','mobile_android','mobile_ios','ussd') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_commande`),
  UNIQUE KEY `numero_commande` (`numero_commande`),
  KEY `idx_commandes_utilisateur` (`id_utilisateur`),
  KEY `idx_commandes_statut` (`statut_commande`),
  KEY `idx_commandes_date` (`date_creation`),
  KEY `idx_commandes_transporteur` (`id_transporteur`),
  KEY `id_adresse_livraison` (`id_adresse_livraison`),
  KEY `id_mode_payement` (`id_mode_payement`),
  KEY `id_point_relais` (`id_point_relais`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `numero_commande`, `id_utilisateur`, `id_adresse_livraison`, `sous_total`, `frais_livraison`, `montant_reduction`, `montant_total`, `code_coupon`, `reduction_coupon`, `id_mode_payement`, `statut_paiement`, `date_paiement`, `type_livraison`, `id_point_relais`, `id_transporteur`, `statut_commande`, `numero_suivi`, `date_expedition`, `date_livraison_prevue`, `date_livraison_reelle`, `qr_token`, `reception_confirmee`, `date_confirmation_reception`, `note_client`, `note_interne`, `canal_commande`, `date_creation`, `date_modification`) VALUES
(3, 'CMD-20260423-0001', 2, NULL, 25000.00, 2000.00, 0.00, 27000.00, NULL, 0.00, 5, 'rembourse', '2026-04-23 12:00:00', 'domicile', NULL, 1, 'confirme', 'LV-20260423-001', '2026-04-20 06:00:00', '2026-04-25 16:00:00', '2026-04-23 13:30:00', 'du7A856rbkPp42fGVhFcUsIgKmvtwNEJ', 1, '2026-04-23 14:00:00', 'Livraison rapide, merci !', '', 'web', '2026-04-23 15:47:53', '2026-04-23 16:22:34'),
(4, 'CMD-20260423-0002', 2, NULL, 39990.00, 2000.00, 500.00, 41490.00, 'PROMO5', 500.00, 1, 'paye', '2026-04-23 08:00:00', 'domicile', NULL, 1, 'en_livraison', 'LV-20260423-002', '2026-04-23 07:00:00', '2026-04-26 16:00:00', NULL, '4xbey1mfDzNnhMOd9qjolra5uAYLwXQI', 0, NULL, 'Livrez avant 17h svp', NULL, 'mobile_android', '2026-04-23 15:47:53', '2026-04-23 16:25:42');

-- --------------------------------------------------------

--
-- Structure de la table `communes`
--

DROP TABLE IF EXISTS `communes`;
CREATE TABLE IF NOT EXISTS `communes` (
  `id_commune` int NOT NULL AUTO_INCREMENT,
  `id_province` int NOT NULL,
  `commune_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_commune`),
  KEY `idx_communes_province` (`id_province`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `communes`
--

INSERT INTO `communes` (`id_commune`, `id_province`, `commune_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 2, 'Ntahangwa', 99.99999999, 999.99999999, 1);

-- --------------------------------------------------------

--
-- Structure de la table `config_paiement_vendeur`
--

DROP TABLE IF EXISTS `config_paiement_vendeur`;
CREATE TABLE IF NOT EXISTS `config_paiement_vendeur` (
  `id_config` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int NOT NULL,
  `methode_principale` enum('mobile_money','virement_bancaire') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operateur_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_mobile_money` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_abonne_mobile` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_titulaire` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_compte` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_banque` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_verifie` tinyint(1) DEFAULT '0',
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_config`),
  KEY `idx_config_vendeur` (`id_vendeur`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `config_paiement_vendeur`
--

INSERT INTO `config_paiement_vendeur` (`id_config`, `id_vendeur`, `methode_principale`, `operateur_mobile`, `numero_mobile_money`, `nom_abonne_mobile`, `nom_titulaire`, `numero_compte`, `nom_banque`, `est_verifie`, `est_actif`, `date_creation`) VALUES
(2, 1, 'virement_bancaire', NULL, NULL, NULL, 'Jean Dupont', 'FR76 3000 6000 0112 3456 7890 123', 'BNP Paribas', 1, 1, '2026-04-23 14:40:18'),
(6, 5, 'mobile_money', '', '', '', NULL, NULL, NULL, 0, 1, '2026-05-18 21:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id_coupon` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_reduction` enum('pourcentage','montant_fixe','livraison_gratuite') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valeur_reduction` decimal(12,2) NOT NULL,
  `montant_min_achat` decimal(12,2) DEFAULT NULL,
  `montant_max_reduction` decimal(12,2) DEFAULT NULL,
  `limite_utilisation` int DEFAULT NULL,
  `nombre_utilisations` int DEFAULT '0',
  `limite_par_utilisateur` int DEFAULT '1',
  `date_debut` timestamp NULL DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `applicable_a` enum('tout','categories','produits','vendeurs') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'tout',
  `ids_applicables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_coupon`),
  UNIQUE KEY `code` (`code`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `documents_vendeur`
--

DROP TABLE IF EXISTS `documents_vendeur`;
CREATE TABLE IF NOT EXISTS `documents_vendeur` (
  `id_document` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int NOT NULL,
  `type_document` enum('carte_identite','passeport','licence_commerce','attestation_fiscale','justificatif_domicile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_document` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fichier_document` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut_verification` enum('en_attente','verifie','refuse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `date_verification` timestamp NULL DEFAULT NULL,
  `verifie_par` int DEFAULT NULL,
  `motif_refus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_expiration` date DEFAULT NULL,
  `date_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_document`),
  KEY `idx_documents_vendeur` (`id_vendeur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents_vendeur`
--

INSERT INTO `documents_vendeur` (`id_document`, `id_vendeur`, `type_document`, `numero_document`, `fichier_document`, `statut_verification`, `date_verification`, `verifie_par`, `motif_refus`, `date_expiration`, `date_upload`) VALUES
(1, 1, 'passeport', '023823', 'uploads/documents_vendeurs/document_20260423_152906_69ea3ac268d03.png', 'en_attente', NULL, NULL, NULL, '2026-04-23', '2026-04-23 13:29:06');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations_vendeurs`
--

DROP TABLE IF EXISTS `evaluations_vendeurs`;
CREATE TABLE IF NOT EXISTS `evaluations_vendeurs` (
  `id_evaluation` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_commande` int NOT NULL,
  `note_globale` tinyint(1) NOT NULL,
  `note_communication` tinyint(1) DEFAULT NULL,
  `note_livraison` tinyint(1) DEFAULT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `est_approuve` tinyint(1) DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_evaluation`),
  UNIQUE KEY `uk_eval_vendeur_commande` (`id_vendeur`,`id_commande`),
  KEY `idx_eval_vendeur` (`id_vendeur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `historique_statut_commande`
--

DROP TABLE IF EXISTS `historique_statut_commande`;
CREATE TABLE IF NOT EXISTS `historique_statut_commande` (
  `id_historique` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `statut` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `modifie_par` int DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_historique`),
  KEY `idx_historique_commande` (`id_commande`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `historique_statut_commande`
--

INSERT INTO `historique_statut_commande` (`id_historique`, `id_commande`, `statut`, `commentaire`, `modifie_par`, `latitude`, `longitude`, `date_creation`) VALUES
(8, 3, 'expedie', 'Colis remis au transporteur', 1, NULL, NULL, '2026-04-23 07:00:00'),
(9, 3, 'en_livraison', 'Livreur en route vers le client', 4, -3.38500000, 29.36500000, '2026-04-23 09:00:00'),
(10, 3, 'en_livraison', 'Proche de la destination', 4, -3.38300000, 29.36200000, '2026-04-23 12:30:00'),
(11, 3, 'en_livraison', 'Livreur arrivé, en attente du client', 4, NULL, NULL, '2026-04-23 13:00:00'),
(12, 3, 'en_attente', '', 1, NULL, NULL, '2026-04-23 14:05:15'),
(13, 3, 'confirme', '', 1, NULL, NULL, '2026-04-23 14:05:20'),
(14, 3, 'confirme', 'Modification manuelle par administrateur', 1, NULL, NULL, '2026-04-23 14:22:11'),
(15, 3, 'confirme', 'Modification manuelle par administrateur', 1, NULL, NULL, '2026-04-23 14:22:24'),
(16, 3, 'confirme', 'Modification manuelle par administrateur', 1, NULL, NULL, '2026-04-23 14:22:34'),
(24, 3, 'en_attente', 'Commande créée par le client', 2, NULL, NULL, '2026-04-18 08:00:00'),
(25, 3, 'confirme', 'Paiement reçu, commande confirmée', 1, NULL, NULL, '2026-04-18 08:30:00'),
(26, 3, 'en_preparation', 'Le vendeur prépare la commande', 3, NULL, NULL, '2026-04-19 07:00:00'),
(27, 3, 'expedie', 'Commande expédiée', 1, NULL, NULL, '2026-04-20 06:00:00'),
(28, 4, 'en_attente', 'Commande créée par le client via mobile', 2, NULL, NULL, '2026-04-21 07:00:00'),
(29, 4, 'confirme', 'Paiement Bancobu confirmé', 1, NULL, NULL, '2026-04-21 07:15:00'),
(30, 4, 'en_preparation', 'Préparation en cours par le vendeur', 3, NULL, NULL, '2026-04-21 12:00:00'),
(31, 4, 'expedie', 'Colis remis au transporteur', 1, NULL, NULL, '2026-04-23 07:00:00'),
(32, 4, 'en_livraison', 'Livreur en route vers le client', 4, NULL, NULL, '2026-04-23 09:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `images_produit`
--

DROP TABLE IF EXISTS `images_produit`;
CREATE TABLE IF NOT EXISTS `images_produit` (
  `id_image` int NOT NULL AUTO_INCREMENT,
  `id_produit` int NOT NULL,
  `id_variante` int DEFAULT NULL,
  `url_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_miniature` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `texte_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_principale` tinyint(1) DEFAULT '0',
  `ordre_affichage` int DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_image`),
  KEY `idx_images_produit` (`id_produit`),
  KEY `idx_images_variante` (`id_variante`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images_produit`
--

INSERT INTO `images_produit` (`id_image`, `id_produit`, `id_variante`, `url_image`, `url_miniature`, `texte_alt`, `est_principale`, `ordre_affichage`, `date_creation`) VALUES
(1, 1, NULL, 'uploads/produits/iphone14-main.jpg', 'uploads/produits/iphone14-main-thumb.jpg', 'iPhone 14 Pro', 0, 1, '2026-04-13 20:05:49'),
(2, 1, NULL, 'uploads/produits/iphone14-2.jpg', 'uploads/produits/iphone14-2-thumb.jpg', 'iPhone 14 Pro vue arrière', 0, 2, '2026-04-13 20:05:49'),
(3, 2, NULL, 'uploads/produits/dell-main.jpg', 'uploads/produits/dell-main-thumb.jpg', 'Dell Inspiron', 0, 1, '2026-04-13 20:05:49'),
(4, 3, NULL, 'uploads/produits/tshirt-main.jpg', 'uploads/produits/tshirt-main-thumb.jpg', 'T-Shirt Nike', 0, 1, '2026-04-13 20:05:49'),
(7, 4, NULL, 'uploads/produits/20260422130852_69e8c86495cc3.png', 'uploads/produits/thumb_20260422130852_69e8c86495cc3.png', 'Chaussures de Sport', 1, 3, '2026-04-22 11:08:52'),
(8, 4, NULL, 'uploads/produits/20260422131545_69e8ca012ad4e.png', 'uploads/produits/thumb_20260422131545_69e8ca012ad4e.png', 'Chaussures de Sport', 0, 4, '2026-04-22 11:15:45'),
(14, 3, NULL, 'uploads/produits/20260511172254_6a02106e2623e.png', 'uploads/produits/thumb_20260511172254_6a02106e2623e.png', 'T-Shirt Homme', 1, 2, '2026-05-11 15:22:54'),
(15, 2, NULL, 'uploads/produits/20260511172358_6a0210aed4195.jpg', 'uploads/produits/thumb_20260511172358_6a0210aed4195.jpg', 'Ordinateur Portable Dell', 1, 2, '2026-05-11 15:24:00'),
(16, 1, NULL, 'uploads/produits/20260511172440_6a0210d85a5a8.jpeg', 'uploads/produits/thumb_20260511172440_6a0210d85a5a8.jpeg', 'iPhone 14 Pro', 1, 3, '2026-05-11 15:24:40');

-- --------------------------------------------------------

--
-- Structure de la table `ips_bloquees`
--

DROP TABLE IF EXISTS `ips_bloquees`;
CREATE TABLE IF NOT EXISTS `ips_bloquees` (
  `id_ip` int NOT NULL AUTO_INCREMENT,
  `adresse_ip` varchar(45) NOT NULL,
  `motif` text,
  `date_blocage` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bloque_par` int DEFAULT NULL,
  PRIMARY KEY (`id_ip`),
  UNIQUE KEY `adresse_ip` (`adresse_ip`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liste_souhaits`
--

DROP TABLE IF EXISTS `liste_souhaits`;
CREATE TABLE IF NOT EXISTS `liste_souhaits` (
  `id_souhait` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_produit` int NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_souhait`),
  UNIQUE KEY `unique_souhait` (`id_utilisateur`,`id_produit`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `liste_souhaits`
--

INSERT INTO `liste_souhaits` (`id_souhait`, `id_utilisateur`, `id_produit`, `date_ajout`) VALUES
(2, 18, 4, '2026-05-19 16:39:39'),
(6, 18, 2, '2026-05-19 17:03:07');

-- --------------------------------------------------------

--
-- Structure de la table `litiges_commandes`
--

DROP TABLE IF EXISTS `litiges_commandes`;
CREATE TABLE IF NOT EXISTS `litiges_commandes` (
  `id_litige` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_plaignant` int NOT NULL,
  `type_plaignant` enum('acheteur','vendeur') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_defendeur` int NOT NULL,
  `raison` enum('non_recu','endommage','non_conforme','paiement','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pieces_jointes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `statut` enum('ouvert','en_mediation','resolu_acheteur','resolu_vendeur','ferme') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ouvert',
  `mediateur_id` int DEFAULT NULL,
  `decision` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `montant_rembourse` decimal(12,2) DEFAULT NULL,
  `date_resolution` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_litige`),
  KEY `idx_litiges_commande` (`id_commande`),
  KEY `idx_litiges_statut` (`statut`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `logs_audit`
--

DROP TABLE IF EXISTS `logs_audit`;
CREATE TABLE IF NOT EXISTS `logs_audit` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `type_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_cible` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cible` int DEFAULT NULL,
  `valeurs_avant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `valeurs_apres` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_audit_utilisateur` (`id_utilisateur`),
  KEY `idx_audit_type` (`type_action`),
  KEY `idx_audit_date` (`date_creation`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `mode_payement`
--

DROP TABLE IF EXISTS `mode_payement`;
CREATE TABLE IF NOT EXISTS `mode_payement` (
  `id_mode_payement` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('mobile_money','carte_bancaire','virement','especes_livraison') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mobile_money',
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frais_fixe` decimal(10,2) DEFAULT '0.00',
  `frais_pourcentage` decimal(5,2) DEFAULT '0.00',
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `est_actif` tinyint(1) NOT NULL DEFAULT '1',
  `ordre_affichage` int DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mode_payement`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mode_payement`
--

INSERT INTO `mode_payement` (`id_mode_payement`, `code`, `description`, `type`, `logo_url`, `frais_fixe`, `frais_pourcentage`, `instructions`, `est_actif`, `ordre_affichage`, `date_creation`) VALUES
(1, 'BANCOBU', 'Bancobu Inoti', 'mobile_money', 'uploads/mode_payement/logo_20260423_153632_69ea3c80263d4.png', 0.00, 1.50, 'Envoyez le montant au numéro affiché via Bancobu Inoti et saisissez la référence', 1, 0, '2026-04-09 03:32:51'),
(2, 'LUMICASH', 'Lumicash', 'mobile_money', NULL, 0.00, 1.50, 'Composez *164# et suivez les instructions pour effectuer le paiement', 1, 0, '2026-04-09 03:32:51'),
(3, 'ECOCASH', 'EcoCash', 'mobile_money', NULL, 0.00, 1.50, 'Utilisez l\'application EcoCash pour effectuer le paiement', 1, 0, '2026-04-09 03:32:51'),
(4, 'CARTE', 'Carte Bancaire', 'carte_bancaire', NULL, 500.00, 2.00, 'Paiement sécurisé par carte Visa/Mastercard', 1, 0, '2026-04-09 03:32:51'),
(5, 'ESPECES_LIVRAISON', 'Paiement à la livraison', 'especes_livraison', NULL, 0.00, 0.00, 'Payez en espèces au livreur à la réception de votre colis', 1, 0, '2026-04-09 03:32:51');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_abonnes`
--

DROP TABLE IF EXISTS `newsletter_abonnes`;
CREATE TABLE IF NOT EXISTS `newsletter_abonnes` (
  `id_abonne` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_abonne`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `newsletter_abonnes`
--

INSERT INTO `newsletter_abonnes` (`id_abonne`, `email`, `date_inscription`, `est_actif`) VALUES
(1, 'dushiem@gmail.com', '2026-05-14 08:53:01', 1),
(2, 'dhsdsdhsk@gmail.com', '2026-05-14 12:08:41', 1),
(3, 'admin@magarameza.com', '2026-05-16 21:23:45', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id_notification` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `type_canal` enum('sms','push','in_app') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie` enum('commande','paiement','livraison','securite','systeme') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_lue` tinyint(1) DEFAULT '0',
  `date_lecture` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notification`),
  KEY `idx_notif_utilisateur` (`id_utilisateur`),
  KEY `idx_notif_lue` (`est_lue`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements_vendeurs`
--

DROP TABLE IF EXISTS `paiements_vendeurs`;
CREATE TABLE IF NOT EXISTS `paiements_vendeurs` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int NOT NULL,
  `id_config_paiement` int DEFAULT NULL,
  `date_debut_periode` date NOT NULL,
  `date_fin_periode` date NOT NULL,
  `nombre_commandes` int DEFAULT '0',
  `total_revenus` decimal(15,2) DEFAULT '0.00',
  `total_commissions` decimal(15,2) DEFAULT '0.00',
  `total_remboursements` decimal(15,2) DEFAULT '0.00',
  `montant_net` decimal(15,2) NOT NULL,
  `statut` enum('en_attente','en_cours','paye','echoue') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `date_paiement` timestamp NULL DEFAULT NULL,
  `reference_transaction` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paiement`),
  KEY `idx_paiements_vendeur` (`id_vendeur`),
  KEY `id_config_paiement` (`id_config_paiement`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiements_vendeurs`
--

INSERT INTO `paiements_vendeurs` (`id_paiement`, `id_vendeur`, `id_config_paiement`, `date_debut_periode`, `date_fin_periode`, `nombre_commandes`, `total_revenus`, `total_commissions`, `total_remboursements`, `montant_net`, `statut`, `date_paiement`, `reference_transaction`, `date_creation`) VALUES
(1, 1, 2, '2026-03-01', '2026-03-31', 25, 12500.00, 1250.00, 0.00, 11250.00, 'paye', '2026-04-05 08:30:00', 'VIREMENT_BNP_MARS_001', '2026-04-23 15:08:53');

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

DROP TABLE IF EXISTS `paniers`;
CREATE TABLE IF NOT EXISTS `paniers` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_produit` int NOT NULL,
  `id_variante` int DEFAULT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_panier`),
  UNIQUE KEY `unique_panier` (`id_utilisateur`,`id_produit`,`id_variante`),
  KEY `idx_paniers_utilisateur` (`id_utilisateur`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`id_panier`, `id_utilisateur`, `id_produit`, `id_variante`, `quantite`, `date_ajout`, `date_modification`) VALUES
(2, 18, 4, NULL, 1, '2026-05-19 16:49:53', '2026-05-19 18:49:53'),
(3, 18, 2, NULL, 1, '2026-05-19 17:02:39', '2026-05-19 19:02:39'),
(4, 18, 1, NULL, 1, '2026-05-19 17:02:45', '2026-05-19 19:02:45'),
(5, 18, 3, NULL, 1, '2026-05-19 17:02:47', '2026-05-19 19:02:47');

-- --------------------------------------------------------

--
-- Structure de la table `points_relais`
--

DROP TABLE IF EXISTS `points_relais`;
CREATE TABLE IF NOT EXISTS `points_relais` (
  `id_point` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('boutique_partenaire','kiosque','bureau_poste') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'boutique_partenaire',
  `adresse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_commune` int DEFAULT NULL,
  `id_quartier` int DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `horaires` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `capacite_max` int DEFAULT '50',
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_point`),
  KEY `idx_relais_gps` (`latitude`,`longitude`),
  KEY `id_commune` (`id_commune`),
  KEY `id_quartier` (`id_quartier`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int DEFAULT NULL,
  `id_categorie` int DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_produit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_produit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug_produit` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_courte` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `marque` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_base` decimal(12,2) NOT NULL,
  `prix_promo` decimal(12,2) DEFAULT NULL,
  `date_debut_promo` timestamp NULL DEFAULT NULL,
  `date_fin_promo` timestamp NULL DEFAULT NULL,
  `quantite_actuelle` int DEFAULT '0',
  `seuil_stock_bas` int DEFAULT '5',
  `statut_stock` enum('en_stock','stock_bas','rupture_stock') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_stock',
  `poids_kg` decimal(8,3) DEFAULT NULL,
  `longueur_cm` decimal(8,2) DEFAULT NULL,
  `largeur_cm` decimal(8,2) DEFAULT NULL,
  `hauteur_cm` decimal(8,2) DEFAULT NULL,
  `type_produit` enum('simple','variable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'simple',
  `note_moyenne` decimal(3,2) DEFAULT '0.00',
  `nombre_avis` int DEFAULT '0',
  `nombre_ventes` int DEFAULT '0',
  `nombre_vues` int DEFAULT '0',
  `statut` enum('brouillon','en_attente','actif','suspendu','supprime') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'brouillon',
  `date_publication` timestamp NULL DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produit`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `slug_produit` (`slug_produit`),
  UNIQUE KEY `code_produit` (`code_produit`),
  KEY `idx_produits_vendeur` (`id_vendeur`),
  KEY `idx_produits_categorie` (`id_categorie`),
  KEY `idx_produits_statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `id_vendeur`, `id_categorie`, `sku`, `code_produit`, `nom_produit`, `slug_produit`, `description_courte`, `description`, `marque`, `prix_base`, `prix_promo`, `date_debut_promo`, `date_fin_promo`, `quantite_actuelle`, `seuil_stock_bas`, `statut_stock`, `poids_kg`, `longueur_cm`, `largeur_cm`, `hauteur_cm`, `type_produit`, `note_moyenne`, `nombre_avis`, `nombre_ventes`, `nombre_vues`, `statut`, `date_publication`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, NULL, 8, 'SKU001', 'PROD001', 'iPhone 14 Pro', 'iphone-14-pro', 'Smartphone haut de gamme', 'iPhone 14 Pro avec écran Super Retina XDR', 'Apple', 1200000.00, 1100000.00, '2026-04-12 22:00:00', '2026-06-12 22:00:00', 25, 5, 'en_stock', 0.240, NULL, NULL, NULL, 'simple', 4.80, 12, 8, 250, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-05-19 18:04:18'),
(2, NULL, 9, 'SKU002', 'PROD002', 'Ordinateur Portable Dell', 'ordinateur-portable-dell', 'PC portable performance', 'Dell Inspiron 15 avec processeur Intel Core i7', 'Dell', 850000.00, 799000.00, '2026-04-12 22:00:00', '2026-04-27 22:00:00', 10, 3, 'en_stock', 2.100, NULL, NULL, NULL, 'simple', 4.50, 8, 5, 180, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-05-11 15:24:17'),
(3, NULL, 11, 'SKU003', 'PROD003', 'T-Shirt Homme', 't-shirt-homme', 'T-shirt en coton', 'T-shirt de qualité supérieure 100% coton', 'Nike', 25000.00, 19990.00, '2026-04-12 22:00:00', '2026-05-02 22:00:00', 130, 20, 'en_stock', 0.200, NULL, NULL, NULL, 'variable', 4.20, 25, 30, 320, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-05-11 15:23:14'),
(4, NULL, 13, 'SKU004', 'PROD004', 'Chaussures de Sport', 'chaussures-sport', 'Chaussures running', 'Chaussures légères pour la course', 'Adidas', 45000.00, 39990.00, NULL, NULL, 115, 10, 'en_stock', 0.800, NULL, NULL, NULL, 'variable', 4.60, 18, 22, 299, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-05-19 18:50:14');

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

DROP TABLE IF EXISTS `profils`;
CREATE TABLE IF NOT EXISTS `profils` (
  `id_profil` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profil`),
  UNIQUE KEY `description` (`description`)
) ;

--
-- Déchargement des données de la table `profils`
--

INSERT INTO `profils` (`id_profil`, `description`, `permissions`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, 'super_admin', '[\"viewDashboard\",\"manageUtilisateurs\",\"manageProfils\",\"manageProduits\",\"manageCommandes\",\"manageVendeurs\",\"manageCategories\",\"manageCoupons\",\"managePaiements\",\"manageLivraisons\",\"manageLitiges\",\"manageTransporteurs\",\"viewRapports\",\"validateKyc\",\"manageAvis\",\"manageMesProduits\",\"viewMesCommandes\",\"manageMesApprovisionnements\",\"viewMesSoldes\",\"manageMaBoutique\",\"viewProduits\",\"manageMonPanier\",\"manageMesAvis\",\"viewMesNotifications\",\"viewMesLivraisons\",\"updateStatutLivraison\",\"viewCarteGps\",\"manageVirements\",\"manageSoldesVendeurs\",\"viewUtilisateurs\",\"manageRetours\"]', 1, '2026-04-09 03:32:51', '2026-04-13 19:10:27'),
(2, 'admin', '[\"viewDashboard\",\"manageProduits\",\"manageCommandes\",\"manageVendeurs\",\"manageCategories\",\"manageCoupons\",\"manageLivraisons\",\"manageLitiges\",\"viewRapports\",\"validateKyc\"]', 0, '2026-04-09 03:32:51', '2026-04-13 19:18:49'),
(3, 'moderateur', '[\"viewDashboard\",\"manageProduits\",\"manageAvis\",\"validateKyc\",\"viewRapports\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51'),
(4, 'vendeur', '[\"viewDashboard\",\"manageMesProduits\",\"viewMesCommandes\",\"manageMesApprovisionnements\",\"viewMesSoldes\",\"manageMaBoutique\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51'),
(5, 'client', '[\"viewProduits\",\"manageMonPanier\",\"manageMesCommandes\",\"manageMesAvis\",\"viewMesNotifications\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51'),
(6, 'livreur', '[\"viewMesLivraisons\",\"updateStatutLivraison\",\"viewCarteGps\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51'),
(7, 'finance', '[\"viewDashboard\",\"managePaiements\",\"manageVirements\",\"viewRapports\",\"manageSoldesVendeurs\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51'),
(8, 'support', '[\"viewDashboard\",\"viewCommandes\",\"manageLitiges\",\"viewUtilisateurs\",\"manageRetours\"]', 1, '2026-04-09 03:32:51', '2026-04-09 03:32:51');

-- --------------------------------------------------------

--
-- Structure de la table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
CREATE TABLE IF NOT EXISTS `provinces` (
  `id_province` int NOT NULL AUTO_INCREMENT,
  `province_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_province`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `provinces`
--

INSERT INTO `provinces` (`id_province`, `province_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 'Bubanza', -3.08000000, 29.39000000, 1),
(2, 'Bujumbura', -3.38200000, 29.36110000, 1),
(3, 'Bururi', -3.95000000, 29.62000000, 1),
(4, 'Cankuzo', -3.22000000, 30.55000000, 1),
(5, 'Cibitoke', -2.89000000, 29.12000000, 1),
(6, 'Gitega', -3.42640000, 29.92460000, 1),
(7, 'Karuzi', -3.10000000, 30.17000000, 1),
(8, 'Kayanza', -2.92000000, 29.63000000, 1),
(9, 'Kirundo', -2.58000000, 30.09000000, 1),
(10, 'Makamba', -4.13000000, 29.80000000, 1),
(11, 'Muramvya', -3.27000000, 29.61000000, 1),
(12, 'Muyinga', -2.85000000, 30.34000000, 1),
(13, 'Mwaro', -3.50000000, 29.65000000, 1),
(14, 'Ngozi', -2.90780000, 29.83060000, 1),
(15, 'Rumonge', -3.97000000, 29.44000000, 1),
(16, 'Rutana', -3.92000000, 30.00000000, 1),
(17, 'Ruyigi', -3.48000000, 30.25000000, 1),
(18, 'Mairie de Bujumbura', -3.37310000, 29.36440000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `qr_confirmations`
--

DROP TABLE IF EXISTS `qr_confirmations`;
CREATE TABLE IF NOT EXISTS `qr_confirmations` (
  `id_qr` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `est_utilise` tinyint(1) DEFAULT '0',
  `date_utilisation` timestamp NULL DEFAULT NULL,
  `latitude_scan` decimal(10,8) DEFAULT NULL,
  `longitude_scan` decimal(11,8) DEFAULT NULL,
  `id_transporteur` int DEFAULT NULL,
  `photo_livraison_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_qr`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `idx_qr_commande` (`id_commande`),
  KEY `idx_qr_transporteur` (`id_transporteur`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `qr_confirmations`
--

INSERT INTO `qr_confirmations` (`id_qr`, `id_commande`, `token`, `token_hash`, `qr_image_url`, `date_expiration`, `est_utilise`, `date_utilisation`, `latitude_scan`, `longitude_scan`, `id_transporteur`, `photo_livraison_url`, `date_creation`) VALUES
(9, 3, 'du7A856rbkPp42fGVhFcUsIgKmvtwNEJ', '1df2b898d76619f7e61384c0a13591b506ab3ee5d05abbdbcd4686b786487493', 'uploads/qr_codes/qr_3_20260423161823.png', '2026-04-30 14:18:24', 0, NULL, NULL, NULL, NULL, NULL, '2026-04-23 14:18:24'),
(10, 4, '4xbey1mfDzNnhMOd9qjolra5uAYLwXQI', 'ccea354167e6dce9e3f4f6114286eb6845fe183d7131fad48c36cd246c5c8fbc', 'uploads/qr_codes/qr_4_20260423162541.png', '2026-04-30 14:25:42', 0, NULL, NULL, NULL, NULL, NULL, '2026-04-23 14:25:42');

-- --------------------------------------------------------

--
-- Structure de la table `quartiers`
--

DROP TABLE IF EXISTS `quartiers`;
CREATE TABLE IF NOT EXISTS `quartiers` (
  `id_quartier` int NOT NULL AUTO_INCREMENT,
  `id_commune` int NOT NULL,
  `quartier_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_quartier`),
  KEY `idx_quartiers_commune` (`id_commune`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `retours_remboursements`
--

DROP TABLE IF EXISTS `retours_remboursements`;
CREATE TABLE IF NOT EXISTS `retours_remboursements` (
  `id_retour` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_article` int DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `type` enum('retour_produit','remboursement_partiel','remboursement_total') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motif` enum('produit_defectueux','non_conforme','erreur_livraison','changement_avis','produit_endommage','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photos_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `montant_demande` decimal(12,2) NOT NULL,
  `montant_approuve` decimal(12,2) DEFAULT NULL,
  `statut` enum('demande','en_cours','approuve','refuse','rembourse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'demande',
  `traite_par` int DEFAULT NULL,
  `motif_refus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `methode_remboursement` enum('mobile_money','virement') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'mobile_money',
  `date_traitement` timestamp NULL DEFAULT NULL,
  `date_remboursement` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_retour`),
  KEY `idx_retours_commande` (`id_commande`),
  KEY `idx_retours_statut` (`statut`)
) ;

--
-- Déchargement des données de la table `retours_remboursements`
--

INSERT INTO `retours_remboursements` (`id_retour`, `id_commande`, `id_article`, `id_utilisateur`, `type`, `motif`, `description`, `photos_urls`, `montant_demande`, `montant_approuve`, `statut`, `traite_par`, `motif_refus`, `methode_remboursement`, `date_traitement`, `date_remboursement`, `date_creation`) VALUES
(3, 4, 4, 2, 'retour_produit', 'changement_avis', 'Je souhaite retourner le T-Shirt car la taille ne correspond pas. Je souhaite un échange ou un remboursement.', '[\"/uploads/retours/t-shirt_taille.jpg\"]', 25000.00, NULL, 'en_cours', 1, NULL, 'virement', '2026-04-23 14:00:00', NULL, '2026-04-23 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `IdSetting` int NOT NULL AUTO_INCREMENT,
  `KeyValue` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `TitlePage` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `IsFile` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdSetting`),
  UNIQUE KEY `KeyValue` (`KeyValue`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`IdSetting`, `KeyValue`, `TitlePage`, `Value`, `IsFile`) VALUES
(1, 'site_name', 'Nom du site', 'ABEMARKET', 0),
(2, 'site_logo', 'Logo du site', '202605131329276a047cb76c826.png', 1),
(3, 'site_favicon', 'Favicon du site', '202605131329276a047cb76ed72.png', 1),
(4, 'site_email', 'Email contact', 'abemarket@gmail.com', 0),
(5, 'site_phone', 'Téléphone contact', '+257 68 86 39 45', 0),
(6, 'site_address', 'Adresse du siège', 'Rohero 1, Avenue Pierre Ndendandumwe Central Building No. 225 a Bujumbura,Republique du Burundi', 0),
(7, 'site_country', 'Pays du site', 'Burundi', 0),
(8, 'password_email16caractere', 'password_email16caractere', 'pmhkniuvlqnwyblm', 0),
(9, 'site_youtube', 'site_youtube', 'http://youtube.com', 0),
(10, 'site_instagram', 'site_instagram', 'http://instagram.com', 0),
(11, 'site_facebook', 'site_facebook', 'http://facebook.com', 0),
(12, 'site_linkedln', 'site_linkedln', 'http://linkedln.com', 0),
(13, 'longitude', 'longitude pour ', '29.3719', 0),
(14, 'latitude', 'latutide', '-3.3835', 0),
(15, 'popup_image', 'Général', 'uploads/popup/202605140921316a05941b1e271.jpeg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `soldes_vendeurs`
--

DROP TABLE IF EXISTS `soldes_vendeurs`;
CREATE TABLE IF NOT EXISTS `soldes_vendeurs` (
  `id_solde` int NOT NULL AUTO_INCREMENT,
  `id_vendeur` int NOT NULL,
  `solde_disponible` decimal(15,2) DEFAULT '0.00',
  `solde_en_attente` decimal(15,2) DEFAULT '0.00',
  `total_gagne` decimal(15,2) DEFAULT '0.00',
  `total_retire` decimal(15,2) DEFAULT '0.00',
  `dernier_paiement` timestamp NULL DEFAULT NULL,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_solde`),
  UNIQUE KEY `id_vendeur` (`id_vendeur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `soldes_vendeurs`
--

INSERT INTO `soldes_vendeurs` (`id_solde`, `id_vendeur`, `solde_disponible`, `solde_en_attente`, `total_gagne`, `total_retire`, `dernier_paiement`, `date_modification`) VALUES
(5, 5, 0.00, 0.00, 0.00, 0.00, NULL, '2026-05-18 23:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `suivi_gps`
--

DROP TABLE IF EXISTS `suivi_gps`;
CREATE TABLE IF NOT EXISTS `suivi_gps` (
  `id_suivi` bigint NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_transporteur` int NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `vitesse_kmh` decimal(6,2) DEFAULT NULL,
  `timestamp_gps` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_suivi`),
  KEY `idx_suivi_commande` (`id_commande`),
  KEY `idx_suivi_transporteur` (`id_transporteur`),
  KEY `idx_suivi_timestamp` (`timestamp_gps`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tentatives_connexion`
--

DROP TABLE IF EXISTS `tentatives_connexion`;
CREATE TABLE IF NOT EXISTS `tentatives_connexion` (
  `id_tentative` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `email_tente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reussie` tinyint(1) DEFAULT '0',
  `motif_echec` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_tentative` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tentative`),
  KEY `idx_tentatives_ip` (`adresse_ip`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tentatives_connexion`
--

INSERT INTO `tentatives_connexion` (`id_tentative`, `id_utilisateur`, `email_tente`, `adresse_ip`, `reussie`, `motif_echec`, `date_tentative`) VALUES
(4, 1, 'admin@abemarket.com', '::1', 0, 'Déconnexion volontaire', '2026-04-13 19:17:10'),
(5, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-13 19:17:22'),
(6, 1, 'admin@abemarket.com', '::1', 0, 'Déconnexion volontaire', '2026-04-13 19:39:06'),
(7, 1, 'admin@abemarket.com', '::1', 0, 'Mot de passe incorrect', '2026-04-13 19:39:12'),
(8, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-13 19:39:16'),
(9, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-04-14 07:03:28'),
(10, 1, 'admin@abemarket.com', '::1', 0, 'Déconnexion volontaire', '2026-04-14 07:51:24'),
(11, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-14 07:51:26'),
(12, 1, 'admin@abemarket.com', '::1', 0, 'Déconnexion volontaire', '2026-04-14 09:24:42'),
(13, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-14 09:26:20'),
(14, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-15 17:00:08'),
(15, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-16 14:04:36'),
(16, NULL, 'admin@gmail.com', '::1', 0, 'Email non trouvé', '2026-04-22 09:45:29'),
(17, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-04-22 09:45:38'),
(18, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-04-23 06:44:44'),
(19, NULL, 'admin@magarameza.com', '::1', 0, 'Email non trouvé', '2026-05-11 15:21:23'),
(20, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-05-11 15:21:43'),
(21, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-12 15:58:37'),
(22, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-13 11:28:48'),
(23, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-14 07:04:53'),
(24, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-14 10:12:25'),
(25, 1, 'admin@abemarket.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 11:11:31'),
(26, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 11:12:12'),
(27, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 13:40:42'),
(28, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 14:04:12'),
(29, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 14:04:21'),
(30, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 14:04:26'),
(31, NULL, 'admin@magarameza.com', '127.0.0.1', 0, 'Email non trouvé', '2026-05-14 14:04:38'),
(32, NULL, 'admin@gmail.com', '127.0.0.1', 0, 'Email non trouvé', '2026-05-14 14:08:17'),
(33, NULL, 'admin@gmail.com', '127.0.0.1', 0, 'Email non trouvé', '2026-05-14 14:08:27'),
(34, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Mot de passe incorrect', '2026-05-14 14:08:42'),
(35, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 14:08:50'),
(36, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 14:09:04'),
(37, NULL, 'admin@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 14:18:01'),
(38, NULL, 'admin@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 14:18:33'),
(39, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-14 14:50:32'),
(40, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 14:50:40'),
(41, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-14 15:07:04'),
(42, 1, 'admin@abemarket.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-14 16:48:44'),
(43, 1, 'admin@abemarket.com', '127.0.0.1', 1, NULL, '2026-05-16 21:21:51'),
(44, 1, 'admin@abemarket.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-16 22:21:44'),
(45, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-18 11:53:37'),
(46, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-18 11:55:53'),
(47, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-18 11:56:00'),
(48, 1, 'admin@abemarket.com', '::1', 1, NULL, '2026-05-18 17:29:50'),
(49, 1, 'admin@abemarket.com', '::1', 0, 'Déconnexion volontaire', '2026-05-18 19:15:07'),
(50, NULL, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-18 19:44:09'),
(51, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-18 21:59:54'),
(52, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-19 14:31:49'),
(53, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-19 14:34:48'),
(54, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-19 14:34:56'),
(55, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 0, 'Déconnexion volontaire', '2026-05-19 14:35:27'),
(56, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-19 14:35:39');

-- --------------------------------------------------------

--
-- Structure de la table `transactions_paiement`
--

DROP TABLE IF EXISTS `transactions_paiement`;
CREATE TABLE IF NOT EXISTS `transactions_paiement` (
  `id_transaction` int NOT NULL AUTO_INCREMENT,
  `reference_interne` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_commande` int DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `id_mode_payement` int NOT NULL,
  `type_transaction` enum('paiement','remboursement','virement_vendeur') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `frais` decimal(10,2) DEFAULT '0.00',
  `montant_net` decimal(15,2) NOT NULL,
  `devise` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'BIF',
  `telephone_payeur` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_payeur` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_operateur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('initie','en_attente','confirme','echoue','annule','rembourse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'initie',
  `message_statut` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_confirmation` timestamp NULL DEFAULT NULL,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaction`),
  UNIQUE KEY `reference_interne` (`reference_interne`),
  KEY `idx_transactions_commande` (`id_commande`),
  KEY `idx_transactions_utilisateur` (`id_utilisateur`),
  KEY `idx_transactions_statut` (`statut`),
  KEY `id_mode_payement` (`id_mode_payement`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions_paiement`
--

INSERT INTO `transactions_paiement` (`id_transaction`, `reference_interne`, `id_commande`, `id_utilisateur`, `id_mode_payement`, `type_transaction`, `montant`, `frais`, `montant_net`, `devise`, `telephone_payeur`, `nom_payeur`, `reference_operateur`, `statut`, `message_statut`, `date_confirmation`, `adresse_ip`, `date_creation`) VALUES
(4, 'TRX-20260423-001', 3, 2, 1, 'paiement', 41490.00, 622.35, 40867.65, 'BIF', '+25762345678', 'Marie Client', 'BANCOBU_REF_789456123', 'confirme', 'Paiement confirmé par Bancobu', '2026-04-23 08:15:00', '192.168.1.100', '2026-04-23 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `transporteurs`
--

DROP TABLE IF EXISTS `transporteurs`;
CREATE TABLE IF NOT EXISTS `transporteurs` (
  `id_transporteur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `type` enum('interne','partenaire') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'interne',
  `nom` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_vehicule` enum('moto','voiture','velo','camionnette','pied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'moto',
  `plaque` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_actuelle` decimal(10,8) DEFAULT NULL,
  `longitude_actuelle` decimal(11,8) DEFAULT NULL,
  `derniere_position` timestamp NULL DEFAULT NULL,
  `est_disponible` tinyint(1) DEFAULT '1',
  `note_moyenne` decimal(3,2) DEFAULT '0.00',
  `livraisons_totales` int DEFAULT '0',
  `livraisons_reussies` int DEFAULT '0',
  `statut` enum('actif','inactif','suspendu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transporteur`),
  KEY `idx_transporteurs_disponible` (`est_disponible`),
  KEY `idx_transporteurs_statut` (`statut`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transporteurs`
--

INSERT INTO `transporteurs` (`id_transporteur`, `id_utilisateur`, `type`, `nom`, `telephone`, `whatsapp`, `photo_url`, `type_vehicule`, `plaque`, `latitude_actuelle`, `longitude_actuelle`, `derniere_position`, `est_disponible`, `note_moyenne`, `livraisons_totales`, `livraisons_reussies`, `statut`, `date_creation`) VALUES
(1, NULL, 'interne', 'Paul Livreur', '+25764567890', '+25764567890', 'uploads/transporteurs/transporteur_20260423_174103_69ea59afaf06a.jpeg', 'moto', 'AB123CD', NULL, NULL, NULL, 1, 4.80, 120, 115, 'actif', '2026-04-13 20:05:49');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verifie` tinyint(1) DEFAULT '0',
  `telephone_verifie` tinyint(1) DEFAULT '0',
  `deux_facteurs_actif` tinyint(1) DEFAULT '0',
  `methode_2fa` enum('sms','email') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  `est_banni` tinyint(1) DEFAULT '0',
  `motif_bannissement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `derniere_connexion` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token_reset` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reset_expiration` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_utilisateurs_telephone` (`telephone`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `mot_de_passe`, `prenom`, `nom`, `telephone`, `avatar_url`, `email_verifie`, `telephone_verifie`, `deux_facteurs_actif`, `methode_2fa`, `est_actif`, `est_banni`, `motif_bannissement`, `derniere_connexion`, `date_creation`, `date_modification`, `token_reset`, `date_reset_expiration`) VALUES
(1, 'admin@abemarket.com', 'c93ccd78b2076528346216b3b2f701e6', 'Administateur', 'Admin', '+25761234567', 'attachments/Users/2026041322362469dd6fe800b3f.jpeg', 1, 1, 0, NULL, 1, 0, NULL, '2026-05-18 17:29:50', '2026-04-13 14:21:16', '2026-05-18 19:29:50', NULL, NULL),
(2, 'client@example.com', '3677b23baa08f74c28aba07f0cb6554e', 'Marie', 'Client', '+25762345678', NULL, 1, 1, 0, NULL, 1, 0, NULL, NULL, '2026-04-13 20:08:30', '2026-04-13 20:08:30', NULL, NULL),
(3, 'vendeur@example.com', '306ff7c53c930e6242aa8b2a011828d3', 'Pierre', 'Vendeur', '+25763456789', 'attachments/Users/2026041322510569dd7359577cf.png', 1, 1, 0, NULL, 1, 0, NULL, NULL, '2026-04-13 20:08:30', '2026-04-13 21:23:50', NULL, NULL),
(4, 'livreur@example.com', '3e9fba8b41fe6bc7a490dcb8ae378598', 'Paul', 'Livreur', '+25764567890', '/uploads/avatars/livreur.jpg', 1, 1, 0, NULL, 1, 0, NULL, NULL, '2026-04-13 20:08:30', '2026-04-13 22:37:13', NULL, NULL),
(6, 'finance@example.com', 'b9c9b331a8a5007cb2b766c6cd293372', 'Luc', 'Finance', '+25766789012', NULL, 1, 1, 0, NULL, 1, 0, NULL, NULL, '2026-04-13 20:08:30', '2026-04-13 20:08:30', NULL, NULL),
(7, 'support@example.com', '5882c44da74f923baabe8476c6e8af37', 'Emmaent', 'Support', '+25767890123', NULL, 1, 1, 0, NULL, 1, 0, NULL, NULL, '2026-04-13 20:08:30', '2026-04-13 20:35:57', NULL, NULL),
(18, 'dushimepaul51@gmail.com', 'e884d3e952eef6fdc5712a7cc82b206b', 'paul', 'dushime', '784594593', 'attachments/Users/202605182359126a0ba7d06ddca.jpg', 1, 0, 0, NULL, 1, 0, NULL, '2026-05-19 14:35:39', '2026-05-18 20:56:52', '2026-05-19 16:35:39', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_profils`
--

DROP TABLE IF EXISTS `utilisateur_profils`;
CREATE TABLE IF NOT EXISTS `utilisateur_profils` (
  `id_utilisateur` int NOT NULL,
  `id_profil` int NOT NULL,
  `attribue_par` int DEFAULT NULL,
  `date_attribution` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`,`id_profil`),
  KEY `id_profil` (`id_profil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur_profils`
--

INSERT INTO `utilisateur_profils` (`id_utilisateur`, `id_profil`, `attribue_par`, `date_attribution`) VALUES
(1, 1, 1, '2026-04-14 07:51:10'),
(2, 5, 1, '2026-04-13 20:16:28'),
(3, 4, 1, '2026-04-13 21:23:50'),
(4, 4, 1, '2026-04-13 20:16:28'),
(4, 6, 1, '2026-04-13 20:16:28'),
(6, 7, 1, '2026-04-13 20:16:28'),
(7, 4, 1, '2026-04-13 21:24:35'),
(18, 4, 18, '2026-05-18 21:18:41'),
(18, 5, 18, '2026-05-18 20:56:52');

-- --------------------------------------------------------

--
-- Structure de la table `variantes_produit`
--

DROP TABLE IF EXISTS `variantes_produit`;
CREATE TABLE IF NOT EXISTS `variantes_produit` (
  `id_variante` int NOT NULL AUTO_INCREMENT,
  `id_produit` int NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributs_variante` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `prix` decimal(12,2) DEFAULT NULL,
  `quantite_actuelle` int DEFAULT '0',
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_variante`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_variantes_produit` (`id_produit`)
) ;

--
-- Déchargement des données de la table `variantes_produit`
--

INSERT INTO `variantes_produit` (`id_variante`, `id_produit`, `sku`, `attributs_variante`, `prix`, `quantite_actuelle`, `est_actif`, `date_creation`) VALUES
(1, 3, 'SKU003-S', '{\"taille\": \"S\", \"couleur\": \"Blanc\"}', 25000.00, 15, 1, '2026-04-13 20:05:49'),
(2, 3, 'SKU003-M', '{\"taille\": \"M\", \"couleur\": \"Blanc\"}', 25000.00, 20, 1, '2026-04-13 20:05:49'),
(3, 3, 'SKU003-L', '{\"taille\": \"L\", \"couleur\": \"Blanc\"}', 25000.00, 13, 1, '2026-04-13 20:05:49'),
(4, 4, 'SKU004-39', '{\"taille\":\"12\",\"couleur\":\"Noir\"}', 45000.00, 100, 1, '2026-04-13 20:05:49'),
(5, 4, 'SKU004-40', '{\"taille\":\"40\",\"couleur\":\"Noir\"}', 45000.00, 15, 1, '2026-04-13 20:05:49');

-- --------------------------------------------------------

--
-- Structure de la table `vendeurs`
--

DROP TABLE IF EXISTS `vendeurs`;
CREATE TABLE IF NOT EXISTS `vendeurs` (
  `id_vendeur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `nom_boutique` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug_boutique` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_boutique` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type_vendeur` enum('particulier','entreprise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'particulier',
  `nom_entreprise` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_nif` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_rc` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_province` int DEFAULT NULL,
  `id_commune` int DEFAULT NULL,
  `id_quartier` int DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taux_commission` decimal(5,2) DEFAULT '10.00',
  `delai_paiement_jours` int DEFAULT '7',
  `note_moyenne` decimal(3,2) DEFAULT '0.00',
  `nombre_avis` int DEFAULT '0',
  `total_commandes` int DEFAULT '0',
  `est_approuve` tinyint(1) DEFAULT '0',
  `date_approbation` timestamp NULL DEFAULT NULL,
  `approuve_par` int DEFAULT NULL,
  `statut` enum('en_attente','actif','suspendu','banni') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_vendeur`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  UNIQUE KEY `slug_boutique` (`slug_boutique`),
  KEY `idx_vendeurs_statut` (`statut`),
  KEY `id_province` (`id_province`),
  KEY `id_commune` (`id_commune`),
  KEY `id_quartier` (`id_quartier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vendeurs`
--

INSERT INTO `vendeurs` (`id_vendeur`, `id_utilisateur`, `nom_boutique`, `slug_boutique`, `logo_boutique`, `description`, `type_vendeur`, `nom_entreprise`, `numero_nif`, `numero_rc`, `id_province`, `id_commune`, `id_quartier`, `latitude`, `longitude`, `telephone`, `whatsapp`, `taux_commission`, `delai_paiement_jours`, `note_moyenne`, `nombre_avis`, `total_commandes`, `est_approuve`, `date_approbation`, `approuve_par`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 1, 'Boutique Admin', 'boutique-admin', NULL, NULL, 'particulier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 7, 0.00, 0, 0, 1, NULL, NULL, 'actif', '2026-04-23 10:55:57', '2026-04-23 10:55:57'),
(5, 18, 'BOBO', 'bobo', 'attachments/Users/202605182357166a0ba75c172cb.jpg', '', 'particulier', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, '784594593', '784594593', 10.00, 7, 0.00, 0, 0, 0, NULL, NULL, 'en_attente', '2026-05-18 21:57:16', '2026-05-18 23:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
  `id_zone` int NOT NULL AUTO_INCREMENT,
  `id_commune` int NOT NULL,
  `zone_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_zone`),
  KEY `idx_zones_commune` (`id_commune`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `zones_livraison`
--

DROP TABLE IF EXISTS `zones_livraison`;
CREATE TABLE IF NOT EXISTS `zones_livraison` (
  `id_zone_liv` int NOT NULL AUTO_INCREMENT,
  `nom_zone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_province` int DEFAULT NULL,
  `ids_communes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ids_quartiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cout_base` decimal(8,2) NOT NULL,
  `seuil_livraison_gratuite` decimal(10,2) DEFAULT NULL,
  `cout_par_kg` decimal(6,2) DEFAULT NULL,
  `delai_min_jours` int DEFAULT NULL,
  `delai_max_jours` int DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_zone_liv`),
  KEY `idx_zone_province` (`id_province`)
) ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `adresses_ibfk_2` FOREIGN KEY (`id_province`) REFERENCES `provinces` (`id_province`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_3` FOREIGN KEY (`id_commune`) REFERENCES `communes` (`id_commune`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_4` FOREIGN KEY (`id_quartier`) REFERENCES `quartiers` (`id_quartier`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_5` FOREIGN KEY (`id_zone`) REFERENCES `zones` (`id_zone`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_6` FOREIGN KEY (`id_colline`) REFERENCES `collines` (`id_colline`) ON DELETE SET NULL;

--
-- Contraintes pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  ADD CONSTRAINT `approvisionnements_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `approvisionnements_ibfk_2` FOREIGN KEY (`id_variante`) REFERENCES `variantes_produit` (`id_variante`) ON DELETE SET NULL,
  ADD CONSTRAINT `approvisionnements_ibfk_3` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `articles_commande`
--
ALTER TABLE `articles_commande`
  ADD CONSTRAINT `articles_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_commande_ibfk_3` FOREIGN KEY (`id_variante`) REFERENCES `variantes_produit` (`id_variante`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_commande_ibfk_4` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `avis_produits`
--
ALTER TABLE `avis_produits`
  ADD CONSTRAINT `avis_produits_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_produits_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `categories` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `codes_otp`
--
ALTER TABLE `codes_otp`
  ADD CONSTRAINT `codes_otp_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `collines`
--
ALTER TABLE `collines`
  ADD CONSTRAINT `collines_ibfk_1` FOREIGN KEY (`id_zone`) REFERENCES `zones` (`id_zone`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`id_adresse_livraison`) REFERENCES `adresses` (`id_adresse`) ON DELETE SET NULL,
  ADD CONSTRAINT `commandes_ibfk_3` FOREIGN KEY (`id_mode_payement`) REFERENCES `mode_payement` (`id_mode_payement`),
  ADD CONSTRAINT `commandes_ibfk_4` FOREIGN KEY (`id_point_relais`) REFERENCES `points_relais` (`id_point`) ON DELETE SET NULL,
  ADD CONSTRAINT `commandes_ibfk_5` FOREIGN KEY (`id_transporteur`) REFERENCES `transporteurs` (`id_transporteur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `communes`
--
ALTER TABLE `communes`
  ADD CONSTRAINT `communes_ibfk_1` FOREIGN KEY (`id_province`) REFERENCES `provinces` (`id_province`) ON DELETE CASCADE;

--
-- Contraintes pour la table `config_paiement_vendeur`
--
ALTER TABLE `config_paiement_vendeur`
  ADD CONSTRAINT `config_paiement_vendeur_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents_vendeur`
--
ALTER TABLE `documents_vendeur`
  ADD CONSTRAINT `documents_vendeur_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations_vendeurs`
--
ALTER TABLE `evaluations_vendeurs`
  ADD CONSTRAINT `evaluations_vendeurs_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_vendeurs_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique_statut_commande`
--
ALTER TABLE `historique_statut_commande`
  ADD CONSTRAINT `historique_statut_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `images_produit`
--
ALTER TABLE `images_produit`
  ADD CONSTRAINT `images_produit_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `images_produit_ibfk_2` FOREIGN KEY (`id_variante`) REFERENCES `variantes_produit` (`id_variante`) ON DELETE CASCADE;

--
-- Contraintes pour la table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  ADD CONSTRAINT `liste_souhaits_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `liste_souhaits_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `litiges_commandes`
--
ALTER TABLE `litiges_commandes`
  ADD CONSTRAINT `litiges_commandes_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements_vendeurs`
--
ALTER TABLE `paiements_vendeurs`
  ADD CONSTRAINT `paiements_vendeurs_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiements_vendeurs_ibfk_2` FOREIGN KEY (`id_config_paiement`) REFERENCES `config_paiement_vendeur` (`id_config`) ON DELETE SET NULL;

--
-- Contraintes pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD CONSTRAINT `paniers_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `paniers_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `points_relais`
--
ALTER TABLE `points_relais`
  ADD CONSTRAINT `points_relais_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `communes` (`id_commune`) ON DELETE SET NULL,
  ADD CONSTRAINT `points_relais_ibfk_2` FOREIGN KEY (`id_quartier`) REFERENCES `quartiers` (`id_quartier`) ON DELETE SET NULL;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE SET NULL,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE SET NULL;

--
-- Contraintes pour la table `qr_confirmations`
--
ALTER TABLE `qr_confirmations`
  ADD CONSTRAINT `qr_confirmations_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `qr_confirmations_ibfk_2` FOREIGN KEY (`id_transporteur`) REFERENCES `transporteurs` (`id_transporteur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `quartiers`
--
ALTER TABLE `quartiers`
  ADD CONSTRAINT `quartiers_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `communes` (`id_commune`) ON DELETE CASCADE;

--
-- Contraintes pour la table `retours_remboursements`
--
ALTER TABLE `retours_remboursements`
  ADD CONSTRAINT `retours_remboursements_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `soldes_vendeurs`
--
ALTER TABLE `soldes_vendeurs`
  ADD CONSTRAINT `soldes_vendeurs_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeurs` (`id_vendeur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `suivi_gps`
--
ALTER TABLE `suivi_gps`
  ADD CONSTRAINT `suivi_gps_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `suivi_gps_ibfk_2` FOREIGN KEY (`id_transporteur`) REFERENCES `transporteurs` (`id_transporteur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tentatives_connexion`
--
ALTER TABLE `tentatives_connexion`
  ADD CONSTRAINT `tentatives_connexion_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `transactions_paiement`
--
ALTER TABLE `transactions_paiement`
  ADD CONSTRAINT `transactions_paiement_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_paiement_ibfk_2` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_paiement_ibfk_3` FOREIGN KEY (`id_mode_payement`) REFERENCES `mode_payement` (`id_mode_payement`);

--
-- Contraintes pour la table `transporteurs`
--
ALTER TABLE `transporteurs`
  ADD CONSTRAINT `transporteurs_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `utilisateur_profils`
--
ALTER TABLE `utilisateur_profils`
  ADD CONSTRAINT `utilisateur_profils_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilisateur_profils_ibfk_2` FOREIGN KEY (`id_profil`) REFERENCES `profils` (`id_profil`) ON DELETE CASCADE;

--
-- Contraintes pour la table `variantes_produit`
--
ALTER TABLE `variantes_produit`
  ADD CONSTRAINT `variantes_produit_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  ADD CONSTRAINT `vendeurs_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendeurs_ibfk_2` FOREIGN KEY (`id_province`) REFERENCES `provinces` (`id_province`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendeurs_ibfk_3` FOREIGN KEY (`id_commune`) REFERENCES `communes` (`id_commune`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendeurs_ibfk_4` FOREIGN KEY (`id_quartier`) REFERENCES `quartiers` (`id_quartier`) ON DELETE SET NULL;

--
-- Contraintes pour la table `zones`
--
ALTER TABLE `zones`
  ADD CONSTRAINT `zones_ibfk_1` FOREIGN KEY (`id_commune`) REFERENCES `communes` (`id_commune`) ON DELETE CASCADE;

--
-- Contraintes pour la table `zones_livraison`
--
ALTER TABLE `zones_livraison`
  ADD CONSTRAINT `zones_livraison_ibfk_1` FOREIGN KEY (`id_province`) REFERENCES `provinces` (`id_province`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
