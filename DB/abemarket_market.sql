-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 07 sep. 2026 à 17:44
-- Version du serveur : 10.11.17-MariaDB-cll-lve
-- Version de PHP : 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `abemarket_market`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id_adresse` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type_adresse` enum('domicile','travail','autre') DEFAULT 'domicile',
  `est_par_defaut` tinyint(1) DEFAULT 0,
  `nom_complet` varchar(200) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `id_province` int(11) DEFAULT NULL,
  `id_commune` int(11) DEFAULT NULL,
  `id_quartier` int(11) DEFAULT NULL,
  `id_zone` int(11) DEFAULT NULL,
  `id_colline` int(11) DEFAULT NULL,
  `adresse_ligne` varchar(255) DEFAULT NULL,
  `point_repere` varchar(500) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `instructions_livraison` text DEFAULT NULL,
  `photo_repere_url` varchar(500) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnements`
--

CREATE TABLE `approvisionnements` (
  `id_appro` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `id_vendeur` int(11) NOT NULL,
  `quantite_initiale` int(11) NOT NULL,
  `quantite_recue` int(11) NOT NULL,
  `quantite_apres` int(11) NOT NULL,
  `prix_achat_unitaire` decimal(12,2) DEFAULT NULL,
  `cout_total` decimal(12,2) DEFAULT NULL,
  `fournisseur` varchar(200) DEFAULT NULL,
  `reference_bon` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `enregistre_par` int(11) DEFAULT NULL,
  `date_appro` date NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles_commande`
--

CREATE TABLE `articles_commande` (
  `id_article` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `id_vendeur` int(11) NOT NULL,
  `nom_produit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_produit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_produit_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attributs_variante` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `prix_unitaire` decimal(12,2) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_total` decimal(12,2) NOT NULL,
  `taux_commission` decimal(5,2) DEFAULT NULL,
  `montant_commission` decimal(12,2) DEFAULT NULL,
  `revenus_vendeur` decimal(12,2) DEFAULT NULL,
  `statut_article` enum('en_attente','prepare','expedie','livre','retourne','annule') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `est_retourne` tinyint(1) DEFAULT 0,
  `motif_retour` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_demande_retour` timestamp NULL DEFAULT NULL,
  `avis_laisse` tinyint(1) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis_produits`
--

CREATE TABLE `avis_produits` (
  `id_avis` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `note` tinyint(1) NOT NULL,
  `titre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urls_medias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `achat_verifie` tinyint(1) DEFAULT 0,
  `est_approuve` tinyint(1) DEFAULT 0,
  `reponse_vendeur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reponse_vendeur` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `avis_produits`
--

INSERT INTO `avis_produits` (`id_avis`, `id_produit`, `id_utilisateur`, `id_commande`, `note`, `titre`, `commentaire`, `urls_medias`, `achat_verifie`, `est_approuve`, `reponse_vendeur`, `date_reponse_vendeur`, `date_creation`) VALUES
(3, 4, 1, NULL, 5, 'Excellent produit', 'Ces chaussures sont très confortables et de bonne qualité.', NULL, 1, 1, 'hjh', '2026-04-22 13:39:11', '2026-04-22 15:06:49');

-- --------------------------------------------------------

--
-- Structure de la table `banners`
--

CREATE TABLE `banners` (
  `id_banner` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `position` enum('home_main','home_top_right','home_bottom','category','product') DEFAULT 'home_main',
  `ordre_affichage` int(11) DEFAULT 0,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_debut` timestamp NULL DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banners`
--

INSERT INTO `banners` (`id_banner`, `title`, `subtitle`, `image`, `link`, `position`, `ordre_affichage`, `est_actif`, `date_debut`, `date_fin`, `date_creation`) VALUES
(3, 'dfdgs', 'sdgs', 'uploads/banners/banner_20260517_001114_6a0907a257c62.png', 'http://localhost/abemarket/banners/add', 'home_main', 0, 1, NULL, NULL, '2026-05-16 21:23:00');

-- --------------------------------------------------------

--
-- Structure de la table `blacklist_ips`
--

CREATE TABLE `blacklist_ips` (
  `id_blacklist` int(11) NOT NULL,
  `adresse_ip` varchar(45) NOT NULL,
  `raison` varchar(255) DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `cree_par` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blacklist_ips`
--

INSERT INTO `blacklist_ips` (`id_blacklist`, `adresse_ip`, `raison`, `date_fin`, `cree_par`, `date_creation`) VALUES
(5, '::1', 'Tentatives de connexion suspectes', NULL, 1, '2026-04-23 12:17:44');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `nom_categorie` varchar(150) NOT NULL,
  `slug_categorie` varchar(150) NOT NULL,
  `niveau` int(11) DEFAULT 0,
  `icone` varchar(100) DEFAULT NULL,
  `url_image` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `ordre_affichage` int(11) DEFAULT 0,
  `nombre_produits` int(11) DEFAULT 0,
  `taux_commission_specifique` decimal(5,2) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `codes_otp` (
  `id_otp` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `type_otp` enum('connexion','verification_telephone','verification_email','reinitialisation_mdp') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tentatives` int(11) DEFAULT 0,
  `date_expiration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utilise` tinyint(1) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `collines` (
  `id_colline` int(11) NOT NULL,
  `id_zone` int(11) NOT NULL,
  `colline_name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `collines`
--

INSERT INTO `collines` (`id_colline`, `id_zone`, `colline_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 1, 'Bisinde', -3.37900000, 29.36100000, 1),
(2, 1, 'Bugarama', -3.37800000, 29.36200000, 1),
(3, 1, 'Karambi', -3.37700000, 29.36300000, 1),
(4, 1, 'Kazimya', -3.37600000, 29.36400000, 1),
(5, 1, 'Nyabigugo', -3.37500000, 29.36500000, 1),
(6, 1, 'Rutimbura', -3.37400000, 29.36600000, 1),
(7, 1, 'Rutonganyikwa', -3.37300000, 29.36700000, 1),
(8, 2, 'Biyorwa', -3.37200000, 29.36800000, 1),
(9, 2, 'Burenza', -3.37100000, 29.36900000, 1),
(10, 2, 'Masazi', -3.37000000, 29.37000000, 1),
(11, 2, 'Mihama', -3.36900000, 29.37100000, 1),
(12, 2, 'Mpame', -3.36800000, 29.37200000, 1),
(13, 2, 'Nyamugari', -3.36700000, 29.37300000, 1),
(14, 2, 'Nyarubabi', -3.36600000, 29.37400000, 1),
(15, 2, 'Rugata', -3.36500000, 29.37500000, 1),
(16, 2, 'Taba', -3.36400000, 29.37600000, 1),
(17, 3, 'Bigera', -3.36300000, 29.37700000, 1),
(18, 3, 'Caragata', -3.36200000, 29.37800000, 1),
(19, 3, 'Gakonko', -3.36100000, 29.37900000, 1),
(20, 3, 'Kivumu', -3.36000000, 29.38000000, 1),
(21, 3, 'Mpungwe', -3.35900000, 29.38100000, 1),
(22, 3, 'Mugege', -3.35800000, 29.38200000, 1),
(23, 3, 'Muhene', -3.35700000, 29.38300000, 1),
(24, 3, 'Musenga', -3.35600000, 29.38400000, 1),
(25, 3, 'Nyangurube', -3.35500000, 29.38500000, 1),
(26, 3, 'Nyankende', -3.35400000, 29.38600000, 1),
(27, 3, 'Rubambagire', -3.35300000, 29.38700000, 1),
(28, 4, 'Bartye', -3.35200000, 29.38800000, 1),
(29, 4, 'Gasasa', -3.35100000, 29.38900000, 1),
(30, 4, 'Gikwiye', -3.35000000, 29.39000000, 1),
(31, 4, 'Gishubi', -3.34900000, 29.39100000, 1),
(32, 4, 'Kanyinya', -3.34800000, 29.39200000, 1),
(33, 4, 'Kirambi', -3.34700000, 29.39300000, 1),
(34, 4, 'Kiyabu', -3.34600000, 29.39400000, 1),
(35, 4, 'Muriza', -3.34500000, 29.39500000, 1),
(36, 4, 'Nyaburondwe', -3.34400000, 29.39600000, 1),
(37, 4, 'Nyamiyaga', -3.34300000, 29.39700000, 1),
(38, 4, 'Nyarurambi', -3.34200000, 29.39800000, 1),
(39, 5, 'Kirangara', -3.34100000, 29.39900000, 1),
(40, 5, 'Kivoga', -3.34000000, 29.40000000, 1),
(41, 5, 'Kizigama', -3.33900000, 29.40100000, 1),
(42, 5, 'Maramvya', -3.33800000, 29.40200000, 1),
(43, 5, 'Nyagashubi', -3.33700000, 29.40300000, 1),
(44, 5, 'Nyange', -3.33600000, 29.40400000, 1),
(45, 5, 'Rugongo', -3.33500000, 29.40500000, 1),
(46, 6, 'Buhorana', -3.33400000, 29.40600000, 1),
(47, 6, 'Kavumu', -3.33300000, 29.40700000, 1),
(48, 6, 'Kinyuku', -3.33200000, 29.40800000, 1),
(49, 6, 'Kobero', -3.33100000, 29.40900000, 1),
(50, 6, 'Ntaruka', -3.33000000, 29.41000000, 1),
(51, 6, 'Rabiro', -3.32900000, 29.41100000, 1),
(52, 6, 'Tangara', -3.32800000, 29.41200000, 1),
(53, 6, 'Wingoma', -3.32700000, 29.41300000, 1),
(54, 7, 'Bucamihigo', -3.32600000, 29.41400000, 1),
(55, 7, 'Butihinda', -3.32500000, 29.41500000, 1),
(56, 7, 'Maramvya', -3.32400000, 29.41600000, 1),
(57, 7, 'Munyinya', -3.32300000, 29.41700000, 1),
(58, 7, 'Ngara', -3.32200000, 29.41800000, 1),
(59, 7, 'Rukira', -3.32100000, 29.41900000, 1),
(60, 7, 'Rushombo', -3.32000000, 29.42000000, 1),
(61, 8, 'Burambira', -3.31900000, 29.42100000, 1),
(62, 8, 'Burenza', -3.31800000, 29.42200000, 1),
(63, 8, 'Gikingo', -3.31700000, 29.42300000, 1),
(64, 8, 'Gisabazuba', -3.31600000, 29.42400000, 1),
(65, 8, 'Migwa', -3.31500000, 29.42500000, 1),
(66, 8, 'Nyarushanga', -3.31400000, 29.42600000, 1),
(67, 9, 'Buvumbi', -3.31300000, 29.42700000, 1),
(68, 9, 'Gahahe', -3.31200000, 29.42800000, 1),
(69, 9, 'Gitega', -3.31100000, 29.42900000, 1),
(70, 9, 'Masaka', -3.31000000, 29.43000000, 1),
(71, 9, 'Nyamihondi', -3.30900000, 29.43100000, 1),
(72, 9, 'Zaga', -3.30800000, 29.43200000, 1),
(73, 10, 'Bwisha', -3.30700000, 29.43300000, 1),
(74, 10, 'Cihonda', -3.30600000, 29.43400000, 1),
(75, 10, 'Gashoho', -3.30500000, 29.43500000, 1),
(76, 10, 'Gishambusha', -3.30400000, 29.43600000, 1),
(77, 10, 'Gitwa', -3.30300000, 29.43700000, 1),
(78, 10, 'Kagari', -3.30200000, 29.43800000, 1),
(79, 10, 'Kinyami', -3.30100000, 29.43900000, 1),
(80, 10, 'Muruta', -3.30000000, 29.44000000, 1),
(81, 10, 'Muyange', -3.29900000, 29.44100000, 1),
(82, 10, 'Nkohwa', -3.29800000, 29.44200000, 1),
(83, 11, 'Bonero', -3.29700000, 29.44300000, 1),
(84, 11, 'Busasa', -3.29600000, 29.44400000, 1),
(85, 11, 'Gisebeyi', -3.29500000, 29.44500000, 1),
(86, 11, 'Muzingi', -3.29400000, 29.44600000, 1),
(87, 11, 'Rugerero', -3.29300000, 29.44700000, 1),
(88, 12, 'Bisiga', -3.29200000, 29.44800000, 1),
(89, 12, 'Gasenyi', -3.29100000, 29.44900000, 1),
(90, 12, 'Giteranyi', -3.29000000, 29.45000000, 1),
(91, 12, 'Karugunda', -3.28900000, 29.45100000, 1),
(92, 12, 'Nonwe', -3.28800000, 29.45200000, 1),
(93, 12, 'Rugese', -3.28700000, 29.45300000, 1),
(94, 12, 'Rukungere', -3.28600000, 29.45400000, 1),
(95, 12, 'Rumandari', -3.28500000, 29.45500000, 1),
(96, 12, 'Shoza', -3.28400000, 29.45600000, 1),
(97, 13, 'Cagizo', -3.28300000, 29.45700000, 1),
(98, 13, 'Gatwenzi', -3.28200000, 29.45800000, 1),
(99, 13, 'Kamaramagambo', -3.28100000, 29.45900000, 1),
(100, 13, 'Kibande', -3.28000000, 29.46000000, 1),
(101, 13, 'Kinonora', -3.27900000, 29.46100000, 1),
(102, 13, 'Maruri', -3.27800000, 29.46200000, 1),
(103, 13, 'Mugongo', -3.27700000, 29.46300000, 1),
(104, 13, 'Murehe', -3.27600000, 29.46400000, 1),
(105, 13, 'Rwamfu', -3.27500000, 29.46500000, 1),
(106, 14, 'Cankuzo', -3.27400000, 29.46600000, 1),
(107, 14, 'Gatungurwe', -3.27300000, 29.46700000, 1),
(108, 14, 'Kabeza', -3.27200000, 29.46800000, 1),
(109, 14, 'Kabuga', -3.27100000, 29.46900000, 1),
(110, 14, 'Kavumu', -3.27000000, 29.47000000, 1),
(111, 14, 'Mugenda', -3.26900000, 29.47100000, 1),
(112, 14, 'Mugongo', -3.26800000, 29.47200000, 1),
(113, 14, 'Mugozi', -3.26700000, 29.47300000, 1),
(114, 14, 'Muhweza', -3.26600000, 29.47400000, 1),
(115, 14, 'Musenyi', -3.26500000, 29.47500000, 1),
(116, 14, 'Muterero', -3.26400000, 29.47600000, 1),
(117, 14, 'Muyaga', -3.26300000, 29.47700000, 1),
(118, 14, 'Nyabisindu', -3.26200000, 29.47800000, 1),
(119, 14, 'Nyakivumu', -3.26100000, 29.47900000, 1),
(120, 14, 'Nyamusenga', -3.26000000, 29.48000000, 1),
(121, 15, 'Gitanga', -3.25900000, 29.48100000, 1),
(122, 15, 'Kibungo', -3.25800000, 29.48200000, 1),
(123, 15, 'Kigaga', -3.25700000, 29.48300000, 1),
(124, 15, 'Mashiga', -3.25600000, 29.48400000, 1),
(125, 15, 'Nyamatongo', -3.25500000, 29.48500000, 1),
(126, 15, 'Rujungu', -3.25400000, 29.48600000, 1),
(127, 15, 'Saswe', -3.25300000, 29.48700000, 1),
(128, 15, 'Shinge', -3.25200000, 29.48800000, 1),
(129, 16, 'Gatunguru', -3.25100000, 29.48900000, 1),
(130, 16, 'Gisenga', -3.25000000, 29.49000000, 1),
(131, 16, 'Humure', -3.24900000, 29.49100000, 1),
(132, 16, 'Kazibaziba', -3.24800000, 29.49200000, 1),
(133, 16, 'Kigamba', -3.24700000, 29.49300000, 1),
(134, 16, 'Kivumu', -3.24600000, 29.49400000, 1),
(135, 16, 'Nyarurambi', -3.24500000, 29.49500000, 1),
(136, 16, 'Rusagara', -3.24400000, 29.49600000, 1),
(137, 16, 'Rwamvura', -3.24300000, 29.49700000, 1),
(138, 17, 'Karago', -3.24200000, 29.49800000, 1),
(139, 17, 'Murehe', -3.24100000, 29.49900000, 1),
(140, 17, 'Nyakerera', -3.24000000, 29.50000000, 1),
(141, 17, 'Nyarutiti', -3.23900000, 29.50100000, 1),
(142, 17, 'Rutoke', -3.23800000, 29.50200000, 1),
(143, 18, 'Budega', -3.23700000, 29.50300000, 1),
(144, 18, 'Bumba', -3.23600000, 29.50400000, 1),
(145, 18, 'Bunyerere', -3.23500000, 29.50500000, 1),
(146, 18, 'Muka', -3.23400000, 29.50600000, 1),
(147, 18, 'Nkoro', -3.23300000, 29.50700000, 1),
(148, 18, 'Nyamwiyanike', -3.23200000, 29.50800000, 1),
(149, 18, 'Rusigabangazi', -3.23100000, 29.50900000, 1),
(150, 19, 'Camazi', -3.23000000, 29.51000000, 1),
(151, 19, 'Gisoko', -3.22900000, 29.51100000, 1),
(152, 19, 'Mburi', -3.22800000, 29.51200000, 1),
(153, 19, 'Muzire', -3.22700000, 29.51300000, 1),
(154, 19, 'Rabiro', -3.22600000, 29.51400000, 1),
(155, 19, 'Ruramba', -3.22500000, 29.51500000, 1),
(156, 20, 'Cendajuru', -3.22400000, 29.51600000, 1),
(157, 20, 'Gahoko', -3.22300000, 29.51700000, 1),
(158, 20, 'Kabageni', -3.22200000, 29.51800000, 1),
(159, 20, 'Kibande', -3.22100000, 29.51900000, 1),
(160, 20, 'Kigarika', -3.22000000, 29.52000000, 1),
(161, 20, 'Kiruhura', -3.21900000, 29.52100000, 1),
(162, 20, 'Kiyange', -3.21800000, 29.52200000, 1),
(163, 21, 'Gerero', -3.21700000, 29.52300000, 1),
(164, 21, 'Gisagara', -3.21600000, 29.52400000, 1),
(165, 21, 'Gitanga', -3.21500000, 29.52500000, 1),
(166, 21, 'Gitwenge', -3.21400000, 29.52600000, 1),
(167, 21, 'Kagoma', -3.21300000, 29.52700000, 1),
(168, 21, 'Kibogoye', -3.21200000, 29.52800000, 1),
(169, 21, 'Kigati', -3.21100000, 29.52900000, 1),
(170, 21, 'Kirambi', -3.21000000, 29.53000000, 1),
(171, 21, 'Muganza', -3.20900000, 29.53100000, 1),
(172, 21, 'Muhingamo', -3.20800000, 29.53200000, 1),
(173, 21, 'Murago', -3.20700000, 29.53300000, 1),
(174, 21, 'Nyuro', -3.20600000, 29.53400000, 1),
(175, 21, 'Ramba', -3.20500000, 29.53500000, 1),
(176, 21, 'Rubabara', -3.20400000, 29.53600000, 1),
(177, 22, 'Busumanyi', -3.20300000, 29.53700000, 1),
(178, 22, 'Kaniha', -3.20200000, 29.53800000, 1),
(179, 22, 'Mugera', -3.20100000, 29.53900000, 1),
(180, 22, 'Musemo', -3.20000000, 29.54000000, 1),
(181, 23, 'Buyongwe', -3.19900000, 29.54100000, 1),
(182, 23, 'Gikonko', -3.19800000, 29.54200000, 1),
(183, 23, 'Mishiha', -3.19700000, 29.54300000, 1),
(184, 23, 'Munzenze', -3.19600000, 29.54400000, 1),
(185, 23, 'Rutsindu', -3.19500000, 29.54500000, 1),
(186, 24, 'Kibimba', -3.19400000, 29.54600000, 1),
(187, 24, 'Mwiruzi', -3.19300000, 29.54700000, 1),
(188, 24, 'Rugerero', -3.19200000, 29.54800000, 1),
(189, 24, 'Rukwega', -3.19100000, 29.54900000, 1),
(190, 24, 'Runihira', -3.19000000, 29.55000000, 1),
(191, 25, 'Busyana', -3.18900000, 29.55100000, 1),
(192, 25, 'Gashigwe', -3.18800000, 29.55200000, 1),
(193, 25, 'Gitaramuka', -3.18700000, 29.55300000, 1),
(194, 25, 'Nyamugari', -3.18600000, 29.55400000, 1),
(195, 25, 'Rukoyoyo', -3.18500000, 29.55500000, 1),
(196, 26, 'Gisoro', -3.18400000, 29.55600000, 1),
(197, 26, 'Misugi', -3.18300000, 29.55700000, 1),
(198, 26, 'Nyagisovu', -3.18200000, 29.55800000, 1),
(199, 26, 'Nyakuguma', -3.18100000, 29.55900000, 1),
(200, 26, 'Twinkwavu', -3.18000000, 29.56000000, 1),
(201, 27, 'Bugama', -3.17900000, 29.56100000, 1),
(202, 27, 'Bunyambo', -3.17800000, 29.56200000, 1),
(203, 27, 'Gahinga', -3.17700000, 29.56300000, 1),
(204, 27, 'Gisuru', -3.17600000, 29.56400000, 1),
(205, 27, 'Itahe', -3.17500000, 29.56500000, 1),
(206, 27, 'Kabingo', -3.17400000, 29.56600000, 1),
(207, 27, 'Kinama', -3.17300000, 29.56700000, 1),
(208, 27, 'Kireka', -3.17200000, 29.56800000, 1),
(209, 27, 'Muhindo', -3.17100000, 29.56900000, 1),
(210, 27, 'Murehe', -3.17000000, 29.57000000, 1),
(211, 27, 'Ntende', -3.16900000, 29.57100000, 1),
(212, 27, 'Nyabigabiro', -3.16800000, 29.57200000, 1),
(213, 27, 'Rusange', -3.16700000, 29.57300000, 1),
(214, 27, 'Ruyaga', -3.16600000, 29.57400000, 1),
(215, 27, 'Rwerambere', -3.16500000, 29.57500000, 1),
(216, 28, 'Kabanga', -3.16400000, 29.57600000, 1),
(217, 28, 'Kigangabuko', -3.16300000, 29.57700000, 1),
(218, 28, 'Munazi', -3.16200000, 29.57800000, 1),
(219, 28, 'Musumba', -3.16100000, 29.57900000, 1),
(220, 28, 'Nyamigina', -3.16000000, 29.58000000, 1),
(221, 28, 'Nyamusasa', -3.15900000, 29.58100000, 1),
(222, 28, 'Ruveri', -3.15800000, 29.58200000, 1),
(223, 29, 'Bugongo', -3.15700000, 29.58300000, 1),
(224, 29, 'Gasunu', -3.15600000, 29.58400000, 1),
(225, 29, 'Gataba', -3.15500000, 29.58500000, 1),
(226, 29, 'Karindo', -3.15400000, 29.58600000, 1),
(227, 29, 'Kibari', -3.15300000, 29.58700000, 1),
(228, 29, 'Kinyinya', -3.15200000, 29.58800000, 1),
(229, 29, 'Mayanza', -3.15100000, 29.58900000, 1),
(230, 29, 'Muvumu', -3.15000000, 29.59000000, 1),
(231, 29, 'Nyakibere', -3.14900000, 29.59100000, 1),
(232, 29, 'Nyamunazi', -3.14800000, 29.59200000, 1),
(233, 29, 'Vumwe', -3.14700000, 29.59300000, 1),
(234, 30, 'Bwome', -3.14600000, 29.59400000, 1),
(235, 30, 'Gatare', -3.14500000, 29.59500000, 1),
(236, 30, 'Kivoga', -3.14400000, 29.59600000, 1),
(237, 30, 'Nyakibingo', -3.14300000, 29.59700000, 1),
(238, 30, 'Nyakiyonga', -3.14200000, 29.59800000, 1),
(239, 30, 'Nyamasenga', -3.14100000, 29.59900000, 1),
(240, 30, 'Nyamitanga', -3.14000000, 29.60000000, 1),
(241, 30, 'Nyaruganda', -3.13900000, 29.60100000, 1),
(242, 30, 'Nyarumuri', -3.13800000, 29.60200000, 1),
(243, 30, 'Remba', -3.13700000, 29.60300000, 1),
(244, 30, 'Ruharo', -3.13600000, 29.60400000, 1),
(245, 30, 'Titi', -3.13500000, 29.60500000, 1),
(246, 31, 'Butarangira', -3.13400000, 29.60600000, 1),
(247, 31, 'Gacokwe', -3.13300000, 29.60700000, 1),
(248, 31, 'Gakangaga', -3.13200000, 29.60800000, 1),
(249, 31, 'Itaba', -3.13100000, 29.60900000, 1),
(250, 31, 'Kabuyenge', -3.13000000, 29.61000000, 1),
(251, 31, 'Kavumwe', -3.12900000, 29.61100000, 1),
(252, 31, 'Musha', -3.12800000, 29.61200000, 1),
(253, 31, 'Ndemeka', -3.12700000, 29.61300000, 1),
(254, 32, 'Caga', -3.12600000, 29.61400000, 1),
(255, 32, 'Kigamba', -3.12500000, 29.61500000, 1),
(256, 32, 'Migende', -3.12400000, 29.61600000, 1),
(257, 32, 'Muvumu', -3.12300000, 29.61700000, 1),
(258, 32, 'Mwegereza', -3.12200000, 29.61800000, 1),
(259, 32, 'Nyabigozi', -3.12100000, 29.61900000, 1),
(260, 32, 'Nyabitaka', -3.12000000, 29.62000000, 1),
(261, 32, 'Nyabitare', -3.11900000, 29.62100000, 1),
(262, 32, 'Nyakirunga', -3.11800000, 29.62200000, 1),
(263, 32, 'Nyakivumu', -3.11700000, 29.62300000, 1),
(264, 32, 'Rubanga', -3.11600000, 29.62400000, 1),
(265, 32, 'Ruhuni', -3.11500000, 29.62500000, 1),
(266, 33, 'Bihembe', -3.11400000, 29.62600000, 1),
(267, 33, 'Kirungu', -3.11300000, 29.62700000, 1),
(268, 33, 'Mago', -3.11200000, 29.62800000, 1),
(269, 33, 'Muramba', -3.11100000, 29.62900000, 1),
(270, 33, 'Mureba', -3.11000000, 29.63000000, 1),
(271, 33, 'Murehe', -3.10900000, 29.63100000, 1),
(272, 33, 'Ndago', -3.10800000, 29.63200000, 1),
(273, 33, 'Nyabitsinda', -3.10700000, 29.63300000, 1),
(274, 33, 'Nyagahanda', -3.10600000, 29.63400000, 1),
(275, 33, 'Nyagitika', -3.10500000, 29.63500000, 1),
(276, 33, 'Nyamitukwe', -3.10400000, 29.63600000, 1),
(277, 34, 'Iteka', -3.10300000, 29.63700000, 1),
(278, 34, 'Iyogero', -3.10200000, 29.63800000, 1),
(279, 34, 'Kinanira', -3.10100000, 29.63900000, 1),
(280, 34, 'Munyinya', -3.10000000, 29.64000000, 1),
(281, 34, 'Nkurubuye', -3.09900000, 29.64100000, 1),
(282, 34, 'Nyarumanga', -3.09800000, 29.64200000, 1),
(283, 34, 'Rukobe', -3.09700000, 29.64300000, 1),
(284, 34, 'Rutonde', -3.09600000, 29.64400000, 1),
(285, 35, 'Butirabura', -3.09500000, 29.64500000, 1),
(286, 35, 'Bwasare', -3.09400000, 29.64600000, 1),
(287, 35, 'Gishuha', -3.09300000, 29.64700000, 1),
(288, 35, 'Jani', -3.09200000, 29.64800000, 1),
(289, 35, 'Kagugwe', -3.09100000, 29.64900000, 1),
(290, 35, 'Kaguhu', -3.09000000, 29.65000000, 1),
(291, 35, 'Karama', -3.08900000, 29.65100000, 1),
(292, 35, 'Rukinzo', -3.08800000, 29.65200000, 1),
(293, 36, 'Buhinyuza', -3.08700000, 29.65300000, 1),
(294, 36, 'Karehe', -3.08600000, 29.65400000, 1),
(295, 36, 'Kibimba', -3.08500000, 29.65500000, 1),
(296, 36, 'Mabago', -3.08400000, 29.65600000, 1),
(297, 36, 'Nyabucugu', -3.08300000, 29.65700000, 1),
(298, 36, 'Nyaruhengeri', -3.08200000, 29.65800000, 1),
(299, 36, 'Nyarunazi', -3.08100000, 29.65900000, 1),
(300, 36, 'Rugazi', -3.08000000, 29.66000000, 1),
(301, 37, 'Burenza', -3.07900000, 29.66100000, 1),
(302, 37, 'Butihinda', -3.07800000, 29.66200000, 1),
(303, 37, 'Bwica', -3.07700000, 29.66300000, 1),
(304, 37, 'Cumba', -3.07600000, 29.66400000, 1),
(305, 37, 'Kiringanire', -3.07500000, 29.66500000, 1),
(306, 37, 'Mizuga', -3.07400000, 29.66600000, 1),
(307, 37, 'Nyamirambo', -3.07300000, 29.66700000, 1),
(308, 37, 'Nyarusange', -3.07200000, 29.66800000, 1),
(309, 37, 'Ruganigwa', -3.07100000, 29.66900000, 1),
(310, 38, 'Bugungu', -3.07000000, 29.67000000, 1),
(311, 38, 'Bunywana', -3.06900000, 29.67100000, 1),
(312, 38, 'Butihinda', -3.06800000, 29.67200000, 1),
(313, 38, 'Gasave', -3.06700000, 29.67300000, 1),
(314, 38, 'Gitaramuka', -3.06600000, 29.67400000, 1),
(315, 38, 'Karongwe', -3.06500000, 29.67500000, 1),
(316, 38, 'Kiyange', -3.06400000, 29.67600000, 1),
(317, 38, 'Muramba', -3.06300000, 29.67700000, 1),
(318, 38, 'Ntobwe', -3.06200000, 29.67800000, 1),
(319, 39, 'Bihogo', -3.06100000, 29.67900000, 1),
(320, 39, 'Gikwiye', -3.06000000, 29.68000000, 1),
(321, 39, 'Karambo', -3.05900000, 29.68100000, 1),
(322, 39, 'Karira', -3.05800000, 29.68200000, 1),
(323, 39, 'Kinama', -3.05700000, 29.68300000, 1),
(324, 39, 'Kiryama', -3.05600000, 29.68400000, 1),
(325, 39, 'Masasu', -3.05500000, 29.68500000, 1),
(326, 39, 'Rusimbuko', -3.05400000, 29.68600000, 1),
(327, 40, 'Higiro', -3.05300000, 29.68700000, 1),
(328, 40, 'Karimbi', -3.05200000, 29.68800000, 1),
(329, 40, 'Kigoganya', -3.05100000, 29.68900000, 1),
(330, 40, 'Ngogomo', -3.05000000, 29.69000000, 1),
(331, 40, 'Nyarubambwe', -3.04900000, 29.69100000, 1),
(332, 40, 'Nyungu', -3.04800000, 29.69200000, 1),
(333, 41, 'Burasira', -3.04700000, 29.69300000, 1),
(334, 41, 'Gihongo', -3.04600000, 29.69400000, 1),
(335, 41, 'Jarama', -3.04500000, 29.69500000, 1),
(336, 41, 'Kara', -3.04400000, 29.69600000, 1),
(337, 41, 'Nyagishiru', -3.04300000, 29.69700000, 1),
(338, 41, 'Nyankurazo', -3.04200000, 29.69800000, 1),
(339, 41, 'Rugongo', -3.04100000, 29.69900000, 1),
(340, 41, 'Ruvumu', -3.04000000, 29.70000000, 1),
(341, 42, 'Bonero', -3.03900000, 29.70100000, 1),
(342, 42, 'Bubaji', -3.03800000, 29.70200000, 1),
(343, 42, 'Ciyando', -3.03700000, 29.70300000, 1),
(344, 42, 'Gahekenya', -3.03600000, 29.70400000, 1),
(345, 42, 'Gasenyi', -3.03500000, 29.70500000, 1),
(346, 42, 'Gitaba', -3.03400000, 29.70600000, 1),
(347, 42, 'Kibongera', -3.03300000, 29.70700000, 1),
(348, 42, 'Kigajo', -3.03200000, 29.70800000, 1),
(349, 42, 'Kiyanza', -3.03100000, 29.70900000, 1),
(350, 42, 'Mukungu', -3.03000000, 29.71000000, 1),
(351, 42, 'Musenga', -3.02900000, 29.71100000, 1),
(352, 42, 'Rutyazo', -3.02800000, 29.71200000, 1),
(353, 43, 'Kayenzi', -3.02700000, 29.71300000, 1),
(354, 43, 'Kibongera', -3.02600000, 29.71400000, 1),
(355, 43, 'Mageni', -3.02500000, 29.71500000, 1),
(356, 43, 'Musenga', -3.02400000, 29.71600000, 1),
(357, 43, 'Nkoyoyo', -3.02300000, 29.71700000, 1),
(358, 43, 'Rusengo', -3.02200000, 29.71800000, 1),
(359, 44, 'Gasuru', -3.02100000, 29.71900000, 1),
(360, 44, 'Kimanga', -3.02000000, 29.72000000, 1),
(361, 44, 'Kiremba', -3.01900000, 29.72100000, 1),
(362, 44, 'Kivubo', -3.01800000, 29.72200000, 1),
(363, 44, 'Kizi', -3.01700000, 29.72300000, 1),
(364, 44, 'Martyazo', -3.01600000, 29.72400000, 1),
(365, 45, 'Buhurana', -3.01500000, 29.72500000, 1),
(366, 45, 'Gatovu', -3.01400000, 29.72600000, 1),
(367, 45, 'Munagano', -3.01300000, 29.72700000, 1),
(368, 45, 'Musenyi', -3.01200000, 29.72800000, 1),
(369, 45, 'Ntamba', -3.01100000, 29.72900000, 1),
(370, 45, 'Nyamarumba', -3.01000000, 29.73000000, 1),
(371, 45, 'Sanzwe', -3.00900000, 29.73100000, 1),
(372, 46, 'Gasasa', -3.00800000, 29.73200000, 1),
(373, 46, 'Kavumu', -3.00700000, 29.73300000, 1),
(374, 46, 'Mubuga', -3.00600000, 29.73400000, 1),
(375, 46, 'Murama', -3.00500000, 29.73500000, 1),
(376, 46, 'Mwurire', -3.00400000, 29.73600000, 1),
(377, 46, 'Rusumo', -3.00300000, 29.73700000, 1),
(378, 46, 'Ryabihira', -3.00200000, 29.73800000, 1),
(379, 47, 'Bugonza', -3.00100000, 29.73900000, 1),
(380, 47, 'Butobwe', -3.00000000, 29.74000000, 1),
(381, 47, 'Gakombe', -2.99900000, 29.74100000, 1),
(382, 47, 'Gihoza', -2.99800000, 29.74200000, 1),
(383, 47, 'Kabingo', -2.99700000, 29.74300000, 1),
(384, 47, 'Karehe', -2.99600000, 29.74400000, 1),
(385, 47, 'Kibande', -2.99500000, 29.74500000, 1),
(386, 47, 'Kibwirwa', -2.99400000, 29.74600000, 1),
(387, 47, 'Muyange', -2.99300000, 29.74700000, 1),
(388, 47, 'Mwakiro', -2.99200000, 29.74800000, 1),
(389, 48, 'Bugomora', -2.99100000, 29.74900000, 1),
(390, 48, 'Burima', -2.99000000, 29.75000000, 1),
(391, 48, 'Cibari', -2.98900000, 29.75100000, 1),
(392, 48, 'Gahororo', -2.98800000, 29.75200000, 1),
(393, 48, 'Gitongwe', -2.98700000, 29.75300000, 1),
(394, 48, 'Karemera', -2.98600000, 29.75400000, 1),
(395, 48, 'Kiryama', -2.98500000, 29.75500000, 1),
(396, 48, 'Kivoga', -2.98400000, 29.75600000, 1),
(397, 48, 'Rwimbogo', -2.98300000, 29.75700000, 1),
(398, 48, 'Quartier Kibogoye', -2.98200000, 29.75800000, 1),
(399, 48, 'Quartier Kinyota', -2.98100000, 29.75900000, 1),
(400, 48, 'Quartier Kwibuye', -2.98000000, 29.76000000, 1),
(401, 48, 'Quartier Mukoni', -2.97900000, 29.76100000, 1),
(402, 48, 'Quartier Muyinga', -2.97800000, 29.76200000, 1),
(403, 49, 'Bukwanzi', -2.97700000, 29.76300000, 1),
(404, 49, 'Gahemba', -2.97600000, 29.76400000, 1),
(405, 49, 'Gisuma', -2.97500000, 29.76500000, 1),
(406, 49, 'Kadende', -2.97400000, 29.76600000, 1),
(407, 49, 'Kavugangoma', -2.97300000, 29.76700000, 1),
(408, 49, 'Mukunguza', -2.97200000, 29.76800000, 1),
(409, 49, 'Rugabano', -2.97100000, 29.76900000, 1),
(410, 49, 'Rukanya', -2.97000000, 29.77000000, 1),
(411, 49, 'Rusheri', -2.96900000, 29.77100000, 1),
(412, 50, 'Bunywana', -2.96800000, 29.77200000, 1),
(413, 50, 'Gatongati', -2.96700000, 29.77300000, 1),
(414, 50, 'Kinazi', -2.96600000, 29.77400000, 1),
(415, 50, 'Mahonda', -2.96500000, 29.77500000, 1),
(416, 50, 'Migogo', -2.96400000, 29.77600000, 1),
(417, 50, 'Rugari', -2.96300000, 29.77700000, 1),
(418, 50, 'Rutoke', -2.96200000, 29.77800000, 1),
(419, 50, 'Ruyiyi', -2.96100000, 29.77900000, 1),
(420, 51, 'Busoro', -2.96000000, 29.78000000, 1),
(421, 51, 'Gasenyi', -2.95900000, 29.78100000, 1),
(422, 51, 'Kigusu', -2.95800000, 29.78200000, 1),
(423, 51, 'Rubavu', -2.95700000, 29.78300000, 1),
(424, 52, 'Kirasira', -2.95600000, 29.78400000, 1),
(425, 52, 'Nkongwe', -2.95500000, 29.78500000, 1),
(426, 52, 'Rugoti', -2.95400000, 29.78600000, 1),
(427, 52, 'Sorero', -2.95300000, 29.78700000, 1),
(428, 53, 'Bwagiriza', -2.95200000, 29.78800000, 1),
(429, 53, 'Gashurushuru', -2.95100000, 29.78900000, 1),
(430, 53, 'Munyinya', -2.95000000, 29.79000000, 1),
(431, 53, 'Nombe', -2.94900000, 29.79100000, 1),
(432, 53, 'Rubaragaza', -2.94800000, 29.79200000, 1),
(433, 53, 'Rutegama', -2.94700000, 29.79300000, 1),
(434, 53, 'Senga', -2.94600000, 29.79400000, 1),
(435, 54, 'Busuma', -2.94500000, 29.79500000, 1),
(436, 54, 'Caga', -2.94400000, 29.79600000, 1),
(437, 54, 'Kanisha', -2.94300000, 29.79700000, 1),
(438, 54, 'Mibanga', -2.94200000, 29.79800000, 1),
(439, 54, 'Nkanda', -2.94100000, 29.79900000, 1),
(440, 55, 'Bigombo', -2.94000000, 29.80000000, 1),
(441, 55, 'Gatwaro', -2.93900000, 29.80100000, 1),
(442, 55, 'Kirambi', -2.93800000, 29.80200000, 1),
(443, 55, 'Nyamugari', -2.93700000, 29.80300000, 1),
(444, 55, 'Nyarunazi', -2.93600000, 29.80400000, 1),
(445, 55, 'Ruvyagira', -2.93500000, 29.80500000, 1),
(446, 56, 'Gitwa', -2.93400000, 29.80600000, 1),
(447, 56, 'Mubira', -2.93300000, 29.80700000, 1),
(448, 56, 'Mugogo', -2.93200000, 29.80800000, 1),
(449, 56, 'Muyange', -2.93100000, 29.80900000, 1),
(450, 57, 'Bweru', -2.93000000, 29.81000000, 1),
(451, 57, 'Gashawe', -2.92900000, 29.81100000, 1),
(452, 57, 'Masama', -2.92800000, 29.81200000, 1),
(453, 57, 'Mubavu', -2.92700000, 29.81300000, 1),
(454, 57, 'Ntunda', -2.92600000, 29.81400000, 1),
(455, 57, 'Nzozi', -2.92500000, 29.81500000, 1),
(456, 58, 'Bunogera', -2.92400000, 29.81600000, 1),
(457, 58, 'Buruhukiro', -2.92300000, 29.81700000, 1),
(458, 58, 'Gisoro', -2.92200000, 29.81800000, 1),
(459, 58, 'Kirambi', -2.92100000, 29.81900000, 1),
(460, 58, 'Migege', -2.92000000, 29.82000000, 1),
(461, 58, 'Nganji', -2.91900000, 29.82100000, 1),
(462, 58, 'Nyagutoha', -2.91800000, 29.82200000, 1),
(463, 59, 'Dutwe', -2.91700000, 29.82300000, 1),
(464, 59, 'Gahemba', -2.91600000, 29.82400000, 1),
(465, 59, 'Kigamba', -2.91500000, 29.82500000, 1),
(466, 59, 'Ngarama', -2.91400000, 29.82600000, 1),
(467, 59, 'Nyarunazi', -2.91300000, 29.82700000, 1),
(468, 59, 'Ruhwago', -2.91200000, 29.82800000, 1),
(469, 59, 'Rukaragata', -2.91100000, 29.82900000, 1),
(470, 59, 'Ruyigi', -2.91000000, 29.83000000, 1),
(471, 59, 'Quartier Gasanda', -2.90900000, 29.83100000, 1),
(472, 59, 'Quartier Kinyabakecuru', -2.90800000, 29.83200000, 1),
(473, 59, 'Quartier Sanzu', -2.90700000, 29.83300000, 1),
(474, 60, 'Quartier Bubanza', -2.90600000, 29.83400000, 1),
(475, 60, 'Quartier Buhororo', -2.90500000, 29.83500000, 1),
(476, 60, 'Quartier Giko', -2.90400000, 29.83600000, 1),
(477, 60, 'Quartier Matonge', -2.90300000, 29.83700000, 1),
(478, 60, 'Quartier Ruvumvu', -2.90200000, 29.83800000, 1),
(479, 61, 'Cimbizi', -2.90100000, 29.83900000, 1),
(480, 61, 'Muhanza', -2.90000000, 29.84000000, 1),
(481, 61, 'Muhenga', -2.89900000, 29.84100000, 1),
(482, 61, 'Nyabitaka', -2.89800000, 29.84200000, 1),
(483, 61, 'Rugunga', -2.89700000, 29.84300000, 1),
(484, 61, 'Rurabo', -2.89600000, 29.84400000, 1),
(485, 61, 'Zina', -2.89500000, 29.84500000, 1),
(486, 62, 'Busiga', -2.89400000, 29.84600000, 1),
(487, 62, 'Gashinge', -2.89300000, 29.84700000, 1),
(488, 62, 'Gatare', -2.89200000, 29.84800000, 1),
(489, 62, 'Kiziba', -2.89100000, 29.84900000, 1),
(490, 62, 'Munanira', -2.89000000, 29.85000000, 1),
(491, 63, 'Gahongore', -2.88900000, 29.85100000, 1),
(492, 63, 'Gatura', -2.88800000, 29.85200000, 1),
(493, 63, 'Kagirigiri', -2.88700000, 29.85300000, 1),
(494, 63, 'Mitakataka', -2.88600000, 29.85400000, 1),
(495, 64, 'Ciya', -2.88500000, 29.85500000, 1),
(496, 64, 'Gitanga', -2.88400000, 29.85600000, 1),
(497, 64, 'Gitsira', -2.88300000, 29.85700000, 1),
(498, 64, 'Karinzi', -2.88200000, 29.85800000, 1),
(499, 64, 'Kivyiru', -2.88100000, 29.85900000, 1),
(500, 64, 'Mugimbu', -2.88000000, 29.86000000, 1),
(501, 64, 'Muramba', -2.87900000, 29.86100000, 1),
(502, 64, 'Mwanda', -2.87800000, 29.86200000, 1),
(503, 64, 'Ngara', -2.87700000, 29.86300000, 1),
(504, 64, 'Rabiro', -2.87600000, 29.86400000, 1),
(505, 65, 'Buhurika', -2.87500000, 29.86500000, 1),
(506, 65, 'Bukinga', -2.87400000, 29.86600000, 1),
(507, 65, 'Dondi', -2.87300000, 29.86700000, 1),
(508, 65, 'Kanazi', -2.87200000, 29.86800000, 1),
(509, 65, 'Kayange', -2.87100000, 29.86900000, 1),
(510, 65, 'Mpishi', -2.87000000, 29.87000000, 1),
(511, 65, 'Mugombarima', -2.86900000, 29.87100000, 1),
(512, 65, 'Musigati', -2.86800000, 29.87200000, 1),
(513, 65, 'Nyarusange', -2.86700000, 29.87300000, 1),
(514, 66, 'Bubenga', -2.86600000, 29.87400000, 1),
(515, 66, 'Butaha', -2.86500000, 29.87500000, 1),
(516, 66, 'Masare', -2.86400000, 29.87600000, 1),
(517, 66, 'Mugoma', -2.86300000, 29.87700000, 1),
(518, 66, 'Muyebe', -2.86200000, 29.87800000, 1),
(519, 66, 'Ruvyimvya', -2.86100000, 29.87900000, 1),
(520, 67, 'Mpinga', -2.86000000, 29.88000000, 1),
(521, 67, 'Ntamba', -2.85900000, 29.88100000, 1),
(522, 67, 'Rugeyo', -2.85800000, 29.88200000, 1),
(523, 67, 'Rusekabuye', -2.85700000, 29.88300000, 1),
(524, 67, 'Rushiha', -2.85600000, 29.88400000, 1),
(525, 68, 'Cunyu', -2.85500000, 29.88500000, 1),
(526, 68, 'Gasenyi', -2.85400000, 29.88600000, 1),
(527, 68, 'Kaburantwa', -2.85300000, 29.88700000, 1),
(528, 68, 'Ruhagarika', -2.85200000, 29.88800000, 1),
(529, 69, 'Buhayira', -2.85100000, 29.88900000, 1),
(530, 69, 'Kabuye', -2.85000000, 29.89000000, 1),
(531, 69, 'Muzenga', -2.84900000, 29.89100000, 1),
(532, 69, 'Remera', -2.84800000, 29.89200000, 1),
(533, 69, 'Rugano', -2.84700000, 29.89300000, 1),
(534, 70, 'Bumba', -2.84600000, 29.89400000, 1),
(535, 70, 'Butara', -2.84500000, 29.89500000, 1),
(536, 70, 'Munyinya', -2.84400000, 29.89600000, 1),
(537, 70, 'Nderama', -2.84300000, 29.89700000, 1),
(538, 70, 'Rtyazo', -2.84200000, 29.89800000, 1),
(539, 70, 'Ruhembe', -2.84100000, 29.89900000, 1),
(540, 70, 'Runege', -2.84000000, 29.90000000, 1),
(541, 71, 'Gasheke', -2.83900000, 29.90100000, 1),
(542, 71, 'Gitera', -2.83800000, 29.90200000, 1),
(543, 71, 'Jerama', -2.83700000, 29.90300000, 1),
(544, 71, 'Maranga', -2.83600000, 29.90400000, 1),
(545, 71, 'Nyarurinzi', -2.83500000, 29.90500000, 1),
(546, 72, 'Gahabura', -2.83400000, 29.90600000, 1),
(547, 72, 'Mikoni', -2.83300000, 29.90700000, 1),
(548, 72, 'Murengera', -2.83200000, 29.90800000, 1),
(549, 72, 'Rangira', -2.83100000, 29.90900000, 1),
(550, 72, 'Rubaya', -2.83000000, 29.91000000, 1),
(551, 73, 'Kibati', -2.82900000, 29.91100000, 1),
(552, 73, 'Masango', -2.82800000, 29.91200000, 1),
(553, 73, 'Mwungo', -2.82700000, 29.91300000, 1),
(554, 73, 'Nyarwumba', -2.82600000, 29.91400000, 1),
(555, 73, 'Sehe', -2.82500000, 29.91500000, 1),
(556, 74, 'Kansega', -2.82400000, 29.91600000, 1),
(557, 74, 'Muremera', -2.82300000, 29.91700000, 1),
(558, 74, 'Mwunguzi', -2.82200000, 29.91800000, 1),
(559, 74, 'Ndava', -2.82100000, 29.91900000, 1),
(560, 74, 'Nimba', -2.82000000, 29.92000000, 1),
(561, 74, 'Nyamitanga', -2.81900000, 29.92100000, 1),
(562, 75, 'Bihembe', -2.81800000, 29.92200000, 1),
(563, 75, 'Bitare', -2.81700000, 29.92300000, 1),
(564, 75, 'Burimbi', -2.81600000, 29.92400000, 1),
(565, 75, 'Giserama', -2.81500000, 29.92500000, 1),
(566, 75, 'Kabondo', -2.81400000, 29.92600000, 1),
(567, 75, 'Kibaya', -2.81300000, 29.92700000, 1),
(568, 75, 'Myave', -2.81200000, 29.92800000, 1),
(569, 75, 'Nyamyeha', -2.81100000, 29.92900000, 1),
(570, 75, 'Nyarubugu', -2.81000000, 29.93000000, 1),
(571, 76, 'Kabere', -2.80900000, 29.93100000, 1),
(572, 76, 'Nyampinda', -2.80800000, 29.93200000, 1),
(573, 76, 'Nyangwe', -2.80700000, 29.93300000, 1),
(574, 76, 'Rusenda', -2.80600000, 29.93400000, 1),
(575, 76, 'Shimwe', -2.80500000, 29.93500000, 1),
(576, 77, 'Buhindo', -2.80400000, 29.93600000, 1),
(577, 77, 'Butega', -2.80300000, 29.93700000, 1),
(578, 77, 'Kagimbu', -2.80200000, 29.93800000, 1),
(579, 77, 'Kahirwa', -2.80100000, 29.93900000, 1),
(580, 77, 'Kajera', -2.80000000, 29.94000000, 1),
(581, 77, 'Kivumu', -2.79900000, 29.94100000, 1),
(582, 78, 'Murambi', -2.79800000, 29.94200000, 1),
(583, 78, 'Quartier Cibitoke', -2.79700000, 29.94300000, 1),
(584, 78, 'Quartier Kagazi', -2.79600000, 29.94400000, 1),
(585, 78, 'Quartier Karurama', -2.79500000, 29.94500000, 1),
(586, 78, 'Quartier Rusiga', -2.79400000, 29.94600000, 1),
(587, 79, 'Kiramira', -2.79300000, 29.94700000, 1),
(588, 79, 'Mihiza', -2.79200000, 29.94800000, 1),
(589, 79, 'Rusororo', -2.79100000, 29.94900000, 1),
(590, 79, 'Ruvumera', -2.79000000, 29.95000000, 1),
(591, 80, 'Bubogora', -2.78900000, 29.95100000, 1),
(592, 80, 'Gitohera', -2.78800000, 29.95200000, 1),
(593, 80, 'Manege', -2.78700000, 29.95300000, 1),
(594, 80, 'Masha', -2.78600000, 29.95400000, 1),
(595, 80, 'Mirombero', -2.78500000, 29.95500000, 1),
(596, 80, 'Murwi', -2.78400000, 29.95600000, 1),
(597, 80, 'Mushanga', -2.78300000, 29.95700000, 1),
(598, 81, 'Mahande', -2.78200000, 29.95800000, 1),
(599, 81, 'Mugimbu', -2.78100000, 29.95900000, 1),
(600, 81, 'Ngoma', -2.78000000, 29.96000000, 1),
(601, 81, 'Nyabubuye', -2.77900000, 29.96100000, 1),
(602, 82, 'Dogodogo', -2.77800000, 29.96200000, 1),
(603, 82, 'Mparambo', -2.77700000, 29.96300000, 1),
(604, 82, 'Rugeregere', -2.77600000, 29.96400000, 1),
(605, 82, 'Samwe', -2.77500000, 29.96500000, 1),
(606, 82, 'Quartier Munyika', -2.77400000, 29.96600000, 1),
(607, 82, 'Quartier Nyakagunda', -2.77300000, 29.96700000, 1),
(608, 83, 'Gabiro', -2.77200000, 29.96800000, 1),
(609, 83, 'Gicaca', -2.77100000, 29.96900000, 1),
(610, 83, 'Musenyi', -2.77000000, 29.97000000, 1),
(611, 83, 'Ruhwa', -2.76900000, 29.97100000, 1),
(612, 83, 'Rukana', -2.76800000, 29.97200000, 1),
(613, 84, 'Buyimba', -2.76700000, 29.97300000, 1),
(614, 84, 'Kibuye', -2.76600000, 29.97400000, 1),
(615, 84, 'Nyarukere', -2.76500000, 29.97500000, 1),
(616, 84, 'Rusha', -2.76400000, 29.97600000, 1),
(617, 84, 'Sagara', -2.76300000, 29.97700000, 1),
(618, 85, 'Gasenyi', -2.76200000, 29.97800000, 1),
(619, 85, 'Gatebe', -2.76100000, 29.97900000, 1),
(620, 85, 'Kavya', -2.76000000, 29.98000000, 1),
(621, 85, 'Muturirwa', -2.75900000, 29.98100000, 1),
(622, 85, 'Rubaya', -2.75800000, 29.98200000, 1),
(623, 85, 'Rutunda', -2.75700000, 29.98300000, 1),
(624, 86, 'Buhanda', -2.75600000, 29.98400000, 1),
(625, 86, 'Gitwe', -2.75500000, 29.98500000, 1),
(626, 86, 'Karugamba', -2.75400000, 29.98600000, 1),
(627, 86, 'Kinama', -2.75300000, 29.98700000, 1),
(628, 86, 'Mageyo', -2.75200000, 29.98800000, 1),
(629, 86, 'Muhororo', -2.75100000, 29.98900000, 1),
(630, 86, 'Muyabara', -2.75000000, 29.99000000, 1),
(631, 87, 'Burenza', -2.74900000, 29.99100000, 1),
(632, 87, 'Butega', -2.74800000, 29.99200000, 1),
(633, 87, 'Kanyinya', -2.74700000, 29.99300000, 1),
(634, 87, 'Kigunga', -2.74600000, 29.99400000, 1),
(635, 87, 'Kinyinya', -2.74500000, 29.99500000, 1),
(636, 87, 'Magarure', -2.74400000, 29.99600000, 1),
(637, 88, 'Gisagara', -2.74300000, 29.99700000, 1),
(638, 88, 'Kiziba', -2.74200000, 29.99800000, 1),
(639, 88, 'Mubimbi', -2.74100000, 29.99900000, 1),
(640, 88, 'Muzazi', -2.74000000, 30.00000000, 1),
(641, 88, 'Nyankuba', -2.73900000, 30.00100000, 1),
(642, 89, 'Bibare', -2.73800000, 30.00200000, 1),
(643, 89, 'Caranka', -2.73700000, 30.00300000, 1),
(644, 89, 'Cirisha', -2.73600000, 30.00400000, 1),
(645, 89, 'Nyarumpongo', -2.73500000, 30.00500000, 1),
(646, 89, 'Rushubi', -2.73400000, 30.00600000, 1),
(647, 89, 'Rutegama', -2.73300000, 30.00700000, 1),
(648, 90, 'Gakenke', -2.73200000, 30.00800000, 1),
(649, 90, 'Gishubi', -2.73100000, 30.00900000, 1),
(650, 90, 'Kayoyo', -2.73000000, 30.01000000, 1),
(651, 90, 'Mugomere', -2.72900000, 30.01100000, 1),
(652, 90, 'Muhweza', -2.72800000, 30.01200000, 1),
(653, 90, 'Mwura', -2.72700000, 30.01300000, 1),
(654, 90, 'Zinga', -2.72600000, 30.01400000, 1),
(655, 91, 'Buringa', -2.72500000, 30.01500000, 1),
(656, 91, 'Mpanda', -2.72400000, 30.01600000, 1),
(657, 91, 'Ninga', -2.72300000, 30.01700000, 1),
(658, 91, 'Nyeshanga', -2.72200000, 30.01800000, 1),
(659, 91, 'Ruhahe', -2.72100000, 30.01900000, 1),
(660, 92, 'Butanuka', -2.72000000, 30.02000000, 1),
(661, 92, 'Butembe', -2.71900000, 30.02100000, 1),
(662, 92, 'Kanenga', -2.71800000, 30.02200000, 1),
(663, 92, 'Kivyibusha', -2.71700000, 30.02300000, 1),
(664, 92, 'Ruziba', -2.71600000, 30.02400000, 1),
(665, 93, 'Buramata', -2.71500000, 30.02500000, 1),
(666, 93, 'Gihanga', -2.71400000, 30.02600000, 1),
(667, 93, 'Kizina', -2.71300000, 30.02700000, 1),
(668, 93, 'Murira', -2.71200000, 30.02800000, 1),
(669, 93, 'Rumotomoto', -2.71100000, 30.02900000, 1),
(670, 94, 'Gahwazi', -2.71000000, 30.03000000, 1),
(671, 94, 'Gatagura', -2.70900000, 30.03100000, 1),
(672, 94, 'Gifurwe', -2.70800000, 30.03200000, 1),
(673, 94, 'Masha', -2.70700000, 30.03300000, 1),
(674, 94, 'Nyomvyi', -2.70600000, 30.03400000, 1),
(675, 95, 'Busongo', -2.70500000, 30.03500000, 1),
(676, 95, 'Cabiza', -2.70400000, 30.03600000, 1),
(677, 95, 'Gihungwe', -2.70300000, 30.03700000, 1),
(678, 95, 'Kagwema', -2.70200000, 30.03800000, 1),
(679, 95, 'Muyange', -2.70100000, 30.03900000, 1),
(680, 95, 'Rugunga', -2.70000000, 30.04000000, 1),
(681, 96, 'Murengeza', -2.69900000, 30.04100000, 1),
(682, 96, 'Musenyi', -2.69800000, 30.04200000, 1),
(683, 96, 'Nyamabere', -2.69700000, 30.04300000, 1),
(684, 96, 'Rubira', -2.69600000, 30.04400000, 1),
(685, 96, 'Rugenge', -2.69500000, 30.04500000, 1),
(686, 97, 'Butanuka', -2.69400000, 30.04600000, 1),
(687, 97, 'Kibenga', -2.69300000, 30.04700000, 1),
(688, 97, 'Kirengane', -2.69200000, 30.04800000, 1),
(689, 97, 'Mutara', -2.69100000, 30.04900000, 1),
(690, 97, 'Muzinda', -2.69000000, 30.05000000, 1),
(691, 98, 'Giseza', -2.68900000, 30.05100000, 1),
(692, 98, 'Kabanga', -2.68800000, 30.05200000, 1),
(693, 98, 'Ruce', -2.68700000, 30.05300000, 1),
(694, 98, 'Rutake', -2.68600000, 30.05400000, 1),
(695, 98, 'Rwamvurwe', -2.68500000, 30.05500000, 1),
(696, 99, 'Bugume', -2.68400000, 30.05600000, 1),
(697, 99, 'Karambira', -2.68300000, 30.05700000, 1),
(698, 99, 'Kayange', -2.68200000, 30.05800000, 1),
(699, 99, 'Kibuye', -2.68100000, 30.05900000, 1),
(700, 99, 'Nyenkarange', -2.68000000, 30.06000000, 1),
(701, 99, 'Rugazi', -2.67900000, 30.06100000, 1),
(702, 100, 'Bugongo', -2.67800000, 30.06200000, 1),
(703, 100, 'Buhanda', -2.67700000, 30.06300000, 1),
(704, 100, 'Masenga', -2.67600000, 30.06400000, 1),
(705, 100, 'Nyankere', -2.67500000, 30.06500000, 1),
(706, 100, 'Nyarwedeka', -2.67400000, 30.06600000, 1),
(707, 100, 'Rukingiro', -2.67300000, 30.06700000, 1),
(708, 100, 'Ruvyagira', -2.67200000, 30.06800000, 1),
(709, 101, 'Bubanza', -2.67100000, 30.06900000, 1),
(710, 101, 'Burima', -2.67000000, 30.07000000, 1),
(711, 101, 'Gakara', -2.66900000, 30.07100000, 1),
(712, 101, 'Gomvyi', -2.66800000, 30.07200000, 1),
(713, 101, 'Kayengwe', -2.66700000, 30.07300000, 1),
(714, 101, 'Murambi', -2.66600000, 30.07400000, 1),
(715, 101, 'Ntobo', -2.66500000, 30.07500000, 1),
(716, 101, 'Rubanda', -2.66400000, 30.07600000, 1),
(717, 101, 'Rutovu', -2.66300000, 30.07700000, 1),
(718, 102, 'Kabezi', -2.66200000, 30.07800000, 1),
(719, 102, 'Masama', -2.66100000, 30.07900000, 1),
(720, 102, 'Migera', -2.66000000, 30.08000000, 1),
(721, 102, 'Mwaza', -2.65900000, 30.08100000, 1),
(722, 103, 'Quartier Busoro', -2.65800000, 30.08200000, 1),
(723, 103, 'Quartier Gisyo', -2.65700000, 30.08300000, 1),
(724, 103, 'Quartier Kajiji', -2.65600000, 30.08400000, 1),
(725, 103, 'Quartier Musama', -2.65500000, 30.08500000, 1),
(726, 103, 'Quartier Nkenga', -2.65400000, 30.08600000, 1),
(727, 104, 'Quartier Kibenga', -2.65300000, 30.08700000, 1),
(728, 104, 'Quartier Kinindo', -2.65200000, 30.08800000, 1),
(729, 104, 'Quartier Rukamo', -2.65100000, 30.08900000, 1),
(730, 105, 'Buhina', -2.65000000, 30.09000000, 1),
(731, 105, 'Gisovu', -2.64900000, 30.09100000, 1),
(732, 105, 'Musugi', -2.64800000, 30.09200000, 1),
(733, 105, 'Nyamaboko', -2.64700000, 30.09300000, 1),
(734, 105, 'Rukuba', -2.64600000, 30.09400000, 1),
(735, 105, 'Ruvumu', -2.64500000, 30.09500000, 1),
(736, 106, 'Gitenga', -2.64400000, 30.09600000, 1),
(737, 106, 'Kimina', -2.64300000, 30.09700000, 1),
(738, 106, 'Mubone', -2.64200000, 30.09800000, 1),
(739, 106, 'Rugembe', -2.64100000, 30.09900000, 1),
(740, 107, 'Quartier Gasekebuye', -2.64000000, 30.10000000, 1),
(741, 107, 'Quartier Gitaramuka', -2.63900000, 30.10100000, 1),
(742, 107, 'Quartier Kamesa', -2.63800000, 30.10200000, 1),
(743, 107, 'Quartier Kinanira', -2.63700000, 30.10300000, 1),
(744, 107, 'Quartier Mpimba', -2.63600000, 30.10400000, 1),
(745, 107, 'Quartier Muzenga', -2.63500000, 30.10500000, 1),
(746, 107, 'Quartier Urumuri', -2.63400000, 30.10600000, 1),
(747, 108, 'Gakungwe', -2.63300000, 30.10700000, 1),
(748, 108, 'Kiremba', -2.63200000, 30.10800000, 1),
(749, 108, 'Mena', -2.63100000, 30.10900000, 1),
(750, 108, 'Ramba', -2.63000000, 30.11000000, 1),
(751, 109, 'Bigwa', -2.62900000, 30.11100000, 1),
(752, 109, 'Buhonga', -2.62800000, 30.11200000, 1),
(753, 109, 'Buzige', -2.62700000, 30.11300000, 1),
(754, 109, 'Kabumba', -2.62600000, 30.11400000, 1),
(755, 109, 'Mboza', -2.62500000, 30.11500000, 1),
(756, 109, 'Mwico', -2.62400000, 30.11600000, 1),
(757, 109, 'Ruyaga', -2.62300000, 30.11700000, 1),
(758, 110, 'Kizingwe', -2.62200000, 30.11800000, 1),
(759, 110, 'Mugere', -2.62100000, 30.11900000, 1),
(760, 110, 'Ngabwe', -2.62000000, 30.12000000, 1),
(761, 110, 'Nyabugete', -2.61900000, 30.12100000, 1),
(762, 110, 'Ruziba', -2.61800000, 30.12200000, 1),
(763, 111, 'Buhoro', -2.61700000, 30.12300000, 1),
(764, 111, 'Busesa', -2.61600000, 30.12400000, 1),
(765, 111, 'Mayuki', -2.61500000, 30.12500000, 1),
(766, 111, 'Mukaka', -2.61400000, 30.12600000, 1),
(767, 111, 'Nyarusebeyi', -2.61300000, 30.12700000, 1),
(768, 111, 'Rumvya', -2.61200000, 30.12800000, 1),
(769, 112, 'Gafumbegeti', -2.61100000, 30.12900000, 1),
(770, 112, 'Gahoma', -2.61000000, 30.13000000, 1),
(771, 112, 'Gakerekwa', -2.60900000, 30.13100000, 1),
(772, 112, 'Mageyo', -2.60800000, 30.13200000, 1),
(773, 112, 'Muhungo', -2.60700000, 30.13300000, 1),
(774, 112, 'Nyabungere', -2.60600000, 30.13400000, 1),
(775, 112, 'Rungogo', -2.60500000, 30.13500000, 1),
(776, 112, 'Rutorero', -2.60400000, 30.13600000, 1),
(777, 113, 'Gitukura', -2.60300000, 30.13700000, 1),
(778, 113, 'Kabere', -2.60200000, 30.13800000, 1),
(779, 113, 'Kibande', -2.60100000, 30.13900000, 1),
(780, 113, 'Mukoma', -2.60000000, 30.14000000, 1),
(781, 113, 'Nyagaseke', -2.59900000, 30.14100000, 1),
(782, 113, 'Rushiha', -2.59800000, 30.14200000, 1),
(783, 114, 'Butaramuka', -2.59700000, 30.14300000, 1),
(784, 114, 'Bwayi', -2.59600000, 30.14400000, 1),
(785, 114, 'Marumpu', -2.59500000, 30.14500000, 1),
(786, 114, 'Mugina', -2.59400000, 30.14600000, 1),
(787, 115, 'Gitumba', -2.59300000, 30.14700000, 1),
(788, 115, 'Nyamakarabo', -2.59200000, 30.14800000, 1),
(789, 115, 'Nyempundu', -2.59100000, 30.14900000, 1),
(790, 116, 'Gisumo', -2.59000000, 30.15000000, 1),
(791, 116, 'Kagurutsi', -2.58900000, 30.15100000, 1),
(792, 116, 'Ngoma', -2.58800000, 30.15200000, 1),
(793, 116, 'Nyamihana', -2.58700000, 30.15300000, 1),
(794, 116, 'Rubona', -2.58600000, 30.15400000, 1),
(795, 117, 'Gitebe', -2.58500000, 30.15500000, 1),
(796, 117, 'Kirinzi', -2.58400000, 30.15600000, 1),
(797, 117, 'Muyange', -2.58300000, 30.15700000, 1),
(798, 117, 'Mwarangabo', -2.58200000, 30.15800000, 1),
(799, 117, 'Rubirizi', -2.58100000, 30.15900000, 1),
(800, 117, 'Rugajo', -2.58000000, 30.16000000, 1),
(801, 117, 'Rugendo', -2.57900000, 30.16100000, 1),
(802, 117, 'Rusagara', -2.57800000, 30.16200000, 1),
(803, 118, 'Gasebeyi', -2.57700000, 30.16300000, 1),
(804, 118, 'Miremera', -2.57600000, 30.16400000, 1),
(805, 118, 'Ruhororo', -2.57500000, 30.16500000, 1),
(806, 119, 'Camakombe', -2.57400000, 30.16600000, 1),
(807, 119, 'Rushima', -2.57300000, 30.16700000, 1),
(808, 119, 'Ruziba', -2.57200000, 30.16800000, 1),
(809, 120, 'Bugarama', -2.57100000, 30.16900000, 1),
(810, 120, 'Kagona', -2.57000000, 30.17000000, 1),
(811, 120, 'Kayombe', -2.56900000, 30.17100000, 1),
(812, 120, 'Nyabungere', -2.56800000, 30.17200000, 1),
(813, 121, 'Burazi', -2.56700000, 30.17300000, 1),
(814, 121, 'Busenge', -2.56600000, 30.17400000, 1),
(815, 121, 'Canda', -2.56500000, 30.17500000, 1),
(816, 121, 'Gasebeyi', -2.56400000, 30.17600000, 1),
(817, 121, 'Gihondo', -2.56300000, 30.17700000, 1),
(818, 121, 'Rubura', -2.56200000, 30.17800000, 1),
(819, 122, 'Gasange', -2.56100000, 30.17900000, 1),
(820, 122, 'Gitaza', -2.56000000, 30.18000000, 1),
(821, 122, 'Kibingo', -2.55900000, 30.18100000, 1),
(822, 122, 'Mubanga', -2.55800000, 30.18200000, 1),
(823, 122, 'Mubone', -2.55700000, 30.18300000, 1),
(824, 122, 'Rutunga', -2.55600000, 30.18400000, 1),
(825, 123, 'Burangwa', -2.55500000, 30.18500000, 1),
(826, 123, 'Cashi', -2.55400000, 30.18600000, 1),
(827, 123, 'Kazigo', -2.55300000, 30.18700000, 1),
(828, 123, 'Magara', -2.55200000, 30.18800000, 1),
(829, 123, 'Mugendo', -2.55100000, 30.18900000, 1),
(830, 123, 'Vuma', -2.55000000, 30.19000000, 1),
(831, 124, 'Buringa', -2.54900000, 30.19100000, 1),
(832, 124, 'Buyenzi', -2.54800000, 30.19200000, 1),
(833, 124, 'Gitunda', -2.54700000, 30.19300000, 1),
(834, 124, 'Kirombwe', -2.54600000, 30.19400000, 1),
(835, 124, 'Muhuta', -2.54500000, 30.19500000, 1),
(836, 125, 'Bambo', -2.54400000, 30.19600000, 1),
(837, 125, 'Mihororo', -2.54300000, 30.19700000, 1),
(838, 125, 'Nyaruyaga', -2.54200000, 30.19800000, 1),
(839, 125, 'Ruteme', -2.54100000, 30.19900000, 1),
(840, 126, 'Higiro', -2.54000000, 30.20000000, 1),
(841, 126, 'Kanzaganya', -2.53900000, 30.20100000, 1),
(842, 126, 'Kizuga', -2.53800000, 30.20200000, 1),
(843, 126, 'Masara', -2.53700000, 30.20300000, 1),
(844, 126, 'Murago', -2.53600000, 30.20400000, 1),
(845, 126, 'Rutongo', -2.53500000, 30.20500000, 1),
(846, 126, 'Ruyobera', -2.53400000, 30.20600000, 1),
(847, 126, 'Sakonga', -2.53300000, 30.20700000, 1),
(848, 127, 'Quartier Kanzigiri', -2.53200000, 30.20800000, 1),
(849, 127, 'Quartier Ruvumera', -2.53100000, 30.20900000, 1),
(850, 127, 'Quartier Swahili', -2.53000000, 30.21000000, 1),
(851, 128, 'Quartier Bwiza', -2.52900000, 30.21100000, 1),
(852, 128, 'Quartier Jabe', -2.52800000, 30.21200000, 1),
(853, 129, 'Quartier Kabondo', -2.52700000, 30.21300000, 1),
(854, 129, 'Quartier Kiriri', -2.52600000, 30.21400000, 1),
(855, 129, 'Quartier Mukaza', -2.52500000, 30.21500000, 1),
(856, 129, 'Quartier Ngendandumwe', -2.52400000, 30.21600000, 1),
(857, 129, 'Quartier Ratiro', -2.52300000, 30.21700000, 1),
(858, 129, 'Quartier Rohero', -2.52200000, 30.21800000, 1),
(859, 129, 'Quartier Rweza', -2.52100000, 30.21900000, 1),
(860, 130, 'Bigoma', -2.52000000, 30.22000000, 1),
(861, 130, 'Coga', -2.51900000, 30.22100000, 1),
(862, 130, 'Kavumu', -2.51800000, 30.22200000, 1),
(863, 130, 'Kirombwe', -2.51700000, 30.22300000, 1),
(864, 130, 'Mirama', -2.51600000, 30.22400000, 1),
(865, 130, 'Muyira', -2.51500000, 30.22500000, 1),
(866, 130, 'Rubizi', -2.51400000, 30.22600000, 1),
(867, 131, 'Quartier Mugoboka', -2.51300000, 30.22700000, 1),
(868, 131, 'Quartier Mutanga', -2.51200000, 30.22800000, 1),
(869, 131, 'Quartier Nyakabiga', -2.51100000, 30.22900000, 1),
(870, 131, 'Quartier Rumuri', -2.51000000, 30.23000000, 1),
(871, 132, 'Benga', -2.50900000, 30.23100000, 1),
(872, 132, 'Karunga', -2.50800000, 30.23200000, 1),
(873, 132, 'Kwigere', -2.50700000, 30.23300000, 1),
(874, 133, 'Quartier Bumwe', -2.50600000, 30.23400000, 1),
(875, 133, 'Quartier Kabusa', -2.50500000, 30.23500000, 1),
(876, 133, 'Quartier Kiyange', -2.50400000, 30.23600000, 1),
(877, 133, 'Quartier Mubone', -2.50300000, 30.23700000, 1),
(878, 133, 'Quartier Mugaruro', -2.50200000, 30.23800000, 1),
(879, 133, 'Quartier Muhuza', -2.50100000, 30.23900000, 1),
(880, 133, 'Quartier Mwezi', -2.50000000, 30.24000000, 1),
(881, 133, 'Quartier Mwizero', -2.49900000, 30.24100000, 1),
(882, 134, 'Quartier Buhuriro', -2.49800000, 30.24200000, 1),
(883, 134, 'Quartier Humuriza', -2.49700000, 30.24300000, 1),
(884, 134, 'Quartier Mahoro', -2.49600000, 30.24400000, 1),
(885, 134, 'Quartier Muhuza', -2.49500000, 30.24500000, 1),
(886, 134, 'Quartier Mutakura', -2.49400000, 30.24600000, 1),
(887, 134, 'Quartier Mwizero', -2.49300000, 30.24700000, 1),
(888, 134, 'Quartier Nyamwiza', -2.49200000, 30.24800000, 1),
(889, 135, 'Quartier Cuhiro', -2.49100000, 30.24900000, 1),
(890, 135, 'Quartier Gaharawe', -2.49000000, 30.25000000, 1),
(891, 135, 'Quartier Keza', -2.48900000, 30.25100000, 1),
(892, 135, 'Quartier Mahotera', -2.48800000, 30.25200000, 1),
(893, 135, 'Quartier Murinzi', -2.48700000, 30.25300000, 1),
(894, 135, 'Quartier Mushasha', -2.48600000, 30.25400000, 1),
(895, 135, 'Quartier Rusizi', -2.48500000, 30.25500000, 1),
(896, 135, 'Quartier Vugizo', -2.48400000, 30.25600000, 1),
(897, 135, 'Quartier Warubondo', -2.48300000, 30.25700000, 1),
(898, 136, 'Quartier Gitare', -2.48200000, 30.25800000, 1),
(899, 136, 'Quartier Huriro', -2.48100000, 30.25900000, 1),
(900, 136, 'Quartier Kigobe', -2.48000000, 30.26000000, 1),
(901, 136, 'Quartier Muyaga', -2.47900000, 30.26100000, 1),
(902, 136, 'Quartier Nyabagere', -2.47800000, 30.26200000, 1),
(903, 136, 'Quartier Sanganiro', -2.47700000, 30.26300000, 1),
(904, 136, 'Quartier Sangwe', -2.47600000, 30.26400000, 1),
(905, 136, 'Quartier Taba', -2.47500000, 30.26500000, 1),
(906, 136, 'Quartier Winterekwa', -2.47400000, 30.26600000, 1),
(907, 137, 'Quartier Gikizi', -2.47300000, 30.26700000, 1),
(908, 137, 'Quartier Gituro', -2.47200000, 30.26800000, 1),
(909, 137, 'Quartier Heha', -2.47100000, 30.26900000, 1),
(910, 137, 'Quartier Kavumu', -2.47000000, 30.27000000, 1),
(911, 137, 'Quartier Mirango', -2.46900000, 30.27100000, 1),
(912, 137, 'Quartier Mukozi', -2.46800000, 30.27200000, 1),
(913, 137, 'Quartier Songa', -2.46700000, 30.27300000, 1),
(914, 137, 'Quartier Teza', -2.46600000, 30.27400000, 1),
(915, 137, 'Quartier Twinyoni', -2.46500000, 30.27500000, 1),
(916, 137, 'Quartier Twizerane', -2.46400000, 30.27600000, 1),
(917, 138, 'Quartier Bubanza', -2.46300000, 30.27700000, 1),
(918, 138, 'Quartier Buhinyuza', -2.46200000, 30.27800000, 1),
(919, 138, 'Quartier Bururi', -2.46100000, 30.27900000, 1),
(920, 138, 'Quartier Carama', -2.46000000, 30.28000000, 1),
(921, 138, 'Quartier Gitega', -2.45900000, 30.28100000, 1),
(922, 138, 'Quartier Kanga', -2.45800000, 30.28200000, 1),
(923, 138, 'Quartier Muco', -2.45700000, 30.28300000, 1),
(924, 138, 'Quartier Muramvya', -2.45600000, 30.28400000, 1),
(925, 138, 'Quartier Mutaga', -2.45500000, 30.28500000, 1),
(926, 138, 'Quartier Muyinga', -2.45400000, 30.28600000, 1),
(927, 138, 'Quartier Ngozi', -2.45300000, 30.28700000, 1),
(928, 138, 'Quartier Ruyigi', -2.45200000, 30.28800000, 1),
(929, 139, 'Budahirwa', -2.45100000, 30.28900000, 1),
(930, 139, 'Bugoma', -2.45000000, 30.29000000, 1),
(931, 139, 'Kivogero', -2.44900000, 30.29100000, 1),
(932, 139, 'Mikangara', -2.44800000, 30.29200000, 1),
(933, 139, 'Nyaruhama', -2.44700000, 30.29300000, 1),
(934, 140, 'Quartier Bigwati', -2.44600000, 30.29400000, 1),
(935, 140, 'Quartier Buhomba', -2.44500000, 30.29500000, 1),
(936, 140, 'Quartier Masanganzira', -2.44400000, 30.29600000, 1),
(937, 140, 'Quartier Mugirigiri', -2.44300000, 30.29700000, 1),
(938, 140, 'Quartier Mutimbuzi', -2.44200000, 30.29800000, 1),
(939, 140, 'Quartier Muzazi', -2.44100000, 30.29900000, 1),
(940, 140, 'Quartier Samariro', -2.44000000, 30.30000000, 1),
(941, 141, 'Quartier Gateka', -2.43900000, 30.30100000, 1),
(942, 141, 'Quartier Mahinguriro', -2.43800000, 30.30200000, 1),
(943, 141, 'Quartier Majambere', -2.43700000, 30.30300000, 1),
(944, 141, 'Quartier Mubano', -2.43600000, 30.30400000, 1),
(945, 141, 'Quartier Mugisha', -2.43500000, 30.30500000, 1),
(946, 141, 'Quartier Muhinga', -2.43400000, 30.30600000, 1),
(947, 141, 'Quartier Muhizi', -2.43300000, 30.30700000, 1),
(948, 141, 'Quartier Ngagara', -2.43200000, 30.30800000, 1),
(949, 141, 'Quartier Rukundo', -2.43100000, 30.30900000, 1),
(950, 142, 'Gishingano', -2.43000000, 30.31000000, 1),
(951, 142, 'Nyakibande', -2.42900000, 30.31100000, 1),
(952, 142, 'Nyambuye', -2.42800000, 30.31200000, 1),
(953, 143, 'Gahahe', -2.42700000, 30.31300000, 1),
(954, 143, 'Gahwama', -2.42600000, 30.31400000, 1),
(955, 143, 'Gasenyi', -2.42500000, 30.31500000, 1),
(956, 143, 'Gatunguru', -2.42400000, 30.31600000, 1),
(957, 143, 'Karama', -2.42300000, 30.31700000, 1),
(958, 143, 'Muyange', -2.42200000, 30.31800000, 1),
(959, 143, 'Nyabunyegeri', -2.42100000, 30.31900000, 1),
(960, 143, 'Rubirizi', -2.42000000, 30.32000000, 1),
(961, 143, 'Tenga', -2.41900000, 30.32100000, 1),
(962, 144, 'Quartier Gakumbu', -2.41800000, 30.32200000, 1),
(963, 144, 'Quartier Kagera', -2.41700000, 30.32300000, 1),
(964, 144, 'Quartier Kajaga', -2.41600000, 30.32400000, 1),
(965, 144, 'Quartier Kinyinya', -2.41500000, 30.32500000, 1),
(966, 144, 'Quartier Ruvyagira', -2.41400000, 30.32600000, 1),
(967, 145, 'Bikanka', -2.41300000, 30.32700000, 1),
(968, 145, 'Kigozi', -2.41200000, 30.32800000, 1),
(969, 145, 'Ndayi', -2.41100000, 30.32900000, 1),
(970, 146, 'Buhoro', -2.41000000, 30.33000000, 1),
(971, 146, 'Jenda', -2.40900000, 30.33100000, 1),
(972, 146, 'Kanyunya', -2.40800000, 30.33200000, 1),
(973, 146, 'Mugoyi', -2.40700000, 30.33300000, 1),
(974, 146, 'Nyarushanga', -2.40600000, 30.33400000, 1),
(975, 146, 'Rwibaga', -2.40500000, 30.33500000, 1),
(976, 146, 'Vyuya', -2.40400000, 30.33600000, 1),
(977, 147, 'Kibira', -2.40300000, 30.33700000, 1),
(978, 147, 'Nyamugari', -2.40200000, 30.33800000, 1),
(979, 147, 'Rutambiro', -2.40100000, 30.33900000, 1),
(980, 148, 'Karama', -2.40000000, 30.34000000, 1),
(981, 148, 'Kigina', -2.39900000, 30.34100000, 1),
(982, 148, 'Kinama', -2.39800000, 30.34200000, 1),
(983, 148, 'Mugendo', -2.39700000, 30.34300000, 1),
(984, 149, 'Kizunga', -2.39600000, 30.34400000, 1),
(985, 149, 'Matara', -2.39500000, 30.34500000, 1),
(986, 149, 'Mukonko', -2.39400000, 30.34600000, 1),
(987, 149, 'Mwumba', -2.39300000, 30.34700000, 1),
(988, 150, 'Butagazwa', -2.39200000, 30.34800000, 1),
(989, 150, 'Gisarwe', -2.39100000, 30.34900000, 1),
(990, 150, 'Mugongo', -2.39000000, 30.35000000, 1);
INSERT INTO `collines` (`id_colline`, `id_zone`, `colline_name`, `latitude`, `longitude`, `est_actif`) VALUES
(991, 150, 'Murunga', -2.38900000, 30.35100000, 1),
(992, 151, 'Mayuyu', -2.38800000, 30.35200000, 1),
(993, 151, 'Nyarumanga', -2.38700000, 30.35300000, 1),
(994, 151, 'Rurambira', -2.38600000, 30.35400000, 1),
(995, 151, 'Ruzibazi', -2.38500000, 30.35500000, 1),
(996, 152, 'Gasarara', -2.38400000, 30.35600000, 1),
(997, 152, 'Mayemba', -2.38300000, 30.35700000, 1),
(998, 152, 'Mbare', -2.38200000, 30.35800000, 1),
(999, 152, 'Muhuha', -2.38100000, 30.35900000, 1),
(1000, 152, 'Nyabibondo', -2.38000000, 30.36000000, 1),
(1001, 153, 'Bubaji', -2.37900000, 30.36100000, 1),
(1002, 153, 'Kinyami', -2.37800000, 30.36200000, 1),
(1003, 153, 'Musenyi', -2.37700000, 30.36300000, 1),
(1004, 153, 'Nyabiraba', -2.37600000, 30.36400000, 1),
(1005, 153, 'Raro', -2.37500000, 30.36500000, 1),
(1006, 154, 'Mutobo', -2.37400000, 30.36600000, 1),
(1007, 154, 'Ruhororo', -2.37300000, 30.36700000, 1),
(1008, 154, 'Rukina', -2.37200000, 30.36800000, 1),
(1009, 155, 'Jungwe', -2.37100000, 30.36900000, 1),
(1010, 155, 'Kagwa', -2.37000000, 30.37000000, 1),
(1011, 155, 'Nyamiyaga', -2.36900000, 30.37100000, 1),
(1012, 155, 'Nyarwaga', -2.36800000, 30.37200000, 1),
(1013, 155, 'Ruvumu', -2.36700000, 30.37300000, 1),
(1014, 156, 'Bugeni', -2.36600000, 30.37400000, 1),
(1015, 156, 'Bwatemba', -2.36500000, 30.37500000, 1),
(1016, 156, 'Gihinga', -2.36400000, 30.37600000, 1),
(1017, 156, 'Gitwaro', -2.36300000, 30.37700000, 1),
(1018, 156, 'Mubuga', -2.36200000, 30.37800000, 1),
(1019, 156, 'Ntunda', -2.36100000, 30.37900000, 1),
(1020, 157, 'Gisanze', -2.36000000, 30.38000000, 1),
(1021, 157, 'Mahonda', -2.35900000, 30.38100000, 1),
(1022, 157, 'Mudahandwa', -2.35800000, 30.38200000, 1),
(1023, 157, 'Mugozi', -2.35700000, 30.38300000, 1),
(1024, 157, 'Murago', -2.35600000, 30.38400000, 1),
(1025, 157, 'Tongwe', -2.35500000, 30.38500000, 1),
(1026, 157, 'Quartier Bururi', -2.35400000, 30.38600000, 1),
(1027, 157, 'Quartier Kigwati', -2.35300000, 30.38700000, 1),
(1028, 157, 'Quartier Rumonyi', -2.35200000, 30.38800000, 1),
(1029, 158, 'Gikwazo', -2.35100000, 30.38900000, 1),
(1030, 158, 'Kagimbu', -2.35000000, 30.39000000, 1),
(1031, 158, 'Kivubo', -2.34900000, 30.39100000, 1),
(1032, 158, 'Nyabucokwe', -2.34800000, 30.39200000, 1),
(1033, 158, 'Rutoke', -2.34700000, 30.39300000, 1),
(1034, 158, 'Sanzu', -2.34600000, 30.39400000, 1),
(1035, 159, 'Burunga', -2.34500000, 30.39500000, 1),
(1036, 159, 'Kiganda', -2.34400000, 30.39600000, 1),
(1037, 159, 'Mubuga', -2.34300000, 30.39700000, 1),
(1038, 159, 'Rukanda', -2.34200000, 30.39800000, 1),
(1039, 160, 'Kabwayi', -2.34100000, 30.39900000, 1),
(1040, 160, 'Kigutu', -2.34000000, 30.40000000, 1),
(1041, 160, 'Kirungu', -2.33900000, 30.40100000, 1),
(1042, 160, 'Mirango', -2.33800000, 30.40200000, 1),
(1043, 160, 'Mushishi', -2.33700000, 30.40300000, 1),
(1044, 161, 'Gihanga', -2.33600000, 30.40400000, 1),
(1045, 161, 'Gikizi', -2.33500000, 30.40500000, 1),
(1046, 161, 'Kajondi', -2.33400000, 30.40600000, 1),
(1047, 161, 'Musenyi', -2.33300000, 30.40700000, 1),
(1048, 161, 'Ruringanizo', -2.33200000, 30.40800000, 1),
(1049, 162, 'Gikana', -2.33100000, 30.40900000, 1),
(1050, 162, 'Gitobo', -2.33000000, 30.41000000, 1),
(1051, 162, 'Muhweza', -2.32900000, 30.41100000, 1),
(1052, 162, 'Musongati', -2.32800000, 30.41200000, 1),
(1053, 162, 'Mwarusi', -2.32700000, 30.41300000, 1),
(1054, 163, 'Buhinga', -2.32600000, 30.41400000, 1),
(1055, 163, 'Burenza', -2.32500000, 30.41500000, 1),
(1056, 163, 'Gahago', -2.32400000, 30.41600000, 1),
(1057, 163, 'Gasenyi', -2.32300000, 30.41700000, 1),
(1058, 163, 'Gatanga', -2.32200000, 30.41800000, 1),
(1059, 163, 'Munini', -2.32100000, 30.41900000, 1),
(1060, 163, 'Muyange', -2.32000000, 30.42000000, 1),
(1061, 163, 'Nyarugera', -2.31900000, 30.42100000, 1),
(1062, 164, 'Burarana', -2.31800000, 30.42200000, 1),
(1063, 164, 'Muzima', -2.31700000, 30.42300000, 1),
(1064, 164, 'Nyavyamo', -2.31600000, 30.42400000, 1),
(1065, 164, 'Rushemeza', -2.31500000, 30.42500000, 1),
(1066, 164, 'Rwankona', -2.31400000, 30.42600000, 1),
(1067, 164, 'Quartier Kiremba', -2.31300000, 30.42700000, 1),
(1068, 165, 'Kijima', -2.31200000, 30.42800000, 1),
(1069, 165, 'Kinyonzo', -2.31100000, 30.42900000, 1),
(1070, 165, 'Munyinya', -2.31000000, 30.43000000, 1),
(1071, 165, 'Mutangaro', -2.30900000, 30.43100000, 1),
(1072, 165, 'Muzenga', -2.30800000, 30.43200000, 1),
(1073, 165, 'Ruhando', -2.30700000, 30.43300000, 1),
(1074, 166, 'Kagoma', -2.30600000, 30.43400000, 1),
(1075, 166, 'Karehe', -2.30500000, 30.43500000, 1),
(1076, 166, 'Karirimvya', -2.30400000, 30.43600000, 1),
(1077, 166, 'Migera', -2.30300000, 30.43700000, 1),
(1078, 166, 'Nyakabenga', -2.30200000, 30.43800000, 1),
(1079, 166, 'Rweza', -2.30100000, 30.43900000, 1),
(1080, 167, 'Bukeye', -2.30000000, 30.44000000, 1),
(1081, 167, 'Busoro', -2.29900000, 30.44100000, 1),
(1082, 167, 'Gikama', -2.29800000, 30.44200000, 1),
(1083, 167, 'Murambi', -2.29700000, 30.44300000, 1),
(1084, 167, 'Nyakazi', -2.29600000, 30.44400000, 1),
(1085, 167, 'Nyarubanga', -2.29500000, 30.44500000, 1),
(1086, 168, 'Bigina', -2.29400000, 30.44600000, 1),
(1087, 168, 'Gasenga', -2.29300000, 30.44700000, 1),
(1088, 168, 'Kibara', -2.29200000, 30.44800000, 1),
(1089, 168, 'Mayange', -2.29100000, 30.44900000, 1),
(1090, 168, 'Mudaturwa', -2.29000000, 30.45000000, 1),
(1091, 168, 'Mukingo', -2.28900000, 30.45100000, 1),
(1092, 168, 'Musasa', -2.28800000, 30.45200000, 1),
(1093, 168, 'Sampeke', -2.28700000, 30.45300000, 1),
(1094, 169, 'Gitaba', -2.28600000, 30.45400000, 1),
(1095, 169, 'Mugeregere', -2.28500000, 30.45500000, 1),
(1096, 169, 'Muguruka', -2.28400000, 30.45600000, 1),
(1097, 169, 'Nyantakara', -2.28300000, 30.45700000, 1),
(1098, 170, 'Canda', -2.28200000, 30.45800000, 1),
(1099, 170, 'Kirare', -2.28100000, 30.45900000, 1),
(1100, 170, 'Nyankara', -2.28000000, 30.46000000, 1),
(1101, 171, 'Dunga', -2.27900000, 30.46100000, 1),
(1102, 171, 'Kigaza', -2.27800000, 30.46200000, 1),
(1103, 171, 'Rusovu', -2.27700000, 30.46300000, 1),
(1104, 171, 'Shaka', -2.27600000, 30.46400000, 1),
(1105, 172, 'Buga', -2.27500000, 30.46500000, 1),
(1106, 172, 'Gatabo', -2.27400000, 30.46600000, 1),
(1107, 172, 'Nkaramanyenye', -2.27300000, 30.46700000, 1),
(1108, 172, 'Rutenderi', -2.27200000, 30.46800000, 1),
(1109, 173, 'Cunamwe', -2.27100000, 30.46900000, 1),
(1110, 173, 'Gitaba', -2.27000000, 30.47000000, 1),
(1111, 173, 'Karonge', -2.26900000, 30.47100000, 1),
(1112, 173, 'Mahembe', -2.26800000, 30.47200000, 1),
(1113, 173, 'Mpinga', -2.26700000, 30.47300000, 1),
(1114, 173, 'Mugutu', -2.26600000, 30.47400000, 1),
(1115, 173, 'Murambi', -2.26500000, 30.47500000, 1),
(1116, 173, 'Murango', -2.26400000, 30.47600000, 1),
(1117, 174, 'Gasaka', -2.26300000, 30.47700000, 1),
(1118, 174, 'Gisenyi', -2.26200000, 30.47800000, 1),
(1119, 174, 'Kanzege', -2.26100000, 30.47900000, 1),
(1120, 174, 'Munonotsi', -2.26000000, 30.48000000, 1),
(1121, 174, 'Musanga', -2.25900000, 30.48100000, 1),
(1122, 174, 'Muvumu', -2.25800000, 30.48200000, 1),
(1123, 174, 'Nyabangwe', -2.25700000, 30.48300000, 1),
(1124, 174, 'Rabiro', -2.25600000, 30.48400000, 1),
(1125, 174, 'Siza', -2.25500000, 30.48500000, 1),
(1126, 175, 'Gihero', -2.25400000, 30.48600000, 1),
(1127, 175, 'Kinoso', -2.25300000, 30.48700000, 1),
(1128, 175, 'Kizingoma', -2.25200000, 30.48800000, 1),
(1129, 175, 'Nyabigina', -2.25100000, 30.48900000, 1),
(1130, 176, 'Borera', -2.25000000, 30.49000000, 1),
(1131, 176, 'Butare', -2.24900000, 30.49100000, 1),
(1132, 176, 'Kabizi', -2.24800000, 30.49200000, 1),
(1133, 176, 'Kibimba', -2.24700000, 30.49300000, 1),
(1134, 176, 'Muyaga', -2.24600000, 30.49400000, 1),
(1135, 176, 'Quartier Buhirwe', -2.24500000, 30.49500000, 1),
(1136, 176, 'Quartier Gitaramuka', -2.24400000, 30.49600000, 1),
(1137, 176, 'Quartier Mushasha', -2.24300000, 30.49700000, 1),
(1138, 176, 'Quartier Swahili', -2.24200000, 30.49800000, 1),
(1139, 177, 'Jimbi', -2.24100000, 30.49900000, 1),
(1140, 177, 'Kibago', -2.24000000, 30.50000000, 1),
(1141, 177, 'Kivoga', -2.23900000, 30.50100000, 1),
(1142, 177, 'Mbizi', -2.23800000, 30.50200000, 1),
(1143, 177, 'Rubimba', -2.23700000, 30.50300000, 1),
(1144, 177, 'Ruyange', -2.23600000, 30.50400000, 1),
(1145, 178, 'Kabanga', -2.23500000, 30.50500000, 1),
(1146, 178, 'Kiyange', -2.23400000, 30.50600000, 1),
(1147, 178, 'Masaswe', -2.23300000, 30.50700000, 1),
(1148, 178, 'Migongo', -2.23200000, 30.50800000, 1),
(1149, 178, 'Nyabigina', -2.23100000, 30.50900000, 1),
(1150, 178, 'Nyarutuntu', -2.23000000, 30.51000000, 1),
(1151, 178, 'Rusunwe', -2.22900000, 30.51100000, 1),
(1152, 179, 'Kayoba', -2.22800000, 30.51200000, 1),
(1153, 179, 'Muresi', -2.22700000, 30.51300000, 1),
(1154, 179, 'Quartier Gitwa', -2.22600000, 30.51400000, 1),
(1155, 179, 'Quartier Kigarama', -2.22500000, 30.51500000, 1),
(1156, 179, 'Quartier Kigwati', -2.22400000, 30.51600000, 1),
(1157, 179, 'Quartier Kirama', -2.22300000, 30.51700000, 1),
(1158, 179, 'Quartier Mukenke', -2.22200000, 30.51800000, 1),
(1159, 179, 'Quartier Muyogo', -2.22100000, 30.51900000, 1),
(1160, 179, 'Quartier Nyaburumba', -2.22000000, 30.52000000, 1),
(1161, 179, 'Quartier Nyankoni', -2.21900000, 30.52100000, 1),
(1162, 179, 'Quartier Rugarama', -2.21800000, 30.52200000, 1),
(1163, 179, 'Quartier Rukanko', -2.21700000, 30.52300000, 1),
(1164, 179, 'Quartier Swahili', -2.21600000, 30.52400000, 1),
(1165, 180, 'Bitezi', -2.21500000, 30.52500000, 1),
(1166, 180, 'Gitanga', -2.21400000, 30.52600000, 1),
(1167, 180, 'Kinyinya', -2.21300000, 30.52700000, 1),
(1168, 180, 'Ntega', -2.21200000, 30.52800000, 1),
(1169, 180, 'Sakinyonga', -2.21100000, 30.52900000, 1),
(1170, 181, 'Butwe', -2.21000000, 30.53000000, 1),
(1171, 181, 'Gisarenda', -2.20900000, 30.53100000, 1),
(1172, 181, 'Mahango', -2.20800000, 30.53200000, 1),
(1173, 181, 'Ruzira', -2.20700000, 30.53300000, 1),
(1174, 182, 'Donge', -2.20600000, 30.53400000, 1),
(1175, 182, 'Kibezi', -2.20500000, 30.53500000, 1),
(1176, 182, 'Mpota', -2.20400000, 30.53600000, 1),
(1177, 182, 'Musho', -2.20300000, 30.53700000, 1),
(1178, 182, 'Mutobo', -2.20200000, 30.53800000, 1),
(1179, 183, 'Gakaranka', -2.20100000, 30.53900000, 1),
(1180, 183, 'Kivumu', -2.20000000, 30.54000000, 1),
(1181, 183, 'Munini', -2.19900000, 30.54100000, 1),
(1182, 184, 'Gahanda', -2.19800000, 30.54200000, 1),
(1183, 184, 'Kinwa', -2.19700000, 30.54300000, 1),
(1184, 184, 'Kiryama', -2.19600000, 30.54400000, 1),
(1185, 184, 'Mutsinda', -2.19500000, 30.54500000, 1),
(1186, 184, 'Rutundwe', -2.19400000, 30.54600000, 1),
(1187, 184, 'Rwego', -2.19300000, 30.54700000, 1),
(1188, 185, 'Kampezi', -2.19200000, 30.54800000, 1),
(1189, 185, 'Mahwa', -2.19100000, 30.54900000, 1),
(1190, 185, 'Ndava', -2.19000000, 30.55000000, 1),
(1191, 185, 'Nyabikenke', -2.18900000, 30.55100000, 1),
(1192, 186, 'Bihanga', -2.18800000, 30.55200000, 1),
(1193, 186, 'Gisisye', -2.18700000, 30.55300000, 1),
(1194, 186, 'Matana', -2.18600000, 30.55400000, 1),
(1195, 186, 'Mugano', -2.18500000, 30.55500000, 1),
(1196, 187, 'Muheka', -2.18400000, 30.55600000, 1),
(1197, 187, 'Musenyi', -2.18300000, 30.55700000, 1),
(1198, 187, 'Taba', -2.18200000, 30.55800000, 1),
(1199, 188, 'Burasira', -2.18100000, 30.55900000, 1),
(1200, 188, 'Coma', -2.18000000, 30.56000000, 1),
(1201, 188, 'Ntentamaza', -2.17900000, 30.56100000, 1),
(1202, 188, 'Nyatubuye', -2.17800000, 30.56200000, 1),
(1203, 188, 'Rukere', -2.17700000, 30.56300000, 1),
(1204, 188, 'Taba', -2.17600000, 30.56400000, 1),
(1205, 189, 'Gitara', -2.17500000, 30.56500000, 1),
(1206, 189, 'Gitaramuka', -2.17400000, 30.56600000, 1),
(1207, 189, 'Gozi', -2.17300000, 30.56700000, 1),
(1208, 189, 'Mwumba', -2.17200000, 30.56800000, 1),
(1209, 190, 'Jenda', -2.17100000, 30.56900000, 1),
(1210, 190, 'Ndago', -2.17000000, 30.57000000, 1),
(1211, 191, 'Mubira', -2.16900000, 30.57100000, 1),
(1212, 191, 'Ndengo', -2.16800000, 30.57200000, 1),
(1213, 191, 'Nyakigano', -2.16700000, 30.57300000, 1),
(1214, 191, 'Nyamugari', -2.16600000, 30.57400000, 1),
(1215, 191, 'Ruhinga', -2.16500000, 30.57500000, 1),
(1216, 191, 'Ruko', -2.16400000, 30.57600000, 1),
(1217, 192, 'Rusama', -2.16300000, 30.57700000, 1),
(1218, 192, 'Tara', -2.16200000, 30.57800000, 1),
(1219, 192, 'Yengero', -2.16100000, 30.57900000, 1),
(1220, 193, 'Kigabiro', -2.16000000, 30.58000000, 1),
(1221, 193, 'Muzamba', -2.15900000, 30.58100000, 1),
(1222, 193, 'Songa', -2.15800000, 30.58200000, 1),
(1223, 194, 'Gataka', -2.15700000, 30.58300000, 1),
(1224, 194, 'Kirinzi', -2.15600000, 30.58400000, 1),
(1225, 194, 'Mugomera', -2.15500000, 30.58500000, 1),
(1226, 194, 'Nyakimonyi', -2.15400000, 30.58600000, 1),
(1227, 194, 'Vyuya', -2.15300000, 30.58700000, 1),
(1228, 195, 'Buhogo', -2.15200000, 30.58800000, 1),
(1229, 195, 'Butezi', -2.15100000, 30.58900000, 1),
(1230, 195, 'Kabingo', -2.15000000, 30.59000000, 1),
(1231, 195, 'Kibimba', -2.14900000, 30.59100000, 1),
(1232, 195, 'Mutwana', -2.14800000, 30.59200000, 1),
(1233, 195, 'Mwebeya', -2.14700000, 30.59300000, 1),
(1234, 195, 'Nkanka', -2.14600000, 30.59400000, 1),
(1235, 195, 'Rubanga', -2.14500000, 30.59500000, 1),
(1236, 195, 'Shasha', -2.14400000, 30.59600000, 1),
(1237, 196, 'Gakungu', -2.14300000, 30.59700000, 1),
(1238, 196, 'Mugombwa', -2.14200000, 30.59800000, 1),
(1239, 196, 'Murara', -2.14100000, 30.59900000, 1),
(1240, 196, 'Murembera', -2.14000000, 30.60000000, 1),
(1241, 196, 'Mwango', -2.13900000, 30.60100000, 1),
(1242, 196, 'Ngomante', -2.13800000, 30.60200000, 1),
(1243, 196, 'Nyabakara', -2.13700000, 30.60300000, 1),
(1244, 196, 'Nyamateke', -2.13600000, 30.60400000, 1),
(1245, 197, 'Gatakazi', -2.13500000, 30.60500000, 1),
(1246, 197, 'Kagunga', -2.13400000, 30.60600000, 1),
(1247, 197, 'Maganahe', -2.13300000, 30.60700000, 1),
(1248, 197, 'Rusunu', -2.13200000, 30.60800000, 1),
(1249, 197, 'Yove', -2.13100000, 30.60900000, 1),
(1250, 198, 'Bayaga', -2.13000000, 30.61000000, 1),
(1251, 198, 'Bukeno', -2.12900000, 30.61100000, 1),
(1252, 198, 'Giharo', -2.12800000, 30.61200000, 1),
(1253, 198, 'Gitanga', -2.12700000, 30.61300000, 1),
(1254, 198, 'Kanyererwe', -2.12600000, 30.61400000, 1),
(1255, 198, 'Mura', -2.12500000, 30.61500000, 1),
(1256, 198, 'Musenyi', -2.12400000, 30.61600000, 1),
(1257, 198, 'Nkurye', -2.12300000, 30.61700000, 1),
(1258, 199, 'Gihera', -2.12200000, 30.61800000, 1),
(1259, 199, 'Gitaba', -2.12100000, 30.61900000, 1),
(1260, 199, 'Juragati', -2.12000000, 30.62000000, 1),
(1261, 199, 'Kayove', -2.11900000, 30.62100000, 1),
(1262, 199, 'Munyika', -2.11800000, 30.62200000, 1),
(1263, 199, 'Musotera', -2.11700000, 30.62300000, 1),
(1264, 199, 'Ngarama', -2.11600000, 30.62400000, 1),
(1265, 199, 'Nyamiyaga', -2.11500000, 30.62500000, 1),
(1266, 199, 'Rorero', -2.11400000, 30.62600000, 1),
(1267, 200, 'Buranga', -2.11300000, 30.62700000, 1),
(1268, 200, 'Gasasa', -2.11200000, 30.62800000, 1),
(1269, 200, 'Kiguhu', -2.11100000, 30.62900000, 1),
(1270, 200, 'Mbuye', -2.11000000, 30.63000000, 1),
(1271, 200, 'Mihama', -2.10900000, 30.63100000, 1),
(1272, 200, 'Nyakabanda', -2.10800000, 30.63200000, 1),
(1273, 200, 'Nyakazu', -2.10700000, 30.63300000, 1),
(1274, 201, 'Butambara', -2.10600000, 30.63400000, 1),
(1275, 201, 'Butamya', -2.10500000, 30.63500000, 1),
(1276, 201, 'Gihinga', -2.10400000, 30.63600000, 1),
(1277, 201, 'Kagoma', -2.10300000, 30.63700000, 1),
(1278, 201, 'Kibanda', -2.10200000, 30.63800000, 1),
(1279, 201, 'Kigamba', -2.10100000, 30.63900000, 1),
(1280, 201, 'Maganahe', -2.10000000, 30.64000000, 1),
(1281, 201, 'Mpinga', -2.09900000, 30.64100000, 1),
(1282, 201, 'Muganza', -2.09800000, 30.64200000, 1),
(1283, 202, 'Bayumbu', -2.09700000, 30.64300000, 1),
(1284, 202, 'Gasenga', -2.09600000, 30.64400000, 1),
(1285, 202, 'Gasozi', -2.09500000, 30.64500000, 1),
(1286, 202, 'Mugondo', -2.09400000, 30.64600000, 1),
(1287, 202, 'Ngara', -2.09300000, 30.64700000, 1),
(1288, 202, 'Ntonzi', -2.09200000, 30.64800000, 1),
(1289, 202, 'Rasa', -2.09100000, 30.64900000, 1),
(1290, 202, 'Rutoke', -2.09000000, 30.65000000, 1),
(1291, 203, 'Buhinga', -2.08900000, 30.65100000, 1),
(1292, 203, 'Giheta', -2.08800000, 30.65200000, 1),
(1293, 203, 'Kamaramagambo', -2.08700000, 30.65300000, 1),
(1294, 203, 'Nyabigozi', -2.08600000, 30.65400000, 1),
(1295, 203, 'Nyabisindu', -2.08500000, 30.65500000, 1),
(1296, 203, 'Runyoni', -2.08400000, 30.65600000, 1),
(1297, 204, 'Buyaga', -2.08300000, 30.65700000, 1),
(1298, 204, 'Gatonga', -2.08200000, 30.65800000, 1),
(1299, 204, 'Murehe', -2.08100000, 30.65900000, 1),
(1300, 204, 'Muzye', -2.08000000, 30.66000000, 1),
(1301, 204, 'Nyembuye', -2.07900000, 30.66100000, 1),
(1302, 204, 'Shembe', -2.07800000, 30.66200000, 1),
(1303, 205, 'Cero', -2.07700000, 30.66300000, 1),
(1304, 205, 'Makakwe', -2.07600000, 30.66400000, 1),
(1305, 205, 'Mungwa', -2.07500000, 30.66500000, 1),
(1306, 205, 'Munywero', -2.07400000, 30.66600000, 1),
(1307, 205, 'Musagara', -2.07300000, 30.66700000, 1),
(1308, 205, 'Ngoma', -2.07200000, 30.66800000, 1),
(1309, 205, 'Nyabibuye', -2.07100000, 30.66900000, 1),
(1310, 206, 'Gisasa', -2.07000000, 30.67000000, 1),
(1311, 206, 'Karera', -2.06900000, 30.67100000, 1),
(1312, 206, 'Mabawe', -2.06800000, 30.67200000, 1),
(1313, 206, 'Mbuza', -2.06700000, 30.67300000, 1),
(1314, 206, 'Nyangazi', -2.06600000, 30.67400000, 1),
(1315, 206, 'Nyanza', -2.06500000, 30.67500000, 1),
(1316, 206, 'Rugunga', -2.06400000, 30.67600000, 1),
(1317, 206, 'Shanga', -2.06300000, 30.67700000, 1),
(1318, 207, 'Jongwe', -2.06200000, 30.67800000, 1),
(1319, 207, 'Kiyazi', -2.06100000, 30.67900000, 1),
(1320, 207, 'Mutobo', -2.06000000, 30.68000000, 1),
(1321, 207, 'Nyarubano', -2.05900000, 30.68100000, 1),
(1322, 207, 'Rurambira', -2.05800000, 30.68200000, 1),
(1323, 207, 'Rutegama', -2.05700000, 30.68300000, 1),
(1324, 208, 'Bikobe', -2.05600000, 30.68400000, 1),
(1325, 208, 'Budaketwa', -2.05500000, 30.68500000, 1),
(1326, 208, 'Musenyi', -2.05400000, 30.68600000, 1),
(1327, 208, 'Nyamugari', -2.05300000, 30.68700000, 1),
(1328, 208, 'Ruvuga', -2.05200000, 30.68800000, 1),
(1329, 209, 'Gasaba', -2.05100000, 30.68900000, 1),
(1330, 209, 'Gisenga', -2.05000000, 30.69000000, 1),
(1331, 209, 'Kabonga', -2.04900000, 30.69100000, 1),
(1332, 209, 'Mukerezi', -2.04800000, 30.69200000, 1),
(1333, 209, 'Nyabigina', -2.04700000, 30.69300000, 1),
(1334, 210, 'Bukunda', -2.04600000, 30.69400000, 1),
(1335, 210, 'Burima', -2.04500000, 30.69500000, 1),
(1336, 210, 'Karinzi', -2.04400000, 30.69600000, 1),
(1337, 210, 'Kigamba', -2.04300000, 30.69700000, 1),
(1338, 210, 'Mivo', -2.04200000, 30.69800000, 1),
(1339, 210, 'Mubondo', -2.04100000, 30.69900000, 1),
(1340, 210, 'Nyabitabo', -2.04000000, 30.70000000, 1),
(1341, 211, 'Gahandu', -2.03900000, 30.70100000, 1),
(1342, 211, 'Gikuzi', -2.03800000, 30.70200000, 1),
(1343, 211, 'Martyazo', -2.03700000, 30.70300000, 1),
(1344, 211, 'Mazuru', -2.03600000, 30.70400000, 1),
(1345, 211, 'Murinda', -2.03500000, 30.70500000, 1),
(1346, 211, 'Ndoba', -2.03400000, 30.70600000, 1),
(1347, 212, 'Biniganyi', -2.03300000, 30.70700000, 1),
(1348, 212, 'Buheka', -2.03200000, 30.70800000, 1),
(1349, 212, 'Kazirabageni', -2.03100000, 30.70900000, 1),
(1350, 212, 'Kiderege', -2.03000000, 30.71000000, 1),
(1351, 213, 'Gikombe', -2.02900000, 30.71100000, 1),
(1352, 213, 'Gikurazo', -2.02800000, 30.71200000, 1),
(1353, 213, 'Kibimba', -2.02700000, 30.71300000, 1),
(1354, 213, 'Mabanda', -2.02600000, 30.71400000, 1),
(1355, 213, 'Mara', -2.02500000, 30.71500000, 1),
(1356, 213, 'Mutwazi', -2.02400000, 30.71600000, 1),
(1357, 213, 'Nkondo', -2.02300000, 30.71700000, 1),
(1358, 213, 'Quartier Mudaturwa', -2.02200000, 30.71800000, 1),
(1359, 213, 'Quartier Mushasha', -2.02100000, 30.71900000, 1),
(1360, 213, 'Quartier Nyabusunzu', -2.02000000, 30.72000000, 1),
(1361, 213, 'Quartier Sagara', -2.01900000, 30.72100000, 1),
(1362, 213, 'Quartier Samvura', -2.01800000, 30.72200000, 1),
(1363, 214, 'Gitaba', -2.01700000, 30.72300000, 1),
(1364, 214, 'Kagege', -2.01600000, 30.72400000, 1),
(1365, 214, 'Kigamba', -2.01500000, 30.72500000, 1),
(1366, 214, 'Mudende', -2.01400000, 30.72600000, 1),
(1367, 214, 'Rubanda', -2.01300000, 30.72700000, 1),
(1368, 215, 'Kabo', -2.01200000, 30.72800000, 1),
(1369, 215, 'Mugumure', -2.01100000, 30.72900000, 1),
(1370, 215, 'Mukubano', -2.01000000, 30.73000000, 1),
(1371, 215, 'Ruvumera', -2.00900000, 30.73100000, 1),
(1372, 216, 'Mukimba', -2.00800000, 30.73200000, 1),
(1373, 216, 'Mukungu', -2.00700000, 30.73300000, 1),
(1374, 216, 'Rimbo', -2.00600000, 30.73400000, 1),
(1375, 216, 'Rubindi', -2.00500000, 30.73500000, 1),
(1376, 217, 'Muyange', -2.00400000, 30.73600000, 1),
(1377, 217, 'Mwimbiro', -2.00300000, 30.73700000, 1),
(1378, 217, 'Nyabutare', -2.00200000, 30.73800000, 1),
(1379, 217, 'Rangi', -2.00100000, 30.73900000, 1),
(1380, 217, 'Ruvyagira', -2.00000000, 30.74000000, 1),
(1381, 218, 'Bukeye', -1.99900000, 30.74100000, 1),
(1382, 218, 'Kabondo', -1.99800000, 30.74200000, 1),
(1383, 218, 'Mugerama', -1.99700000, 30.74300000, 1),
(1384, 218, 'Mvugo', -1.99600000, 30.74400000, 1),
(1385, 218, 'Quartier Bogorwa', -1.99500000, 30.74500000, 1),
(1386, 218, 'Quartier Bukeye', -1.99400000, 30.74600000, 1),
(1387, 218, 'Quartier Gitunda', -1.99300000, 30.74700000, 1),
(1388, 218, 'Quartier Isanganiro', -1.99200000, 30.74800000, 1),
(1389, 218, 'Quartier Kigembezi', -1.99100000, 30.74900000, 1),
(1390, 218, 'Quartier Ngoro', -1.99000000, 30.75000000, 1),
(1391, 218, 'Quartier Nyamirongo', -1.98900000, 30.75100000, 1),
(1392, 218, 'Quartier Swahili', -1.98800000, 30.75200000, 1),
(1393, 219, 'Higiro', -1.98700000, 30.75300000, 1),
(1394, 219, 'Karonge', -1.98600000, 30.75400000, 1),
(1395, 219, 'Kigombe', -1.98500000, 30.75500000, 1),
(1396, 219, 'Mbizi', -1.98400000, 30.75600000, 1),
(1397, 219, 'Mugu', -1.98300000, 30.75700000, 1),
(1398, 219, 'Nyamirinzi', -1.98200000, 30.75800000, 1),
(1399, 219, 'Rabiro', -1.98100000, 30.75900000, 1),
(1400, 220, 'Gitwe', -1.98000000, 30.76000000, 1),
(1401, 220, 'Karagara', -1.97900000, 30.76100000, 1),
(1402, 220, 'Muhanda', -1.97800000, 30.76200000, 1),
(1403, 220, 'Murambi', -1.97700000, 30.76300000, 1),
(1404, 220, 'Nyagasaka', -1.97600000, 30.76400000, 1),
(1405, 221, 'Bisaka', -1.97500000, 30.76500000, 1),
(1406, 221, 'Busaga', -1.97400000, 30.76600000, 1),
(1407, 221, 'Gahinda', -1.97300000, 30.76700000, 1),
(1408, 221, 'Gitaramuka', -1.97200000, 30.76800000, 1),
(1409, 221, 'Rwaniro', -1.97100000, 30.76900000, 1),
(1410, 222, 'Kanyinya', -1.97000000, 30.77000000, 1),
(1411, 222, 'Mabanza', -1.96900000, 30.77100000, 1),
(1412, 222, 'Mujigo', -1.96800000, 30.77200000, 1),
(1413, 222, 'Nkizi', -1.96700000, 30.77300000, 1),
(1414, 222, 'Runyinya', -1.96600000, 30.77400000, 1),
(1415, 223, 'Busebwa', -1.96500000, 30.77500000, 1),
(1416, 223, 'Gatete', -1.96400000, 30.77600000, 1),
(1417, 223, 'Mugara', -1.96300000, 30.77700000, 1),
(1418, 223, 'Mutambara', -1.96200000, 30.77800000, 1),
(1419, 224, 'Cabara', -1.96100000, 30.77900000, 1),
(1420, 224, 'Gashasha', -1.96000000, 30.78000000, 1),
(1421, 224, 'Kanenge', -1.95900000, 30.78100000, 1),
(1422, 224, 'Mayengo', -1.95800000, 30.78200000, 1),
(1423, 224, 'Nyakuguma', -1.95700000, 30.78300000, 1),
(1424, 225, 'Gatwe', -1.95600000, 30.78400000, 1),
(1425, 225, 'Kagongo', -1.95500000, 30.78500000, 1),
(1426, 225, 'Kizuka', -1.95400000, 30.78600000, 1),
(1427, 225, 'Mibanda', -1.95300000, 30.78700000, 1),
(1428, 225, 'Mwange', -1.95200000, 30.78800000, 1),
(1429, 226, 'Busura', -1.95100000, 30.78900000, 1),
(1430, 226, 'Gisenyi', -1.95000000, 30.79000000, 1),
(1431, 226, 'Gishiha', -1.94900000, 30.79100000, 1),
(1432, 226, 'Magana', -1.94800000, 30.79200000, 1),
(1433, 226, 'Maramvya', -1.94700000, 30.79300000, 1),
(1434, 226, 'Muzi', -1.94600000, 30.79400000, 1),
(1435, 227, 'Buhinyuza', -1.94500000, 30.79500000, 1),
(1436, 227, 'Buyenzi', -1.94400000, 30.79600000, 1),
(1437, 227, 'Rumonyi', -1.94300000, 30.79700000, 1),
(1438, 227, 'Rutwenzi', -1.94200000, 30.79800000, 1),
(1439, 228, 'Karonke', -1.94100000, 30.79900000, 1),
(1440, 228, 'Minago', -1.94000000, 30.80000000, 1),
(1441, 228, 'Muhuzu', -1.93900000, 30.80100000, 1),
(1442, 228, 'Muturirwa', -1.93800000, 30.80200000, 1),
(1443, 228, 'Rutumo', -1.93700000, 30.80300000, 1),
(1444, 229, 'Gasenyi', -1.93600000, 30.80400000, 1),
(1445, 229, 'Kinama', -1.93500000, 30.80500000, 1),
(1446, 229, 'Kirama', -1.93400000, 30.80600000, 1),
(1447, 229, 'Mudende', -1.93300000, 30.80700000, 1),
(1448, 229, 'Sebeyi', -1.93200000, 30.80800000, 1),
(1449, 230, 'Banda', -1.93100000, 30.80900000, 1),
(1450, 230, 'Gitsinda', -1.93000000, 30.81000000, 1),
(1451, 230, 'Karambi', -1.92900000, 30.81100000, 1),
(1452, 230, 'Nyacambuko', -1.92800000, 30.81200000, 1),
(1453, 230, 'Nyamurunga', -1.92700000, 30.81300000, 1),
(1454, 230, 'Rubirizi', -1.92600000, 30.81400000, 1),
(1455, 231, 'Quartier Birimba', -1.92500000, 30.81500000, 1),
(1456, 231, 'Quartier Gihwanya', -1.92400000, 30.81600000, 1),
(1457, 231, 'Quartier Iteba', -1.92300000, 30.81700000, 1),
(1458, 231, 'Quartier Kanyenkoko', -1.92200000, 30.81800000, 1),
(1459, 231, 'Quartier Mugomere', -1.92100000, 30.81900000, 1),
(1460, 231, 'Quartier Nkayamba', -1.92000000, 30.82000000, 1),
(1461, 231, 'Quartier Rukinga', -1.91900000, 30.82100000, 1),
(1462, 231, 'Quartier Swahili', -1.91800000, 30.82200000, 1),
(1463, 232, 'Gakonko', -1.91700000, 30.82300000, 1),
(1464, 232, 'Gatobo', -1.91600000, 30.82400000, 1),
(1465, 232, 'Gitaba', -1.91500000, 30.82500000, 1),
(1466, 232, 'Gitongwe', -1.91400000, 30.82600000, 1),
(1467, 232, 'Murara', -1.91300000, 30.82700000, 1),
(1468, 232, 'Murenge', -1.91200000, 30.82800000, 1),
(1469, 233, 'Bukemba', -1.91100000, 30.82900000, 1),
(1470, 233, 'Gihofi', -1.91000000, 30.83000000, 1),
(1471, 233, 'Kabanga', -1.90900000, 30.83100000, 1),
(1472, 233, 'Murama', -1.90800000, 30.83200000, 1),
(1473, 234, 'Bugiga', -1.90700000, 30.83300000, 1),
(1474, 234, 'Butare', -1.90600000, 30.83400000, 1),
(1475, 234, 'Muyombwe', -1.90500000, 30.83500000, 1),
(1476, 234, 'Rubanga', -1.90400000, 30.83600000, 1),
(1477, 234, 'Ruranga', -1.90300000, 30.83700000, 1),
(1478, 235, 'Bugunga', -1.90200000, 30.83800000, 1),
(1479, 235, 'Buta', -1.90100000, 30.83900000, 1),
(1480, 235, 'Gasakuza', -1.90000000, 30.84000000, 1),
(1481, 235, 'Gaterama', -1.89900000, 30.84100000, 1),
(1482, 235, 'Gatongati', -1.89800000, 30.84200000, 1),
(1483, 235, 'Gitaba', -1.89700000, 30.84300000, 1),
(1484, 235, 'Kinganda', -1.89600000, 30.84400000, 1),
(1485, 235, 'Maramvya', -1.89500000, 30.84500000, 1),
(1486, 235, 'Mika', -1.89400000, 30.84600000, 1),
(1487, 236, 'Burara', -1.89300000, 30.84700000, 1),
(1488, 236, 'Gatare', -1.89200000, 30.84800000, 1),
(1489, 236, 'Gatete', -1.89100000, 30.84900000, 1),
(1490, 236, 'Kigoma', -1.89000000, 30.85000000, 1),
(1491, 236, 'Kivo', -1.88900000, 30.85100000, 1),
(1492, 236, 'Munazi', -1.88800000, 30.85200000, 1),
(1493, 236, 'Nyakizu', -1.88700000, 30.85300000, 1),
(1494, 237, 'Budahunga', -1.88600000, 30.85400000, 1),
(1495, 237, 'Butegana', -1.88500000, 30.85500000, 1),
(1496, 237, 'Kabirizi', -1.88400000, 30.85600000, 1),
(1497, 237, 'Kabuyenge', -1.88300000, 30.85700000, 1),
(1498, 237, 'Mukenke', -1.88200000, 30.85800000, 1),
(1499, 238, 'Burwana', -1.88100000, 30.85900000, 1),
(1500, 238, 'Cumba', -1.88000000, 30.86000000, 1),
(1501, 238, 'Nkomero', -1.87900000, 30.86100000, 1),
(1502, 238, 'Tonga', -1.87800000, 30.86200000, 1),
(1503, 239, 'Gisenyi', -1.87700000, 30.86300000, 1),
(1504, 239, 'Higiro', -1.87600000, 30.86400000, 1),
(1505, 239, 'Kibonde', -1.87500000, 30.86500000, 1),
(1506, 239, 'Marembo', -1.87400000, 30.86600000, 1),
(1507, 239, 'Muhuzu', -1.87300000, 30.86700000, 1),
(1508, 239, 'Murambi', -1.87200000, 30.86800000, 1),
(1509, 239, 'Nyabisindu', -1.87100000, 30.86900000, 1),
(1510, 239, 'Rugarama', -1.87000000, 30.87000000, 1),
(1511, 239, 'Rwibikara', -1.86900000, 30.87100000, 1),
(1512, 240, 'Butahana', -1.86800000, 30.87200000, 1),
(1513, 240, 'Butihinda', -1.86700000, 30.87300000, 1),
(1514, 240, 'Gahosha', -1.86600000, 30.87400000, 1),
(1515, 240, 'Gasuga', -1.86500000, 30.87500000, 1),
(1516, 240, 'Rungazi', -1.86400000, 30.87600000, 1),
(1517, 241, 'Buhimba', -1.86300000, 30.87700000, 1),
(1518, 241, 'Buringa', -1.86200000, 30.87800000, 1),
(1519, 241, 'Kabanga', -1.86100000, 30.87900000, 1),
(1520, 241, 'Karambo', -1.86000000, 30.88000000, 1),
(1521, 241, 'Kididiri', -1.85900000, 30.88100000, 1),
(1522, 241, 'Rurende', -1.85800000, 30.88200000, 1),
(1523, 242, 'Gasave', -1.85700000, 30.88300000, 1),
(1524, 242, 'Kibazi', -1.85600000, 30.88400000, 1),
(1525, 242, 'Kimeza', -1.85500000, 30.88500000, 1),
(1526, 242, 'Mugongo', -1.85400000, 30.88600000, 1),
(1527, 242, 'Ruyenzi', -1.85300000, 30.88700000, 1),
(1528, 243, 'Kagege', -1.85200000, 30.88800000, 1),
(1529, 243, 'Kiravumba', -1.85100000, 30.88900000, 1),
(1530, 243, 'Mugobe', -1.85000000, 30.89000000, 1),
(1531, 243, 'Mukerwa', -1.84900000, 30.89100000, 1),
(1532, 243, 'Munyinya', -1.84800000, 30.89200000, 1),
(1533, 243, 'Renga', -1.84700000, 30.89300000, 1),
(1534, 243, 'Runyinya', -1.84600000, 30.89400000, 1),
(1535, 244, 'Gatemere', -1.84500000, 30.89500000, 1),
(1536, 244, 'Murore', -1.84400000, 30.89600000, 1),
(1537, 244, 'Nyabugeni', -1.84300000, 30.89700000, 1),
(1538, 244, 'Rutabo', -1.84200000, 30.89800000, 1),
(1539, 244, 'Ruyaga', -1.84100000, 30.89900000, 1),
(1540, 244, 'Rurira', -1.84000000, 30.90000000, 1),
(1541, 245, 'Gitete', -1.83900000, 30.90100000, 1),
(1542, 245, 'Kumana', -1.83800000, 30.90200000, 1),
(1543, 245, 'Muvyuko', -1.83700000, 30.90300000, 1),
(1544, 245, 'Muyange', -1.83600000, 30.90400000, 1),
(1545, 245, 'Nyagisozi', -1.83500000, 30.90500000, 1),
(1546, 245, 'Nyange', -1.83400000, 30.90600000, 1),
(1547, 245, 'Ruheha', -1.83300000, 30.90700000, 1),
(1548, 245, 'Sigu', -1.83200000, 30.90800000, 1),
(1549, 246, 'Mubuga', -1.83100000, 30.90900000, 1),
(1550, 246, 'Ngoma', -1.83000000, 30.91000000, 1),
(1551, 246, 'Nyenzi', -1.82900000, 30.91100000, 1),
(1552, 246, 'Ruhongore', -1.82800000, 30.91200000, 1),
(1553, 247, 'Bigombo', -1.82700000, 30.91300000, 1),
(1554, 247, 'Coga', -1.82600000, 30.91400000, 1),
(1555, 247, 'Santunda', -1.82500000, 30.91500000, 1),
(1556, 247, 'Shore', -1.82400000, 30.91600000, 1),
(1557, 248, 'Baziro', -1.82300000, 30.91700000, 1),
(1558, 248, 'Bucana', -1.82200000, 30.91800000, 1),
(1559, 248, 'Gihinga', -1.82100000, 30.91900000, 1),
(1560, 248, 'Kivumu', -1.82000000, 30.92000000, 1),
(1561, 248, 'Marembo', -1.81900000, 30.92100000, 1),
(1562, 248, 'Mirwa', -1.81800000, 30.92200000, 1),
(1563, 249, 'Bugorora', -1.81700000, 30.92300000, 1),
(1564, 249, 'Buhevyi', -1.81600000, 30.92400000, 1),
(1565, 249, 'Kaduduri', -1.81500000, 30.92500000, 1),
(1566, 249, 'Kibonobono', -1.81400000, 30.92600000, 1),
(1567, 249, 'Mutarishwa', -1.81300000, 30.92700000, 1),
(1568, 250, 'Buhoro', -1.81200000, 30.92800000, 1),
(1569, 250, 'Bunywera', -1.81100000, 30.92900000, 1),
(1570, 250, 'Karambo', -1.81000000, 30.93000000, 1),
(1571, 250, 'Minyago', -1.80900000, 30.93100000, 1),
(1572, 250, 'Rusara', -1.80800000, 30.93200000, 1),
(1573, 251, 'Burara', -1.80700000, 30.93300000, 1),
(1574, 251, 'Gatare', -1.80600000, 30.93400000, 1),
(1575, 251, 'Gatete', -1.80500000, 30.93500000, 1),
(1576, 251, 'Kigoma', -1.80400000, 30.93600000, 1),
(1577, 251, 'Kivo', -1.80300000, 30.93700000, 1),
(1578, 251, 'Munazi', -1.80200000, 30.93800000, 1),
(1579, 251, 'Nyakizu', -1.80100000, 30.93900000, 1),
(1580, 252, 'Budahunga', -1.80000000, 30.94000000, 1),
(1581, 252, 'Butegana', -1.79900000, 30.94100000, 1),
(1582, 252, 'Kabirizi', -1.79800000, 30.94200000, 1),
(1583, 252, 'Kabuyenge', -1.79700000, 30.94300000, 1),
(1584, 252, 'Mukenke', -1.79600000, 30.94400000, 1),
(1585, 253, 'Burwana', -1.79500000, 30.94500000, 1),
(1586, 253, 'Cumba', -1.79400000, 30.94600000, 1),
(1587, 253, 'Nkomero', -1.79300000, 30.94700000, 1),
(1588, 253, 'Tonga', -1.79200000, 30.94800000, 1),
(1589, 254, 'Gisenyi', -1.79100000, 30.94900000, 1),
(1590, 254, 'Higiro', -1.79000000, 30.95000000, 1),
(1591, 254, 'Kibonde', -1.78900000, 30.95100000, 1),
(1592, 254, 'Marembo', -1.78800000, 30.95200000, 1),
(1593, 254, 'Muhuzu', -1.78700000, 30.95300000, 1),
(1594, 254, 'Murambi', -1.78600000, 30.95400000, 1),
(1595, 254, 'Nyabisindu', -1.78500000, 30.95500000, 1),
(1596, 254, 'Rugarama', -1.78400000, 30.95600000, 1),
(1597, 254, 'Rwibikara', -1.78300000, 30.95700000, 1),
(1598, 255, 'Butahana', -1.78200000, 30.95800000, 1),
(1599, 255, 'Butihinda', -1.78100000, 30.95900000, 1),
(1600, 255, 'Gahosha', -1.78000000, 30.96000000, 1),
(1601, 255, 'Gasuga', -1.77900000, 30.96100000, 1),
(1602, 255, 'Rungazi', -1.77800000, 30.96200000, 1),
(1603, 256, 'Buhimba', -1.77700000, 30.96300000, 1),
(1604, 256, 'Buringa', -1.77600000, 30.96400000, 1),
(1605, 256, 'Kabanga', -1.77500000, 30.96500000, 1),
(1606, 256, 'Karambo', -1.77400000, 30.96600000, 1),
(1607, 256, 'Kididiri', -1.77300000, 30.96700000, 1),
(1608, 256, 'Rurende', -1.77200000, 30.96800000, 1),
(1609, 257, 'Gasave', -1.77100000, 30.96900000, 1),
(1610, 257, 'Kibazi', -1.77000000, 30.97000000, 1),
(1611, 257, 'Kimeza', -1.76900000, 30.97100000, 1),
(1612, 257, 'Mugongo', -1.76800000, 30.97200000, 1),
(1613, 257, 'Ruyenzi', -1.76700000, 30.97300000, 1),
(1614, 258, 'Kagege', -1.76600000, 30.97400000, 1),
(1615, 258, 'Kiravumba', -1.76500000, 30.97500000, 1),
(1616, 258, 'Mugobe', -1.76400000, 30.97600000, 1),
(1617, 258, 'Mukerwa', -1.76300000, 30.97700000, 1),
(1618, 258, 'Munyinya', -1.76200000, 30.97800000, 1),
(1619, 258, 'Renga', -1.76100000, 30.97900000, 1),
(1620, 258, 'Runyinya', -1.76000000, 30.98000000, 1),
(1621, 259, 'Gatemere', -1.75900000, 30.98100000, 1),
(1622, 259, 'Murore', -1.75800000, 30.98200000, 1),
(1623, 259, 'Nyabugeni', -1.75700000, 30.98300000, 1),
(1624, 259, 'Rutabo', -1.75600000, 30.98400000, 1),
(1625, 259, 'Ruyaga', -1.75500000, 30.98500000, 1),
(1626, 259, 'Rurira', -1.75400000, 30.98600000, 1),
(1627, 260, 'Gitete', -1.75300000, 30.98700000, 1),
(1628, 260, 'Kumana', -1.75200000, 30.98800000, 1),
(1629, 260, 'Muvyuko', -1.75100000, 30.98900000, 1),
(1630, 260, 'Muyange', -1.75000000, 30.99000000, 1),
(1631, 260, 'Nyagisozi', -1.74900000, 30.99100000, 1),
(1632, 260, 'Nyange', -1.74800000, 30.99200000, 1),
(1633, 260, 'Ruheha', -1.74700000, 30.99300000, 1),
(1634, 260, 'Sigu', -1.74600000, 30.99400000, 1),
(1635, 261, 'Mubuga', -1.74500000, 30.99500000, 1),
(1636, 261, 'Ngoma', -1.74400000, 30.99600000, 1),
(1637, 261, 'Nyenzi', -1.74300000, 30.99700000, 1),
(1638, 261, 'Ruhongore', -1.74200000, 30.99800000, 1),
(1639, 262, 'Bigombo', -1.74100000, 30.99900000, 1),
(1640, 262, 'Coga', -1.74000000, 31.00000000, 1),
(1641, 262, 'Santunda', -1.73900000, 31.00100000, 1),
(1642, 262, 'Shore', -1.73800000, 31.00200000, 1),
(1643, 263, 'Jene', -1.73700000, 31.00300000, 1),
(1644, 263, 'Kibuba', -1.73600000, 31.00400000, 1),
(1645, 263, 'Kigeri', -1.73500000, 31.00500000, 1),
(1646, 263, 'Mugoyi', -1.73400000, 31.00600000, 1),
(1647, 263, 'Mutana', -1.73300000, 31.00700000, 1),
(1648, 263, 'Ngoma', -1.73200000, 31.00800000, 1),
(1649, 263, 'Ruhinga', -1.73100000, 31.00900000, 1),
(1650, 263, 'Rusambi', -1.73000000, 31.01000000, 1),
(1651, 263, 'Ryamukona', -1.72900000, 31.01100000, 1),
(1652, 263, 'Songore', -1.72800000, 31.01200000, 1),
(1653, 263, 'Yandaro', -1.72700000, 31.01300000, 1),
(1654, 264, 'Caguka', -1.72600000, 31.01400000, 1),
(1655, 264, 'Gikingo', -1.72500000, 31.01500000, 1),
(1656, 264, 'Kidunduri', -1.72400000, 31.01600000, 1),
(1657, 264, 'Kirehe', -1.72300000, 31.01700000, 1),
(1658, 264, 'Kivuvu', -1.72200000, 31.01800000, 1),
(1659, 264, 'Mugera', -1.72100000, 31.01900000, 1),
(1660, 264, 'Mugongo', -1.72000000, 31.02000000, 1),
(1661, 264, 'Munege', -1.71900000, 31.02100000, 1),
(1662, 264, 'Nyamisagara', -1.71800000, 31.02200000, 1),
(1663, 265, 'Benga', -1.71700000, 31.02300000, 1),
(1664, 265, 'Kinyamukizi', -1.71600000, 31.02400000, 1),
(1665, 265, 'Kinzobe', -1.71500000, 31.02500000, 1),
(1666, 265, 'Maruri', -1.71400000, 31.02600000, 1),
(1667, 265, 'Migege', -1.71300000, 31.02700000, 1),
(1668, 265, 'Mpanga', -1.71200000, 31.02800000, 1),
(1669, 265, 'Ntarambo', -1.71100000, 31.02900000, 1),
(1670, 265, 'Nyabikaranka', -1.71000000, 31.03000000, 1),
(1671, 265, 'Nyangwe', -1.70900000, 31.03100000, 1),
(1672, 265, 'Ryirengeye', -1.70800000, 31.03200000, 1),
(1673, 265, 'Shikankoni', -1.70700000, 31.03300000, 1),
(1674, 266, 'Bubezi', -1.70600000, 31.03400000, 1),
(1675, 266, 'Cukiro', -1.70500000, 31.03500000, 1),
(1676, 266, 'Gahahe', -1.70400000, 31.03600000, 1),
(1677, 266, 'Karinzi', -1.70300000, 31.03700000, 1),
(1678, 266, 'Kinga', -1.70200000, 31.03800000, 1),
(1679, 266, 'Mihigo', -1.70100000, 31.03900000, 1),
(1680, 266, 'Murago', -1.70000000, 31.04000000, 1),
(1681, 266, 'Mwendo', -1.69900000, 31.04100000, 1),
(1682, 266, 'Nyabihanga', -1.69800000, 31.04200000, 1),
(1683, 266, 'Quartier Kirema', -1.69700000, 31.04300000, 1),
(1684, 266, 'Quartier Musave', -1.69600000, 31.04400000, 1),
(1685, 267, 'Bigera', -1.69500000, 31.04500000, 1),
(1686, 267, 'Cendajuru', -1.69400000, 31.04600000, 1),
(1687, 267, 'Gahini', -1.69300000, 31.04700000, 1),
(1688, 267, 'Gitemezi', -1.69200000, 31.04800000, 1),
(1689, 267, 'Kinyami', -1.69100000, 31.04900000, 1),
(1690, 267, 'Magana', -1.69000000, 31.05000000, 1),
(1691, 267, 'Makombe', -1.68900000, 31.05100000, 1),
(1692, 267, 'Mihama', -1.68800000, 31.05200000, 1),
(1693, 267, 'Mparamirundi', -1.68700000, 31.05300000, 1),
(1694, 267, 'Munyange', -1.68600000, 31.05400000, 1),
(1695, 267, 'Mutsinda', -1.68500000, 31.05500000, 1),
(1696, 267, 'Nyamisebo', -1.68400000, 31.05600000, 1),
(1697, 267, 'Tubiri', -1.68300000, 31.05700000, 1),
(1698, 268, 'Canzara', -1.68200000, 31.05800000, 1),
(1699, 268, 'Gacu', -1.68100000, 31.05900000, 1),
(1700, 268, 'Gihororo', -1.68000000, 31.06000000, 1),
(1701, 268, 'Kavumu', -1.67900000, 31.06100000, 1),
(1702, 268, 'Kibingo', -1.67800000, 31.06200000, 1),
(1703, 268, 'Magamba', -1.67700000, 31.06300000, 1),
(1704, 268, 'Murima', -1.67600000, 31.06400000, 1),
(1705, 268, 'Nkuba', -1.67500000, 31.06500000, 1),
(1706, 268, 'Ruvomo', -1.67400000, 31.06600000, 1),
(1707, 268, 'Rwintare', -1.67300000, 31.06700000, 1),
(1708, 269, 'Busambo', -1.67200000, 31.06800000, 1),
(1709, 269, 'Buziraguhindwa', -1.67100000, 31.06900000, 1),
(1710, 269, 'Karunyinya', -1.67000000, 31.07000000, 1),
(1711, 269, 'Kavoga', -1.66900000, 31.07100000, 1),
(1712, 269, 'Manini', -1.66800000, 31.07200000, 1),
(1713, 269, 'Muruta', -1.66700000, 31.07300000, 1),
(1714, 269, 'Myugariro', -1.66600000, 31.07400000, 1),
(1715, 269, 'Nyamiyogoro', -1.66500000, 31.07500000, 1),
(1716, 269, 'Remera', -1.66400000, 31.07600000, 1),
(1717, 269, 'Yanza', -1.66300000, 31.07700000, 1),
(1718, 270, 'Campazi', -1.66200000, 31.07800000, 1),
(1719, 270, 'Gishubi', -1.66100000, 31.07900000, 1),
(1720, 270, 'Mikuba', -1.66000000, 31.08000000, 1),
(1721, 270, 'Mutana', -1.65900000, 31.08100000, 1),
(1722, 270, 'Nkonge', -1.65800000, 31.08200000, 1),
(1723, 270, 'Nyakibari', -1.65700000, 31.08300000, 1),
(1724, 270, 'Ruvumu', -1.65600000, 31.08400000, 1),
(1725, 271, 'Gitwa', -1.65500000, 31.08500000, 1),
(1726, 271, 'Muhweza', -1.65400000, 31.08600000, 1),
(1727, 271, 'Nemba', -1.65300000, 31.08700000, 1),
(1728, 271, 'Nyabihogo', -1.65200000, 31.08800000, 1),
(1729, 271, 'Ruhande', -1.65100000, 31.08900000, 1),
(1730, 272, 'Buvumo', -1.65000000, 31.09000000, 1),
(1731, 272, 'Buyumpu', -1.64900000, 31.09100000, 1),
(1732, 272, 'Dusasa', -1.64800000, 31.09200000, 1),
(1733, 272, 'Gashiru', -1.64700000, 31.09300000, 1),
(1734, 272, 'Gisagara', -1.64600000, 31.09400000, 1),
(1735, 272, 'Kibati', -1.64500000, 31.09500000, 1),
(1736, 272, 'Tondero', -1.64400000, 31.09600000, 1),
(1737, 272, 'Yanza', -1.64300000, 31.09700000, 1),
(1738, 273, 'Caratsi', -1.64200000, 31.09800000, 1),
(1739, 273, 'Karama', -1.64100000, 31.09900000, 1),
(1740, 273, 'Manga', -1.64000000, 31.10000000, 1),
(1741, 273, 'Randa', -1.63900000, 31.10100000, 1),
(1742, 273, 'Rorero', -1.63800000, 31.10200000, 1),
(1743, 273, 'Ruhororo', -1.63700000, 31.10300000, 1),
(1744, 273, 'Rukere', -1.63600000, 31.10400000, 1),
(1745, 273, 'Runyinya', -1.63500000, 31.10500000, 1),
(1746, 273, 'Rutega', -1.63400000, 31.10600000, 1),
(1747, 274, 'Kaserege', -1.63300000, 31.10700000, 1),
(1748, 274, 'Kibakwe', -1.63200000, 31.10800000, 1),
(1749, 274, 'Kibaya', -1.63100000, 31.10900000, 1),
(1750, 274, 'Mpfunda', -1.63000000, 31.11000000, 1),
(1751, 274, 'Muganza', -1.62900000, 31.11100000, 1),
(1752, 274, 'Rwagongwe', -1.62800000, 31.11200000, 1),
(1753, 274, 'Rwegura', -1.62700000, 31.11300000, 1),
(1754, 275, 'Buhigiranka', -1.62600000, 31.11400000, 1),
(1755, 275, 'Gashingwe', -1.62500000, 31.11500000, 1),
(1756, 275, 'Gatwe', -1.62400000, 31.11600000, 1),
(1757, 275, 'Kigina', -1.62300000, 31.11700000, 1),
(1758, 275, 'Kinyovu', -1.62200000, 31.11800000, 1),
(1759, 275, 'Masama', -1.62100000, 31.11900000, 1),
(1760, 275, 'Martyazo', -1.62000000, 31.12000000, 1),
(1761, 275, 'Muremera', -1.61900000, 31.12100000, 1),
(1762, 275, 'Mushonge', -1.61800000, 31.12200000, 1),
(1763, 275, 'Rurama', -1.61700000, 31.12300000, 1),
(1764, 275, 'Shinge', -1.61600000, 31.12400000, 1),
(1765, 275, 'Shoza', -1.61500000, 31.12500000, 1),
(1766, 276, 'Bitagazwa', -1.61400000, 31.12600000, 1),
(1767, 276, 'Bunogera', -1.61300000, 31.12700000, 1),
(1768, 276, 'Kibuye', -1.61200000, 31.12800000, 1),
(1769, 276, 'Kidasha', -1.61100000, 31.12900000, 1),
(1770, 276, 'Kivoga', -1.61000000, 31.13000000, 1),
(1771, 276, 'Migongo', -1.60900000, 31.13100000, 1),
(1772, 276, 'Nkomero', -1.60800000, 31.13200000, 1),
(1773, 276, 'Nyabikenke', -1.60700000, 31.13300000, 1),
(1774, 276, 'Nyamarobe', -1.60600000, 31.13400000, 1),
(1775, 276, 'Ragwe', -1.60500000, 31.13500000, 1),
(1776, 277, 'Bisiga', -1.60400000, 31.13600000, 1),
(1777, 277, 'Kagoti', -1.60300000, 31.13700000, 1),
(1778, 277, 'Kidasha', -1.60200000, 31.13800000, 1),
(1779, 277, 'Kirungu', -1.60100000, 31.13900000, 1),
(1780, 277, 'Nyambo', -1.60000000, 31.14000000, 1),
(1781, 277, 'Rubaya', -1.59900000, 31.14100000, 1),
(1782, 277, 'Rusave', -1.59800000, 31.14200000, 1),
(1783, 278, 'Cayi', -1.59700000, 31.14300000, 1),
(1784, 278, 'Gakere', -1.59600000, 31.14400000, 1),
(1785, 278, 'Kibande', -1.59500000, 31.14500000, 1),
(1786, 278, 'Kibezi', -1.59400000, 31.14600000, 1),
(1787, 278, 'Kiremera', -1.59300000, 31.14700000, 1),
(1788, 278, 'Kiyange', -1.59200000, 31.14800000, 1),
(1789, 278, 'Munagano', -1.59100000, 31.14900000, 1),
(1790, 278, 'Ngeramigongo', -1.59000000, 31.15000000, 1),
(1791, 278, 'Ruvumu', -1.58900000, 31.15100000, 1),
(1792, 279, 'Bihangare', -1.58800000, 31.15200000, 1),
(1793, 279, 'Bitambwe', -1.58700000, 31.15300000, 1),
(1794, 279, 'Congori', -1.58600000, 31.15400000, 1),
(1795, 279, 'Gicumbi', -1.58500000, 31.15500000, 1),
(1796, 279, 'Kidobori', -1.58400000, 31.15600000, 1),
(1797, 279, 'Masama', -1.58300000, 31.15700000, 1),
(1798, 279, 'Muhuzo', -1.58200000, 31.15800000, 1),
(1799, 279, 'Nyamurenge', -1.58100000, 31.15900000, 1),
(1800, 280, 'Cagwa', -1.58000000, 31.16000000, 1),
(1801, 280, 'Gahororo', -1.57900000, 31.16100000, 1),
(1802, 280, 'Gatwaro', -1.57800000, 31.16200000, 1),
(1803, 280, 'Gisuka', -1.57700000, 31.16300000, 1),
(1804, 280, 'Gitaro', -1.57600000, 31.16400000, 1),
(1805, 280, 'Kiremba', -1.57500000, 31.16500000, 1),
(1806, 280, 'Masasu', -1.57400000, 31.16600000, 1),
(1807, 280, 'Mugerera', -1.57300000, 31.16700000, 1),
(1808, 280, 'Musanga', -1.57200000, 31.16800000, 1),
(1809, 280, 'Ruhama', -1.57100000, 31.16900000, 1),
(1810, 280, 'Ruyumpu', -1.57000000, 31.17000000, 1),
(1811, 280, 'Rwimbogo', -1.56900000, 31.17100000, 1),
(1812, 281, 'Burenge', -1.56800000, 31.17200000, 1),
(1813, 281, 'Gisekuro', -1.56700000, 31.17300000, 1),
(1814, 281, 'Kagina', -1.56600000, 31.17400000, 1),
(1815, 281, 'Kigoma', -1.56500000, 31.17500000, 1),
(1816, 281, 'Kigufi', -1.56400000, 31.17600000, 1),
(1817, 281, 'Kizenga', -1.56300000, 31.17700000, 1),
(1818, 281, 'Makaba', -1.56200000, 31.17800000, 1),
(1819, 281, 'Ndihwe', -1.56100000, 31.17900000, 1),
(1820, 281, 'Nyakibari', -1.56000000, 31.18000000, 1),
(1821, 281, 'Nyamugari', -1.55900000, 31.18100000, 1),
(1822, 281, 'Nyanza', -1.55800000, 31.18200000, 1),
(1823, 281, 'Ruramba', -1.55700000, 31.18300000, 1),
(1824, 282, 'Buhama', -1.55600000, 31.18400000, 1),
(1825, 282, 'Butare', -1.55500000, 31.18500000, 1),
(1826, 282, 'Canamo', -1.55400000, 31.18600000, 1),
(1827, 282, 'Kabanga', -1.55300000, 31.18700000, 1),
(1828, 282, 'Kabari', -1.55200000, 31.18800000, 1),
(1829, 282, 'Kagarama', -1.55100000, 31.18900000, 1),
(1830, 282, 'Masoro', -1.55000000, 31.19000000, 1),
(1831, 282, 'Mufigi', -1.54900000, 31.19100000, 1),
(1832, 282, 'Musasa', -1.54800000, 31.19200000, 1),
(1833, 282, 'Ruhata', -1.54700000, 31.19300000, 1),
(1834, 282, 'Rutobo', -1.54600000, 31.19400000, 1),
(1835, 283, 'Burenza', -1.54500000, 31.19500000, 1),
(1836, 283, 'Gikomero', -1.54400000, 31.19600000, 1),
(1837, 283, 'Higiro', -1.54300000, 31.19700000, 1),
(1838, 283, 'Mutara', -1.54200000, 31.19800000, 1),
(1839, 283, 'Nyunzwe', -1.54100000, 31.19900000, 1),
(1840, 283, 'Renga', -1.54000000, 31.20000000, 1),
(1841, 283, 'Rugomba', -1.53900000, 31.20100000, 1),
(1842, 283, 'Runda', -1.53800000, 31.20200000, 1),
(1843, 284, 'Gasegerwa', -1.53700000, 31.20300000, 1),
(1844, 284, 'Gicu', -1.53600000, 31.20400000, 1),
(1845, 284, 'Gikingo', -1.53500000, 31.20500000, 1),
(1846, 284, 'Gitare', -1.53400000, 31.20600000, 1),
(1847, 284, 'Kaganda', -1.53300000, 31.20700000, 1),
(1848, 284, 'Kagoma', -1.53200000, 31.20800000, 1),
(1849, 284, 'Kajaga', -1.53100000, 31.20900000, 1),
(1850, 284, 'Mubungere', -1.53000000, 31.21000000, 1),
(1851, 284, 'Mugende', -1.52900000, 31.21100000, 1),
(1852, 284, 'Nyabikenke', -1.52800000, 31.21200000, 1),
(1853, 284, 'Nyarusange', -1.52700000, 31.21300000, 1),
(1854, 285, 'Gacamirindi', -1.52600000, 31.21400000, 1),
(1855, 285, 'Ntaho', -1.52500000, 31.21500000, 1),
(1856, 285, 'Ruhehe', -1.52400000, 31.21600000, 1),
(1857, 285, 'Rusugi', -1.52300000, 31.21700000, 1),
(1858, 286, 'Bwinyana', -1.52200000, 31.21800000, 1),
(1859, 286, 'Canzikiro', -1.52100000, 31.21900000, 1),
(1860, 286, 'Gahe', -1.52000000, 31.22000000, 1),
(1861, 286, 'Muramba', -1.51900000, 31.22100000, 1),
(1862, 286, 'Muyebe', -1.51800000, 31.22200000, 1),
(1863, 286, 'Nyagatovu', -1.51700000, 31.22300000, 1),
(1864, 286, 'Nyabihanga', -1.51600000, 31.22400000, 1),
(1865, 286, 'Rwisuri', -1.51500000, 31.22500000, 1),
(1866, 287, 'Canika', -1.51400000, 31.22600000, 1),
(1867, 287, 'Cendajuru', -1.51300000, 31.22700000, 1),
(1868, 287, 'Kabirizi', -1.51200000, 31.22800000, 1),
(1869, 287, 'Kigobe', -1.51100000, 31.22900000, 1),
(1870, 287, 'Kiziba', -1.51000000, 31.23000000, 1),
(1871, 288, 'Ceru', -1.50900000, 31.23100000, 1),
(1872, 288, 'Cewe', -1.50800000, 31.23200000, 1),
(1873, 288, 'Rukuramigabo', -1.50700000, 31.23300000, 1),
(1874, 288, 'Runyonza', -1.50600000, 31.23400000, 1),
(1875, 289, 'Burarana', -1.50500000, 31.23500000, 1),
(1876, 289, 'Gashingwa', -1.50400000, 31.23600000, 1),
(1877, 289, 'Kavumu', -1.50300000, 31.23700000, 1),
(1878, 289, 'Kiraro', -1.50200000, 31.23800000, 1),
(1879, 289, 'Kirima', -1.50100000, 31.23900000, 1),
(1880, 289, 'Martyazo', -1.50000000, 31.24000000, 1),
(1881, 289, 'Nyakibanda', -1.49900000, 31.24100000, 1),
(1882, 289, 'Nyamivuma', -1.49800000, 31.24200000, 1),
(1883, 289, 'Nyamyumba', -1.49700000, 31.24300000, 1),
(1884, 289, 'Rugeri', -1.49600000, 31.24400000, 1),
(1885, 290, 'Gakana', -1.49500000, 31.24500000, 1),
(1886, 290, 'Gihosha', -1.49400000, 31.24600000, 1),
(1887, 290, 'Kinyangurube', -1.49300000, 31.24700000, 1),
(1888, 290, 'Mutara', -1.49200000, 31.24800000, 1),
(1889, 290, 'Mwenya', -1.49100000, 31.24900000, 1),
(1890, 290, 'Rugero', -1.49000000, 31.25000000, 1),
(1891, 290, 'Shinge', -1.48900000, 31.25100000, 1),
(1892, 291, 'Bugera', -1.48800000, 31.25200000, 1),
(1893, 291, 'Busenyi', -1.48700000, 31.25300000, 1),
(1894, 291, 'Cumva', -1.48600000, 31.25400000, 1),
(1895, 291, 'Gikuyo', -1.48500000, 31.25500000, 1),
(1896, 291, 'Kanyinya', -1.48400000, 31.25600000, 1),
(1897, 291, 'Karamagi', -1.48300000, 31.25700000, 1),
(1898, 291, 'Mataka', -1.48200000, 31.25800000, 1),
(1899, 291, 'Rambo', -1.48100000, 31.25900000, 1),
(1900, 292, 'Kavomo', -1.48000000, 31.26000000, 1),
(1901, 292, 'Kireka', -1.47900000, 31.26100000, 1),
(1902, 292, 'Kiyanza', -1.47800000, 31.26200000, 1),
(1903, 292, 'Muramba', -1.47700000, 31.26300000, 1),
(1904, 292, 'Yaranda', -1.47600000, 31.26400000, 1),
(1905, 293, 'Gaturanda', -1.47500000, 31.26500000, 1),
(1906, 293, 'Gitorongero', -1.47400000, 31.26600000, 1),
(1907, 293, 'Karisha', -1.47300000, 31.26700000, 1),
(1908, 293, 'Kigina', -1.47200000, 31.26800000, 1),
(1909, 293, 'Nyabikenke', -1.47100000, 31.26900000, 1),
(1910, 294, 'Kigoma', -1.47000000, 31.27000000, 1),
(1911, 294, 'Muyange', -1.46900000, 31.27100000, 1),
(1912, 294, 'Nyamata', -1.46800000, 31.27200000, 1),
(1913, 294, 'Rugando', -1.46700000, 31.27300000, 1),
(1914, 294, 'Rugasa', -1.46600000, 31.27400000, 1),
(1915, 294, 'Saruduha', -1.46500000, 31.27500000, 1),
(1916, 294, 'Shenga', -1.46400000, 31.27600000, 1),
(1917, 295, 'Cimo', -1.46300000, 31.27700000, 1),
(1918, 295, 'Kiri', -1.46200000, 31.27800000, 1),
(1919, 295, 'Mubuga', -1.46100000, 31.27900000, 1),
(1920, 295, 'Mugombwa', -1.46000000, 31.28000000, 1),
(1921, 295, 'Munyinya', -1.45900000, 31.28100000, 1),
(1922, 295, 'Ngaragu', -1.45800000, 31.28200000, 1),
(1923, 295, 'Ntembe', -1.45700000, 31.28300000, 1),
(1924, 296, 'Murama', -1.45600000, 31.28400000, 1),
(1925, 296, 'Quartier Bushaza', -1.45500000, 31.28500000, 1),
(1926, 296, 'Quartier Kavogero', -1.45400000, 31.28600000, 1),
(1927, 296, 'Quartier Kumahoro', -1.45300000, 31.28700000, 1),
(1928, 296, 'Quartier Rupfunda', -1.45200000, 31.28800000, 1),
(1929, 296, 'Quartier Windora', -1.45100000, 31.28900000, 1),
(1930, 297, 'Cindonyi', -1.45000000, 31.29000000, 1),
(1931, 297, 'Cinuma', -1.44900000, 31.29100000, 1),
(1932, 297, 'Gasagara', -1.44800000, 31.29200000, 1),
(1933, 297, 'Kiyonza', -1.44700000, 31.29300000, 1),
(1934, 297, 'Ninda', -1.44600000, 31.29400000, 1),
(1935, 297, 'Nunga', -1.44500000, 31.29500000, 1),
(1936, 297, 'Nyakarama', -1.44400000, 31.29600000, 1),
(1937, 297, 'Rubuga', -1.44300000, 31.29700000, 1),
(1938, 297, 'Rutamo', -1.44200000, 31.29800000, 1),
(1939, 298, 'Buringanire', -1.44100000, 31.29900000, 1),
(1940, 298, 'Carubambo', -1.44000000, 31.30000000, 1),
(1941, 298, 'Kinyovu', -1.43900000, 31.30100000, 1),
(1942, 298, 'Makombe', -1.43800000, 31.30200000, 1),
(1943, 298, 'Mariza', -1.43700000, 31.30300000, 1),
(1944, 298, 'Mugendo', -1.43600000, 31.30400000, 1),
(1945, 298, 'Ntango', -1.43500000, 31.30500000, 1),
(1946, 298, 'Rutagara', -1.43400000, 31.30600000, 1),
(1947, 298, 'Sasa', -1.43300000, 31.30700000, 1),
(1948, 299, 'Gitwenzi', -1.43200000, 31.30800000, 1),
(1949, 299, 'Kamenya', -1.43100000, 31.30900000, 1),
(1950, 299, 'Kanyagu', -1.43000000, 31.31000000, 1),
(1951, 299, 'Kigina', -1.42900000, 31.31100000, 1),
(1952, 299, 'Murungurira', -1.42800000, 31.31200000, 1),
(1953, 299, 'Susa', -1.42700000, 31.31300000, 1),
(1954, 300, 'Gasave', -1.42600000, 31.31400000, 1),
(1955, 300, 'Kigaga', -1.42500000, 31.31500000, 1),
(1956, 300, 'Mihigo', -1.42400000, 31.31600000, 1),
(1957, 300, 'Monge', -1.42300000, 31.31700000, 1),
(1958, 300, 'Ntega', -1.42200000, 31.31800000, 1),
(1959, 300, 'Nyabisindu', -1.42100000, 31.31900000, 1),
(1960, 300, 'Nyemera', -1.42000000, 31.32000000, 1),
(1961, 300, 'Rugese', -1.41900000, 31.32100000, 1);
INSERT INTO `collines` (`id_colline`, `id_zone`, `colline_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1962, 301, 'Bwiza', -1.41800000, 31.32200000, 1),
(1963, 301, 'Gitwe', -1.41700000, 31.32300000, 1),
(1964, 301, 'Kamwayi', -1.41600000, 31.32400000, 1),
(1965, 301, 'Muyumpu', -1.41500000, 31.32500000, 1),
(1966, 301, 'Nyamabuye', -1.41400000, 31.32600000, 1),
(1967, 301, 'Rukondokondo', -1.41300000, 31.32700000, 1),
(1968, 301, 'Ryagihana', -1.41200000, 31.32800000, 1),
(1969, 302, 'Bugorora', -1.41100000, 31.32900000, 1),
(1970, 302, 'Gisitwe', -1.41000000, 31.33000000, 1),
(1971, 302, 'Mwendo', -1.40900000, 31.33100000, 1),
(1972, 302, 'Ngendo', -1.40800000, 31.33200000, 1),
(1973, 302, 'Rukore', -1.40700000, 31.33300000, 1),
(1974, 302, 'Runyankezi', -1.40600000, 31.33400000, 1),
(1975, 303, 'Gatwe', -1.40500000, 31.33500000, 1),
(1976, 303, 'Kanabugiri', -1.40400000, 31.33600000, 1),
(1977, 303, 'Muyinza', -1.40300000, 31.33700000, 1),
(1978, 303, 'Nkorwe', -1.40200000, 31.33800000, 1),
(1979, 303, 'Nyakibingo', -1.40100000, 31.33900000, 1),
(1980, 303, 'Rushubije', -1.40000000, 31.34000000, 1),
(1981, 303, 'Rwimbogo', -1.39900000, 31.34100000, 1),
(1982, 304, 'Butsimba', -1.39800000, 31.34200000, 1),
(1983, 304, 'Gasura', -1.39700000, 31.34300000, 1),
(1984, 304, 'Gikomero', -1.39600000, 31.34400000, 1),
(1985, 304, 'Kabuye', -1.39500000, 31.34500000, 1),
(1986, 304, 'Mbasi', -1.39400000, 31.34600000, 1),
(1987, 304, 'Nyabikenke', -1.39300000, 31.34700000, 1),
(1988, 304, 'Rwimanzovu', -1.39200000, 31.34800000, 1),
(1989, 304, 'Shororo', -1.39100000, 31.34900000, 1),
(1990, 304, 'Vumbi', -1.39000000, 31.35000000, 1),
(1991, 305, 'Banga', -1.38900000, 31.35100000, 1),
(1992, 305, 'Bihunge', -1.38800000, 31.35200000, 1),
(1993, 305, 'Matongo', -1.38700000, 31.35300000, 1),
(1994, 305, 'Mpemba', -1.38600000, 31.35400000, 1),
(1995, 305, 'Munini', -1.38500000, 31.35500000, 1),
(1996, 305, 'Murambi', -1.38400000, 31.35600000, 1),
(1997, 305, 'Mutarure', -1.38300000, 31.35700000, 1),
(1998, 305, 'Nyakibingo', -1.38200000, 31.35800000, 1),
(1999, 305, 'Rudehe', -1.38100000, 31.35900000, 1),
(2000, 306, 'Burarana', -1.38000000, 31.36000000, 1),
(2001, 306, 'Butuhurana', -1.37900000, 31.36100000, 1),
(2002, 306, 'Bwisange', -1.37800000, 31.36200000, 1),
(2003, 306, 'Kijuri', -1.37700000, 31.36300000, 1),
(2004, 306, 'Mikamba', -1.37600000, 31.36400000, 1),
(2005, 306, 'Nyarurambi', -1.37500000, 31.36500000, 1),
(2006, 306, 'Ruvumu', -1.37400000, 31.36600000, 1),
(2007, 307, 'Burarana', -1.37300000, 31.36700000, 1),
(2008, 307, 'Busangana', -1.37200000, 31.36800000, 1),
(2009, 307, 'Busekera', -1.37100000, 31.36900000, 1),
(2010, 307, 'Gashishima', -1.37000000, 31.37000000, 1),
(2011, 307, 'Kigereka', -1.36900000, 31.37100000, 1),
(2012, 307, 'Nyambo', -1.36800000, 31.37200000, 1),
(2013, 307, 'Rwantsinda', -1.36700000, 31.37300000, 1),
(2014, 307, 'Rusha', -1.36600000, 31.37400000, 1),
(2015, 308, 'Busokoza', -1.36500000, 31.37500000, 1),
(2016, 308, 'Gahise', -1.36400000, 31.37600000, 1),
(2017, 308, 'Gatabo', -1.36300000, 31.37700000, 1),
(2018, 308, 'Mufumya', -1.36200000, 31.37800000, 1),
(2019, 308, 'Muremera', -1.36100000, 31.37900000, 1),
(2020, 308, 'Nkango', -1.36000000, 31.38000000, 1),
(2021, 308, 'Nyarurama', -1.35900000, 31.38100000, 1),
(2022, 308, 'Rukambura', -1.35800000, 31.38200000, 1),
(2023, 309, 'Gakenke', -1.35700000, 31.38300000, 1),
(2024, 309, 'Gihororo', -1.35600000, 31.38400000, 1),
(2025, 309, 'Karambi', -1.35500000, 31.38500000, 1),
(2026, 309, 'Kivuruga', -1.35400000, 31.38600000, 1),
(2027, 309, 'Muhingira', -1.35300000, 31.38700000, 1),
(2028, 309, 'Murago', -1.35200000, 31.38800000, 1),
(2029, 309, 'Nyarurambi', -1.35100000, 31.38900000, 1),
(2030, 309, 'Ruhengeri', -1.35000000, 31.39000000, 1),
(2031, 310, 'Camizi', -1.34900000, 31.39100000, 1),
(2032, 310, 'Gitwe', -1.34800000, 31.39200000, 1),
(2033, 310, 'Kabuye', -1.34700000, 31.39300000, 1),
(2034, 310, 'Kinyovu', -1.34600000, 31.39400000, 1),
(2035, 310, 'Munyinya', -1.34500000, 31.39500000, 1),
(2036, 310, 'Musonge', -1.34400000, 31.39600000, 1),
(2037, 310, 'Nteko', -1.34300000, 31.39700000, 1),
(2038, 310, 'Rukoma', -1.34200000, 31.39800000, 1),
(2039, 311, 'Kanyankuru', -1.34100000, 31.39900000, 1),
(2040, 311, 'Karurusi', -1.34000000, 31.40000000, 1),
(2041, 311, 'Kibaribari', -1.33900000, 31.40100000, 1),
(2042, 311, 'Kibayi', -1.33800000, 31.40200000, 1),
(2043, 311, 'Kibenga', -1.33700000, 31.40300000, 1),
(2044, 311, 'Mbirizi', -1.33600000, 31.40400000, 1),
(2045, 311, 'Mudusi', -1.33500000, 31.40500000, 1),
(2046, 311, 'Munini', -1.33400000, 31.40600000, 1),
(2047, 311, 'Rubagabaga', -1.33300000, 31.40700000, 1),
(2048, 312, 'Gisyo', -1.33200000, 31.40800000, 1),
(2049, 312, 'Gitwenge', -1.33100000, 31.40900000, 1),
(2050, 312, 'Kabungo', -1.33000000, 31.41000000, 1),
(2051, 312, 'Kigume', -1.32900000, 31.41100000, 1),
(2052, 312, 'Migende', -1.32800000, 31.41200000, 1),
(2053, 312, 'Ngendo', -1.32700000, 31.41300000, 1),
(2054, 312, 'Ngoro', -1.32600000, 31.41400000, 1),
(2055, 312, 'Nteko', -1.32500000, 31.41500000, 1),
(2056, 312, 'Rwankuba', -1.32400000, 31.41600000, 1),
(2057, 312, 'Shinya', -1.32300000, 31.41700000, 1),
(2058, 313, 'Bumba', -1.32200000, 31.41800000, 1),
(2059, 313, 'Gikungere', -1.32100000, 31.41900000, 1),
(2060, 313, 'Munyinya', -1.32000000, 31.42000000, 1),
(2061, 313, 'Ninga', -1.31900000, 31.42100000, 1),
(2062, 313, 'Rugoma', -1.31800000, 31.42200000, 1),
(2063, 314, 'Kigarama', -1.31700000, 31.42300000, 1),
(2064, 314, 'Kigwandi', -1.31600000, 31.42400000, 1),
(2065, 314, 'Kiryama', -1.31500000, 31.42500000, 1),
(2066, 314, 'Musema', -1.31400000, 31.42600000, 1),
(2067, 314, 'Nyabibuye', -1.31300000, 31.42700000, 1),
(2068, 314, 'Shembati', -1.31200000, 31.42800000, 1),
(2069, 315, 'Bandaga', -1.31100000, 31.42900000, 1),
(2070, 315, 'Burengo', -1.31000000, 31.43000000, 1),
(2071, 315, 'Bwayi', -1.30900000, 31.43100000, 1),
(2072, 315, 'Gasare', -1.30800000, 31.43200000, 1),
(2073, 315, 'Kibavu', -1.30700000, 31.43300000, 1),
(2074, 315, 'Kivumu', -1.30600000, 31.43400000, 1),
(2075, 315, 'Muganza', -1.30500000, 31.43500000, 1),
(2076, 316, 'Bisha', -1.30400000, 31.43600000, 1),
(2077, 316, 'Bishuri', -1.30300000, 31.43700000, 1),
(2078, 316, 'Gatare', -1.30200000, 31.43800000, 1),
(2079, 316, 'Kabuye', -1.30100000, 31.43900000, 1),
(2080, 316, 'Karehe', -1.30000000, 31.44000000, 1),
(2081, 316, 'Nyabitwe', -1.29900000, 31.44100000, 1),
(2082, 316, 'Nyamonde', -1.29800000, 31.44200000, 1),
(2083, 316, 'Rubirizi', -1.29700000, 31.44300000, 1),
(2084, 316, 'Tara', -1.29600000, 31.44400000, 1),
(2085, 317, 'Butanyerera', -1.29500000, 31.44500000, 1),
(2086, 317, 'Gacokwe', -1.29400000, 31.44600000, 1),
(2087, 317, 'Gipfuvya', -1.29300000, 31.44700000, 1),
(2088, 317, 'Karama', -1.29200000, 31.44800000, 1),
(2089, 317, 'Muzumure', -1.29100000, 31.44900000, 1),
(2090, 317, 'Nyabiyogi', -1.29000000, 31.45000000, 1),
(2091, 318, 'Businde', -1.28900000, 31.45100000, 1),
(2092, 318, 'Butezi', -1.28800000, 31.45200000, 1),
(2093, 318, 'Gahombo', -1.28700000, 31.45300000, 1),
(2094, 318, 'Gakuro', -1.28600000, 31.45400000, 1),
(2095, 318, 'Gishunzi', -1.28500000, 31.45500000, 1),
(2096, 318, 'Karinzi', -1.28400000, 31.45600000, 1),
(2097, 318, 'Kinyonga', -1.28300000, 31.45700000, 1),
(2098, 318, 'Kivoga', -1.28200000, 31.45800000, 1),
(2099, 318, 'Kiyange', -1.28100000, 31.45900000, 1),
(2100, 318, 'Mikoni', -1.28000000, 31.46000000, 1),
(2101, 318, 'Rukago', -1.27900000, 31.46100000, 1),
(2102, 318, 'Ruzingati', -1.27800000, 31.46200000, 1),
(2103, 319, 'Gikomero', -1.27700000, 31.46300000, 1),
(2104, 319, 'Kaguruka', -1.27600000, 31.46400000, 1),
(2105, 319, 'Kiguruka', -1.27500000, 31.46500000, 1),
(2106, 319, 'Kirahamira', -1.27400000, 31.46600000, 1),
(2107, 319, 'Musagara', -1.27300000, 31.46700000, 1),
(2108, 319, 'Rubungu', -1.27200000, 31.46800000, 1),
(2109, 319, 'Rusave', -1.27100000, 31.46900000, 1),
(2110, 320, 'Gaharo', -1.27000000, 31.47000000, 1),
(2111, 320, 'Gashibuka', -1.26900000, 31.47100000, 1),
(2112, 320, 'Kibimba', -1.26800000, 31.47200000, 1),
(2113, 320, 'Mbaba', -1.26700000, 31.47300000, 1),
(2114, 320, 'Muhanga', -1.26600000, 31.47400000, 1),
(2115, 320, 'Musama', -1.26500000, 31.47500000, 1),
(2116, 320, 'Mwendo', -1.26400000, 31.47600000, 1),
(2117, 320, 'Ngoma', -1.26300000, 31.47700000, 1),
(2118, 320, 'Rugamba', -1.26200000, 31.47800000, 1),
(2119, 320, 'Rushenza', -1.26100000, 31.47900000, 1),
(2120, 321, 'Ceyerezi', -1.26000000, 31.48000000, 1),
(2121, 321, 'Gasenyi', -1.25900000, 31.48100000, 1),
(2122, 321, 'Gatozo', -1.25800000, 31.48200000, 1),
(2123, 321, 'Kanyundo', -1.25700000, 31.48300000, 1),
(2124, 321, 'Masanze', -1.25600000, 31.48400000, 1),
(2125, 321, 'Mbogwe', -1.25500000, 31.48500000, 1),
(2126, 321, 'Mibazi', -1.25400000, 31.48600000, 1),
(2127, 321, 'Ndava', -1.25300000, 31.48700000, 1),
(2128, 321, 'Nyamwera', -1.25200000, 31.48800000, 1),
(2129, 321, 'Sakinyinya', -1.25100000, 31.48900000, 1),
(2130, 322, 'Bushoka', -1.25000000, 31.49000000, 1),
(2131, 322, 'Gatura', -1.24900000, 31.49100000, 1),
(2132, 322, 'Gisara', -1.24800000, 31.49200000, 1),
(2133, 322, 'Gitamo', -1.24700000, 31.49300000, 1),
(2134, 322, 'Kivuzo', -1.24600000, 31.49400000, 1),
(2135, 322, 'Mubogora', -1.24500000, 31.49500000, 1),
(2136, 322, 'Nyamitanga', -1.24400000, 31.49600000, 1),
(2137, 322, 'Nyarurambi', -1.24300000, 31.49700000, 1),
(2138, 322, 'Rubanga', -1.24200000, 31.49800000, 1),
(2139, 322, 'Rushubi', -1.24100000, 31.49900000, 1),
(2140, 323, 'Bigugo', -1.24000000, 31.50000000, 1),
(2141, 323, 'Butwe', -1.23900000, 31.50100000, 1),
(2142, 323, 'Gasave', -1.23800000, 31.50200000, 1),
(2143, 323, 'Jimbi', -1.23700000, 31.50300000, 1),
(2144, 323, 'Kavuvuma', -1.23600000, 31.50400000, 1),
(2145, 323, 'Kivuvuma', -1.23500000, 31.50500000, 1),
(2146, 323, 'Mwenene', -1.23400000, 31.50600000, 1),
(2147, 323, 'Nzewe', -1.23300000, 31.50700000, 1),
(2148, 323, 'Rukanu', -1.23200000, 31.50800000, 1),
(2149, 323, 'Shurugumya', -1.23100000, 31.50900000, 1),
(2150, 324, 'Gihororo', -1.23000000, 31.51000000, 1),
(2151, 324, 'Gitibu', -1.22900000, 31.51100000, 1),
(2152, 324, 'Nyabibuye', -1.22800000, 31.51200000, 1),
(2153, 324, 'Nyarusange', -1.22700000, 31.51300000, 1),
(2154, 324, 'Rama', -1.22600000, 31.51400000, 1),
(2155, 324, 'Rango', -1.22500000, 31.51500000, 1),
(2156, 324, 'Ruhinga', -1.22400000, 31.51600000, 1),
(2157, 325, 'Busoro', -1.22300000, 31.51700000, 1),
(2158, 325, 'Gahwazi', -1.22200000, 31.51800000, 1),
(2159, 325, 'Gakeceri', -1.22100000, 31.51900000, 1),
(2160, 325, 'Kinyana', -1.22000000, 31.52000000, 1),
(2161, 325, 'Makombe', -1.21900000, 31.52100000, 1),
(2162, 325, 'Masama', -1.21800000, 31.52200000, 1),
(2163, 326, 'Bitambwe', -1.21700000, 31.52300000, 1),
(2164, 326, 'Caga', -1.21600000, 31.52400000, 1),
(2165, 326, 'Kididiri', -1.21500000, 31.52500000, 1),
(2166, 326, 'Kigufi', -1.21400000, 31.52600000, 1),
(2167, 326, 'Mihigo', -1.21300000, 31.52700000, 1),
(2168, 326, 'Muremera', -1.21200000, 31.52800000, 1),
(2169, 326, 'Mutumba', -1.21100000, 31.52900000, 1),
(2170, 326, 'Muyogoro', -1.21000000, 31.53000000, 1),
(2171, 326, 'Nyange', -1.20900000, 31.53100000, 1),
(2172, 326, 'Rubari', -1.20800000, 31.53200000, 1),
(2173, 326, 'Rugori', -1.20700000, 31.53300000, 1),
(2174, 326, 'Rwanyege', -1.20600000, 31.53400000, 1),
(2175, 327, 'Buye', -1.20500000, 31.53500000, 1),
(2176, 327, 'Hayiro', -1.20400000, 31.53600000, 1),
(2177, 327, 'Muremera', -1.20300000, 31.53700000, 1),
(2178, 327, 'Mushitsi', -1.20200000, 31.53800000, 1),
(2179, 327, 'Nyarugunda', -1.20100000, 31.53900000, 1),
(2180, 327, 'Nzove', -1.20000000, 31.54000000, 1),
(2181, 328, 'Buhanda', -1.19900000, 31.54100000, 1),
(2182, 328, 'Buziragahama', -1.19800000, 31.54200000, 1),
(2183, 328, 'Gatsinda', -1.19700000, 31.54300000, 1),
(2184, 328, 'Gitundwe', -1.19600000, 31.54400000, 1),
(2185, 328, 'Kayanza', -1.19500000, 31.54500000, 1),
(2186, 328, 'Kibindi', -1.19400000, 31.54600000, 1),
(2187, 328, 'Murama', -1.19300000, 31.54700000, 1),
(2188, 328, 'Rukurazo', -1.19200000, 31.54800000, 1),
(2189, 328, 'Rwarangabo', -1.19100000, 31.54900000, 1),
(2190, 328, 'Sabanerwa', -1.19000000, 31.55000000, 1),
(2191, 329, 'Gihoma', -1.18900000, 31.55100000, 1),
(2192, 329, 'Kivuzo', -1.18800000, 31.55200000, 1),
(2193, 329, 'Makaba', -1.18700000, 31.55300000, 1),
(2194, 329, 'Nkero', -1.18600000, 31.55400000, 1),
(2195, 329, 'Rwahirwa', -1.18500000, 31.55500000, 1),
(2196, 330, 'Cigumije', -1.18400000, 31.55600000, 1),
(2197, 330, 'Gasebeyi', -1.18300000, 31.55700000, 1),
(2198, 330, 'Gitwenzi', -1.18200000, 31.55800000, 1),
(2199, 330, 'Hina', -1.18100000, 31.55900000, 1),
(2200, 330, 'Kambati', -1.18000000, 31.56000000, 1),
(2201, 330, 'Kiruri', -1.17900000, 31.56100000, 1),
(2202, 330, 'Mirango', -1.17800000, 31.56200000, 1),
(2203, 330, 'Mivo', -1.17700000, 31.56300000, 1),
(2204, 330, 'Mwungere', -1.17600000, 31.56400000, 1),
(2205, 331, 'Bwiza', -1.17500000, 31.56500000, 1),
(2206, 331, 'Gahengeri', -1.17400000, 31.56600000, 1),
(2207, 331, 'Kavumu', -1.17300000, 31.56700000, 1),
(2208, 331, 'Kayogoro', -1.17200000, 31.56800000, 1),
(2209, 331, 'Mubuga', -1.17100000, 31.56900000, 1),
(2210, 331, 'Nyaruntana', -1.17000000, 31.57000000, 1),
(2211, 331, 'Sare', -1.16900000, 31.57100000, 1),
(2212, 331, 'Shango', -1.16800000, 31.57200000, 1),
(2213, 332, 'Burima', -1.16700000, 31.57300000, 1),
(2214, 332, 'Kimenyi', -1.16600000, 31.57400000, 1),
(2215, 332, 'Mugomera', -1.16500000, 31.57500000, 1),
(2216, 332, 'Ntaho', -1.16400000, 31.57600000, 1),
(2217, 332, 'Nyabihanga', -1.16300000, 31.57700000, 1),
(2218, 332, 'Nyanza', -1.16200000, 31.57800000, 1),
(2219, 332, 'Ruhongore', -1.16100000, 31.57900000, 1),
(2220, 333, 'Bugorora', -1.16000000, 31.58000000, 1),
(2221, 333, 'Gakenke', -1.15900000, 31.58100000, 1),
(2222, 333, 'Gihama', -1.15800000, 31.58200000, 1),
(2223, 333, 'Gitasi', -1.15700000, 31.58300000, 1),
(2224, 333, 'Kabasazi', -1.15600000, 31.58400000, 1),
(2225, 333, 'Kagozi', -1.15500000, 31.58500000, 1),
(2226, 333, 'Karungura', -1.15400000, 31.58600000, 1),
(2227, 333, 'Ntembe', -1.15300000, 31.58700000, 1),
(2228, 334, 'Quartier Camugani', -1.15200000, 31.58800000, 1),
(2229, 334, 'Quartier Gabiro', -1.15100000, 31.58900000, 1),
(2230, 334, 'Quartier Gisagara', -1.15000000, 31.59000000, 1),
(2231, 334, 'Quartier Kanyami', -1.14900000, 31.59100000, 1),
(2232, 334, 'Quartier Kinyami', -1.14800000, 31.59200000, 1),
(2233, 334, 'Quartier Muremera', -1.14700000, 31.59300000, 1),
(2234, 334, 'Quartier Rubuye', -1.14600000, 31.59400000, 1),
(2235, 334, 'Quartier Rusuguti', -1.14500000, 31.59500000, 1),
(2236, 334, 'Quartier Shikiro', -1.14400000, 31.59600000, 1),
(2237, 335, 'Gatika', -1.14300000, 31.59700000, 1),
(2238, 335, 'Kavumu', -1.14200000, 31.59800000, 1),
(2239, 335, 'Kimagara', -1.14100000, 31.59900000, 1),
(2240, 335, 'Mpondogoto', -1.14000000, 31.60000000, 1),
(2241, 335, 'Murambi', -1.13900000, 31.60100000, 1),
(2242, 335, 'Nyabizinu', -1.13800000, 31.60200000, 1),
(2243, 335, 'Rumbaga', -1.13700000, 31.60300000, 1),
(2244, 336, 'Burenza', -1.13600000, 31.60400000, 1),
(2245, 336, 'Butaganda', -1.13500000, 31.60500000, 1),
(2246, 336, 'Cahi', -1.13400000, 31.60600000, 1),
(2247, 336, 'Kabataha', -1.13300000, 31.60700000, 1),
(2248, 336, 'Rwabiriro', -1.13200000, 31.60800000, 1),
(2249, 337, 'Cumba', -1.13100000, 31.60900000, 1),
(2250, 337, 'Gitaramuka', -1.13000000, 31.61000000, 1),
(2251, 337, 'Mwika', -1.12900000, 31.61100000, 1),
(2252, 337, 'Nkanda', -1.12800000, 31.61200000, 1),
(2253, 337, 'Nyankurazo', -1.12700000, 31.61300000, 1),
(2254, 337, 'Nyarugati', -1.12600000, 31.61400000, 1),
(2255, 338, 'Cihonda', -1.12500000, 31.61500000, 1),
(2256, 338, 'Gashikanwa', -1.12400000, 31.61600000, 1),
(2257, 338, 'Kivumu', -1.12300000, 31.61700000, 1),
(2258, 338, 'Maruri', -1.12200000, 31.61800000, 1),
(2259, 338, 'Nini', -1.12100000, 31.61900000, 1),
(2260, 338, 'Rwizingwe', -1.12000000, 31.62000000, 1),
(2261, 339, 'Buhoro', -1.11900000, 31.62100000, 1),
(2262, 339, 'Butaganda', -1.11800000, 31.62200000, 1),
(2263, 339, 'Gitanga', -1.11700000, 31.62300000, 1),
(2264, 339, 'Musumba', -1.11600000, 31.62400000, 1),
(2265, 339, 'Ruhengeri', -1.11500000, 31.62500000, 1),
(2266, 339, 'Sigi', -1.11400000, 31.62600000, 1),
(2267, 340, 'Buganuka', -1.11300000, 31.62700000, 1),
(2268, 340, 'Kabuye', -1.11200000, 31.62800000, 1),
(2269, 340, 'Mubira', -1.11100000, 31.62900000, 1),
(2270, 340, 'Mukoni', -1.11000000, 31.63000000, 1),
(2271, 340, 'Mutobo', -1.10900000, 31.63100000, 1),
(2272, 340, 'Nyakibingo', -1.10800000, 31.63200000, 1),
(2273, 340, 'Nyamugari', -1.10700000, 31.63300000, 1),
(2274, 340, 'Rimiro', -1.10600000, 31.63400000, 1),
(2275, 340, 'Ruyaga', -1.10500000, 31.63500000, 1),
(2276, 341, 'Kamira', -1.10400000, 31.63600000, 1),
(2277, 341, 'Kananira', -1.10300000, 31.63700000, 1),
(2278, 341, 'Muramba', -1.10200000, 31.63800000, 1),
(2279, 341, 'Rugabo', -1.10100000, 31.63900000, 1),
(2280, 341, 'Runini', -1.10000000, 31.64000000, 1),
(2281, 341, 'Ruyogoro', -1.09900000, 31.64100000, 1),
(2282, 342, 'Gitwenzi', -1.09800000, 31.64200000, 1),
(2283, 342, 'Kimerejana', -1.09700000, 31.64300000, 1),
(2284, 342, 'Mihigo', -1.09600000, 31.64400000, 1),
(2285, 342, 'Mubanga', -1.09500000, 31.64500000, 1),
(2286, 342, 'Muhama', -1.09400000, 31.64600000, 1),
(2287, 342, 'Ntiba', -1.09300000, 31.64700000, 1),
(2288, 343, 'Bomba', -1.09200000, 31.64800000, 1),
(2289, 343, 'Gitwa', -1.09100000, 31.64900000, 1),
(2290, 343, 'Mafu', -1.09000000, 31.65000000, 1),
(2291, 343, 'Mirango', -1.08900000, 31.65100000, 1),
(2292, 343, 'Mugirampeke', -1.08800000, 31.65200000, 1),
(2293, 343, 'Musakazi', -1.08700000, 31.65300000, 1),
(2294, 343, 'Musenyi', -1.08600000, 31.65400000, 1),
(2295, 343, 'Nyagasebeyi', -1.08500000, 31.65500000, 1),
(2296, 343, 'Rushoka', -1.08400000, 31.65600000, 1),
(2297, 343, 'Ruyaga', -1.08300000, 31.65700000, 1),
(2298, 344, 'Butaha', -1.08200000, 31.65800000, 1),
(2299, 344, 'Gatukuza', -1.08100000, 31.65900000, 1),
(2300, 344, 'Kabamba', -1.08000000, 31.66000000, 1),
(2301, 344, 'Ngoma', -1.07900000, 31.66100000, 1),
(2302, 344, 'Rutanga', -1.07800000, 31.66200000, 1),
(2303, 345, 'Butezi', -1.07700000, 31.66300000, 1),
(2304, 345, 'Gikingo', -1.07600000, 31.66400000, 1),
(2305, 345, 'Kibande', -1.07500000, 31.66500000, 1),
(2306, 345, 'Mashitsi', -1.07400000, 31.66600000, 1),
(2307, 345, 'Nyagatovu', -1.07300000, 31.66700000, 1),
(2308, 345, 'Rukongwa', -1.07200000, 31.66800000, 1),
(2309, 346, 'Gatare', -1.07100000, 31.66900000, 1),
(2310, 346, 'Mafuro', -1.07000000, 31.67000000, 1),
(2311, 346, 'Remera', -1.06900000, 31.67100000, 1),
(2312, 346, 'Rusengo', -1.06800000, 31.67200000, 1),
(2313, 346, 'Rutambwe', -1.06700000, 31.67300000, 1),
(2314, 346, 'Sabunda', -1.06600000, 31.67400000, 1),
(2315, 347, 'Bucamihigo', -1.06500000, 31.67500000, 1),
(2316, 347, 'Buniha', -1.06400000, 31.67600000, 1),
(2317, 347, 'Cagura', -1.06300000, 31.67700000, 1),
(2318, 347, 'Gitamo', -1.06200000, 31.67800000, 1),
(2319, 347, 'Gitanga', -1.06100000, 31.67900000, 1),
(2320, 347, 'Gitaramuka', -1.06000000, 31.68000000, 1),
(2321, 347, 'Kagoma', -1.05900000, 31.68100000, 1),
(2322, 347, 'Kinyami', -1.05800000, 31.68200000, 1),
(2323, 347, 'Rwamiko', -1.05700000, 31.68300000, 1),
(2324, 348, 'Banda', -1.05600000, 31.68400000, 1),
(2325, 348, 'Giturwe', -1.05500000, 31.68500000, 1),
(2326, 348, 'Kobero', -1.05400000, 31.68600000, 1),
(2327, 348, 'Nkoto', -1.05300000, 31.68700000, 1),
(2328, 348, 'Nyarunazi', -1.05200000, 31.68800000, 1),
(2329, 348, 'Nyinya', -1.05100000, 31.68900000, 1),
(2330, 348, 'Ryarunyinya', -1.05000000, 31.69000000, 1),
(2331, 349, 'Bwitoyi', -1.04900000, 31.69100000, 1),
(2332, 349, 'Gasekanya', -1.04800000, 31.69200000, 1),
(2333, 349, 'Gisura', -1.04700000, 31.69300000, 1),
(2334, 349, 'Kigomero', -1.04600000, 31.69400000, 1),
(2335, 349, 'Kiruhura', -1.04500000, 31.69500000, 1),
(2336, 349, 'Mbasi', -1.04400000, 31.69600000, 1),
(2337, 349, 'Myando', -1.04300000, 31.69700000, 1),
(2338, 349, 'Ngendo', -1.04200000, 31.69800000, 1),
(2339, 349, 'Nyakabanda', -1.04100000, 31.69900000, 1),
(2340, 350, 'Bitare', -1.04000000, 31.70000000, 1),
(2341, 350, 'Carire', -1.03900000, 31.70100000, 1),
(2342, 350, 'Gaterama', -1.03800000, 31.70200000, 1),
(2343, 350, 'Gitongo', -1.03700000, 31.70300000, 1),
(2344, 350, 'Kibasi', -1.03600000, 31.70400000, 1),
(2345, 350, 'Kibungo', -1.03500000, 31.70500000, 1),
(2346, 350, 'Runyeri', -1.03400000, 31.70600000, 1),
(2347, 351, 'Cishwa', -1.03300000, 31.70700000, 1),
(2348, 351, 'Jenda', -1.03200000, 31.70800000, 1),
(2349, 351, 'Mugitega', -1.03100000, 31.70900000, 1),
(2350, 351, 'Mukoro', -1.03000000, 31.71000000, 1),
(2351, 351, 'Mwurire', -1.02900000, 31.71100000, 1),
(2352, 351, 'Nkanda', -1.02800000, 31.71200000, 1),
(2353, 351, 'Rwingiri', -1.02700000, 31.71300000, 1),
(2354, 352, 'Gitongo', -1.02600000, 31.71400000, 1),
(2355, 352, 'Masango', -1.02500000, 31.71500000, 1),
(2356, 352, 'Muririmbo', -1.02400000, 31.71600000, 1),
(2357, 352, 'Muyange', -1.02300000, 31.71700000, 1),
(2358, 352, 'Muzenga', -1.02200000, 31.71800000, 1),
(2359, 352, 'Mwumba', -1.02100000, 31.71900000, 1),
(2360, 352, 'Nkongwe', -1.02000000, 31.72000000, 1),
(2361, 353, 'Gitora', -1.01900000, 31.72100000, 1),
(2362, 353, 'Mirama', -1.01800000, 31.72200000, 1),
(2363, 353, 'Nyamagana', -1.01700000, 31.72300000, 1),
(2364, 353, 'Rushanga', -1.01600000, 31.72400000, 1),
(2365, 354, 'Bigera', -1.01500000, 31.72500000, 1),
(2366, 354, 'Mushikanwa', -1.01400000, 31.72600000, 1),
(2367, 354, 'Mutaho', -1.01300000, 31.72700000, 1),
(2368, 354, 'Nyabisaka', -1.01200000, 31.72800000, 1),
(2369, 354, 'Nyangungu', -1.01100000, 31.72900000, 1),
(2370, 354, 'Quartier Bigera', -1.01000000, 31.73000000, 1),
(2371, 354, 'Quartier Kigwati', -1.00900000, 31.73100000, 1),
(2372, 355, 'Mutoyi', -1.00800000, 31.73200000, 1),
(2373, 355, 'Kivuvu', -1.00700000, 31.73300000, 1),
(2374, 355, 'Nyagisenyi', -1.00600000, 31.73400000, 1),
(2375, 355, 'Nyakeru', -1.00500000, 31.73500000, 1),
(2376, 356, 'Gerangabo', -1.00400000, 31.73600000, 1),
(2377, 356, 'Kidasha', -1.00300000, 31.73700000, 1),
(2378, 356, 'Kinyinya', -1.00200000, 31.73800000, 1),
(2379, 356, 'Kivoga', -1.00100000, 31.73900000, 1),
(2380, 356, 'Ngoma', -1.00000000, 31.74000000, 1),
(2381, 356, 'Nzove', -0.99900000, 31.74100000, 1),
(2382, 356, 'Rurengera', -0.99800000, 31.74200000, 1),
(2383, 357, 'Bukirasazi', -0.99700000, 31.74300000, 1),
(2384, 357, 'Migano', -0.99600000, 31.74400000, 1),
(2385, 357, 'Mpingwe', -0.99500000, 31.74500000, 1),
(2386, 357, 'Nyambuye', -0.99400000, 31.74600000, 1),
(2387, 357, 'Rugoma', -0.99300000, 31.74700000, 1),
(2388, 357, 'Rwinyana', -0.99200000, 31.74800000, 1),
(2389, 357, 'Shaya', -0.99100000, 31.74900000, 1),
(2390, 358, 'Bihomvora', -0.99000000, 31.75000000, 1),
(2391, 358, 'Bikingi', -0.98900000, 31.75100000, 1),
(2392, 358, 'Bukoro', -0.98800000, 31.75200000, 1),
(2393, 358, 'Jurwe', -0.98700000, 31.75300000, 1),
(2394, 358, 'Masare', -0.98600000, 31.75400000, 1),
(2395, 358, 'Muhororo', -0.98500000, 31.75500000, 1),
(2396, 359, 'Bubaji', -0.98400000, 31.75600000, 1),
(2397, 359, 'Buraza', -0.98300000, 31.75700000, 1),
(2398, 359, 'Buriza', -0.98200000, 31.75800000, 1),
(2399, 359, 'Gicumbi', -0.98100000, 31.75900000, 1),
(2400, 359, 'Kabumbe', -0.98000000, 31.76000000, 1),
(2401, 359, 'Musebeyi', -0.97900000, 31.76100000, 1),
(2402, 359, 'Ndava', -0.97800000, 31.76200000, 1),
(2403, 360, 'Butemba', -0.97700000, 31.76300000, 1),
(2404, 360, 'Butezi', -0.97600000, 31.76400000, 1),
(2405, 360, 'Muyange', -0.97500000, 31.76500000, 1),
(2406, 360, 'Ndago', -0.97400000, 31.76600000, 1),
(2407, 361, 'Bukwavu', -0.97300000, 31.76700000, 1),
(2408, 361, 'Gishubi', -0.97200000, 31.76800000, 1),
(2409, 361, 'Mugozi', -0.97100000, 31.76900000, 1),
(2410, 361, 'Muhuzu', -0.97000000, 31.77000000, 1),
(2411, 361, 'Munyinya', -0.96900000, 31.77100000, 1),
(2412, 361, 'Nyakigina', -0.96800000, 31.77200000, 1),
(2413, 361, 'Remera', -0.96700000, 31.77300000, 1),
(2414, 361, 'Ruhande', -0.96600000, 31.77400000, 1),
(2415, 362, 'Kibagara', -0.96500000, 31.77500000, 1),
(2416, 362, 'Kibaya', -0.96400000, 31.77600000, 1),
(2417, 362, 'Kinyonzo', -0.96300000, 31.77700000, 1),
(2418, 362, 'Mahonda', -0.96200000, 31.77800000, 1),
(2419, 362, 'Murama', -0.96100000, 31.77900000, 1),
(2420, 362, 'Nyakarambo', -0.96000000, 31.78000000, 1),
(2421, 362, 'Nyamugari', -0.95900000, 31.78100000, 1),
(2422, 363, 'Bunyuka', -0.95800000, 31.78200000, 1),
(2423, 363, 'Kibere', -0.95700000, 31.78300000, 1),
(2424, 363, 'Nyamisure', -0.95600000, 31.78400000, 1),
(2425, 363, 'Rugabano', -0.95500000, 31.78500000, 1),
(2426, 363, 'Rukoki', -0.95400000, 31.78600000, 1),
(2427, 364, 'Buhanda', -0.95300000, 31.78700000, 1),
(2428, 364, 'Gasongati', -0.95200000, 31.78800000, 1),
(2429, 364, 'Kibuye', -0.95100000, 31.78900000, 1),
(2430, 364, 'Ruhinda', -0.95000000, 31.79000000, 1),
(2431, 364, 'Ruvumu', -0.94900000, 31.79100000, 1),
(2432, 364, 'Tema', -0.94800000, 31.79200000, 1),
(2433, 365, 'Bibate', -0.94700000, 31.79300000, 1),
(2434, 365, 'Bugega', -0.94600000, 31.79400000, 1),
(2435, 365, 'Gisura', -0.94500000, 31.79500000, 1),
(2436, 365, 'Gitaramuka', -0.94400000, 31.79600000, 1),
(2437, 365, 'Mahonda', -0.94300000, 31.79700000, 1),
(2438, 365, 'Maza', -0.94200000, 31.79800000, 1),
(2439, 365, 'Mugano', -0.94100000, 31.79900000, 1),
(2440, 365, 'Rweza', -0.94000000, 31.80000000, 1),
(2441, 366, 'Bucana', -0.93900000, 31.80100000, 1),
(2442, 366, 'Gatare', -0.93800000, 31.80200000, 1),
(2443, 366, 'Gatoze', -0.93700000, 31.80300000, 1),
(2444, 366, 'Kejari', -0.93600000, 31.80400000, 1),
(2445, 366, 'Mikore', -0.93500000, 31.80500000, 1),
(2446, 366, 'Mugaruro', -0.93400000, 31.80600000, 1),
(2447, 366, 'Muhagaze', -0.93300000, 31.80700000, 1),
(2448, 366, 'Mujejuru', -0.93200000, 31.80800000, 1),
(2449, 366, 'Rukiga', -0.93100000, 31.80900000, 1),
(2450, 366, 'Rwitamba', -0.93000000, 31.81000000, 1),
(2451, 367, 'Gahembe', -0.92900000, 31.81100000, 1),
(2452, 367, 'Gatwaro', -0.92800000, 31.81200000, 1),
(2453, 367, 'Gitaramuka', -0.92700000, 31.81300000, 1),
(2454, 367, 'Murambi', -0.92600000, 31.81400000, 1),
(2455, 368, 'Cimba', -0.92500000, 31.81500000, 1),
(2456, 368, 'Kayogoro', -0.92400000, 31.81600000, 1),
(2457, 368, 'Murangara', -0.92300000, 31.81700000, 1),
(2458, 368, 'Murehe', -0.92200000, 31.81800000, 1),
(2459, 368, 'Ntunda', -0.92100000, 31.81900000, 1),
(2460, 368, 'Nyamutobo', -0.92000000, 31.82000000, 1),
(2461, 368, 'Yanza', -0.91900000, 31.82100000, 1),
(2462, 369, 'Gikuka', -0.91800000, 31.82200000, 1),
(2463, 369, 'Kigomera', -0.91700000, 31.82300000, 1),
(2464, 369, 'Kigufi', -0.91600000, 31.82400000, 1),
(2465, 369, 'Musenga', -0.91500000, 31.82500000, 1),
(2466, 369, 'Ndago', -0.91400000, 31.82600000, 1),
(2467, 369, 'Nyakazi', -0.91300000, 31.82700000, 1),
(2468, 369, 'Nyamirama', -0.91200000, 31.82800000, 1),
(2469, 369, 'Nyamugari', -0.91100000, 31.82900000, 1),
(2470, 369, 'Rurimbi', -0.91000000, 31.83000000, 1),
(2471, 370, 'Biziya', -0.90900000, 31.83100000, 1),
(2472, 370, 'Gasenyi', -0.90800000, 31.83200000, 1),
(2473, 370, 'Kabimba', -0.90700000, 31.83300000, 1),
(2474, 370, 'Kigara', -0.90600000, 31.83400000, 1),
(2475, 370, 'Muzima', -0.90500000, 31.83500000, 1),
(2476, 370, 'Nyarubenga', -0.90400000, 31.83600000, 1),
(2477, 370, 'Tye', -0.90300000, 31.83700000, 1),
(2478, 371, 'Mirango', -0.90200000, 31.83800000, 1),
(2479, 371, 'Murenge', -0.90100000, 31.83900000, 1),
(2480, 371, 'Ngaruzwa', -0.90000000, 31.84000000, 1),
(2481, 371, 'Ntunda', -0.89900000, 31.84100000, 1),
(2482, 371, 'Nyentambwe', -0.89800000, 31.84200000, 1),
(2483, 371, 'Rusaga', -0.89700000, 31.84300000, 1),
(2484, 372, 'Buhoro', -0.89600000, 31.84400000, 1),
(2485, 372, 'Kagoma', -0.89500000, 31.84500000, 1),
(2486, 372, 'Kanyonga', -0.89400000, 31.84600000, 1),
(2487, 372, 'Rukobe', -0.89300000, 31.84700000, 1),
(2488, 372, 'Rutegama', -0.89200000, 31.84800000, 1),
(2489, 373, 'Buhinda', -0.89100000, 31.84900000, 1),
(2490, 373, 'Gihamagara', -0.89000000, 31.85000000, 1),
(2491, 373, 'Kibogoye', -0.88900000, 31.85100000, 1),
(2492, 373, 'Kirambi', -0.88800000, 31.85200000, 1),
(2493, 373, 'Kiremba', -0.88700000, 31.85300000, 1),
(2494, 373, 'Kugitega', -0.88600000, 31.85400000, 1),
(2495, 373, 'Mugomera', -0.88500000, 31.85500000, 1),
(2496, 373, 'Peniyere', -0.88400000, 31.85600000, 1),
(2497, 373, 'Ruhanza', -0.88300000, 31.85700000, 1),
(2498, 374, 'Bihororo', -0.88200000, 31.85800000, 1),
(2499, 374, 'Gisuru', -0.88100000, 31.85900000, 1),
(2500, 374, 'Kibogoye', -0.88000000, 31.86000000, 1),
(2501, 374, 'Kiremera', -0.87900000, 31.86100000, 1),
(2502, 374, 'Korane', -0.87800000, 31.86200000, 1),
(2503, 374, 'Muremera', -0.87700000, 31.86300000, 1),
(2504, 374, 'Ruhanza', -0.87600000, 31.86400000, 1),
(2505, 374, 'Rutegama', -0.87500000, 31.86500000, 1),
(2506, 375, 'Butare', -0.87400000, 31.86600000, 1),
(2507, 375, 'Gisikara', -0.87300000, 31.86700000, 1),
(2508, 375, 'Itaba', -0.87200000, 31.86800000, 1),
(2509, 375, 'Kanyinya', -0.87100000, 31.86900000, 1),
(2510, 375, 'Karemba', -0.87000000, 31.87000000, 1),
(2511, 375, 'Kinyaruko', -0.86900000, 31.87100000, 1),
(2512, 375, 'Macu', -0.86800000, 31.87200000, 1),
(2513, 375, 'Mutanga', -0.86700000, 31.87300000, 1),
(2514, 375, 'Nkima', -0.86600000, 31.87400000, 1),
(2515, 376, 'Gihehe', -0.86500000, 31.87500000, 1),
(2516, 376, 'Gihuga', -0.86400000, 31.87600000, 1),
(2517, 376, 'Kaguhu', -0.86300000, 31.87700000, 1),
(2518, 376, 'Kibimba', -0.86200000, 31.87800000, 1),
(2519, 376, 'Murayi', -0.86100000, 31.87900000, 1),
(2520, 376, 'Musama', -0.86000000, 31.88000000, 1),
(2521, 376, 'Muyange', -0.85900000, 31.88100000, 1),
(2522, 376, 'Nyamugari', -0.85800000, 31.88200000, 1),
(2523, 376, 'Rweru', -0.85700000, 31.88300000, 1),
(2524, 377, 'Gasunu', -0.85600000, 31.88400000, 1),
(2525, 377, 'Gisarara', -0.85500000, 31.88500000, 1),
(2526, 377, 'Gishuha', -0.85400000, 31.88600000, 1),
(2527, 377, 'Kamonyi', -0.85300000, 31.88700000, 1),
(2528, 377, 'Kibande', -0.85200000, 31.88800000, 1),
(2529, 377, 'Kiriba', -0.85100000, 31.88900000, 1),
(2530, 377, 'Masasu', -0.85000000, 31.89000000, 1),
(2531, 377, 'Rubarasi', -0.84900000, 31.89100000, 1),
(2532, 377, 'Rwingiri', -0.84800000, 31.89200000, 1),
(2533, 378, 'Bugumbasha', -0.84700000, 31.89300000, 1),
(2534, 378, 'Gasasa', -0.84600000, 31.89400000, 1),
(2535, 378, 'Kagege', -0.84500000, 31.89500000, 1),
(2536, 378, 'Makebuko', -0.84400000, 31.89600000, 1),
(2537, 378, 'Mavuvu', -0.84300000, 31.89700000, 1),
(2538, 378, 'Ngundu', -0.84200000, 31.89800000, 1),
(2539, 378, 'Ntita', -0.84100000, 31.89900000, 1),
(2540, 378, 'Rwanda', -0.84000000, 31.90000000, 1),
(2541, 378, 'Rwesero', -0.83900000, 31.90100000, 1),
(2542, 379, 'Buga', -0.83800000, 31.90200000, 1),
(2543, 379, 'Janja', -0.83700000, 31.90300000, 1),
(2544, 379, 'Karoba', -0.83600000, 31.90400000, 1),
(2545, 379, 'Kiyange', -0.83500000, 31.90500000, 1),
(2546, 379, 'Murago', -0.83400000, 31.90600000, 1),
(2547, 379, 'Musave', -0.83300000, 31.90700000, 1),
(2548, 379, 'Muyange', -0.83200000, 31.90800000, 1),
(2549, 379, 'Rusagara', -0.83100000, 31.90900000, 1),
(2550, 379, 'Rwezamenyo', -0.83000000, 31.91000000, 1),
(2551, 379, 'Simba', -0.82900000, 31.91100000, 1),
(2552, 380, 'Bukinga', -0.82800000, 31.91200000, 1),
(2553, 380, 'Kanyinya', -0.82700000, 31.91300000, 1),
(2554, 380, 'Mitimire', -0.82600000, 31.91400000, 1),
(2555, 380, 'Mubuga', -0.82500000, 31.91500000, 1),
(2556, 380, 'Nyarunazi', -0.82400000, 31.91600000, 1),
(2557, 381, 'Bukwazo', -0.82300000, 31.91700000, 1),
(2558, 381, 'Karenda', -0.82200000, 31.91800000, 1),
(2559, 381, 'Kimanama', -0.82100000, 31.91900000, 1),
(2560, 381, 'Mirama', -0.82000000, 31.92000000, 1),
(2561, 381, 'Mubuga', -0.81900000, 31.92100000, 1),
(2562, 381, 'Mukanda', -0.81800000, 31.92200000, 1),
(2563, 381, 'Murirwe', -0.81700000, 31.92300000, 1),
(2564, 381, 'Ngobeke', -0.81600000, 31.92400000, 1),
(2565, 382, 'Bihanga', -0.81500000, 31.92500000, 1),
(2566, 382, 'Kibiri', -0.81400000, 31.92600000, 1),
(2567, 382, 'Rutoke', -0.81300000, 31.92700000, 1),
(2568, 382, 'Rweza', -0.81200000, 31.92800000, 1),
(2569, 382, 'Quartier Butamuheba', -0.81100000, 31.92900000, 1),
(2570, 382, 'Quartier Jimbi', -0.81000000, 31.93000000, 1),
(2571, 382, 'Quartier Karera', -0.80900000, 31.93100000, 1),
(2572, 382, 'Quartier Mugoboka', -0.80800000, 31.93200000, 1),
(2573, 382, 'Quartier Mungwa', -0.80700000, 31.93300000, 1),
(2574, 382, 'Quartier Ntobwe', -0.80600000, 31.93400000, 1),
(2575, 383, 'Butobwe', -0.80500000, 31.93500000, 1),
(2576, 383, 'Gasagara', -0.80400000, 31.93600000, 1),
(2577, 383, 'Gasenyi', -0.80300000, 31.93700000, 1),
(2578, 383, 'Muhororo', -0.80200000, 31.93800000, 1),
(2579, 383, 'Murenda', -0.80100000, 31.93900000, 1),
(2580, 383, 'Mwanzari', -0.80000000, 31.94000000, 1),
(2581, 383, 'Mwumba', -0.79900000, 31.94100000, 1),
(2582, 383, 'Nyamagandika', -0.79800000, 31.94200000, 1),
(2583, 383, 'Rutanganika', -0.79700000, 31.94300000, 1),
(2584, 384, 'Quartier Bwoga', -0.79600000, 31.94400000, 1),
(2585, 384, 'Quartier Magarama', -0.79500000, 31.94500000, 1),
(2586, 384, 'Quartier Musave', -0.79400000, 31.94600000, 1),
(2587, 384, 'Quartier Mushasha', -0.79300000, 31.94700000, 1),
(2588, 384, 'Quartier Musinzira', -0.79200000, 31.94800000, 1),
(2589, 384, 'Quartier Nyabisindu', -0.79100000, 31.94900000, 1),
(2590, 384, 'Quartier Nyabututsi', -0.79000000, 31.95000000, 1),
(2591, 384, 'Quartier Nyamugari', -0.78900000, 31.95100000, 1),
(2592, 384, 'Quartier Rango', -0.78800000, 31.95200000, 1),
(2593, 384, 'Quartier Shatanya', -0.78700000, 31.95300000, 1),
(2594, 384, 'Quartier Yoba', -0.78600000, 31.95400000, 1),
(2595, 385, 'Higiro', -0.78500000, 31.95500000, 1),
(2596, 385, 'Gitamo', -0.78400000, 31.95600000, 1),
(2597, 385, 'Nyakibingo', -0.78300000, 31.95700000, 1),
(2598, 385, 'Quartier Birohe', -0.78200000, 31.95800000, 1),
(2599, 385, 'Quartier Ceru', -0.78100000, 31.95900000, 1),
(2600, 385, 'Quartier Mahonda', -0.78000000, 31.96000000, 1),
(2601, 385, 'Quartier Mugutu', -0.77900000, 31.96100000, 1),
(2602, 385, 'Quartier Rubarasi', -0.77800000, 31.96200000, 1),
(2603, 385, 'Quartier Rukoba', -0.77700000, 31.96300000, 1),
(2604, 385, 'Quartier Rutegama', -0.77600000, 31.96400000, 1),
(2605, 385, 'Quartier Songa', -0.77500000, 31.96500000, 1),
(2606, 385, 'Quartier Zege', -0.77400000, 31.96600000, 1),
(2607, 385, 'Rubamvyi', -0.77300000, 31.96700000, 1),
(2608, 386, 'Bugenyuzi', -0.77200000, 31.96800000, 1),
(2609, 386, 'Canzikiro', -0.77100000, 31.96900000, 1),
(2610, 386, 'Cuba', -0.77000000, 31.97000000, 1),
(2611, 386, 'Gashanga', -0.76900000, 31.97100000, 1),
(2612, 386, 'Kanazi', -0.76800000, 31.97200000, 1),
(2613, 386, 'Kigufi', -0.76700000, 31.97300000, 1),
(2614, 386, 'Kiranda', -0.76600000, 31.97400000, 1),
(2615, 386, 'Muramba', -0.76500000, 31.97500000, 1),
(2616, 386, 'Muyange', -0.76400000, 31.97600000, 1),
(2617, 386, 'Nyagoba', -0.76300000, 31.97700000, 1),
(2618, 387, 'Buhiga', -0.76200000, 31.97800000, 1),
(2619, 387, 'Gitanga', -0.76100000, 31.97900000, 1),
(2620, 387, 'Karunyinya', -0.76000000, 31.98000000, 1),
(2621, 387, 'Magamba', -0.75900000, 31.98100000, 1),
(2622, 387, 'Mwoya', -0.75800000, 31.98200000, 1),
(2623, 387, 'Nzibariba', -0.75700000, 31.98300000, 1),
(2624, 387, 'Ramvya', -0.75600000, 31.98400000, 1),
(2625, 387, 'Ruvumu', -0.75500000, 31.98500000, 1),
(2626, 387, 'Rwingoma', -0.75400000, 31.98600000, 1),
(2627, 388, 'Buhindye', -0.75300000, 31.98700000, 1),
(2628, 388, 'Gishikanwa', -0.75200000, 31.98800000, 1),
(2629, 388, 'Kidahwe', -0.75100000, 31.98900000, 1),
(2630, 388, 'Nyamirambo', -0.75000000, 31.99000000, 1),
(2631, 388, 'Rwandagaro', -0.74900000, 31.99100000, 1),
(2632, 388, 'Teme', -0.74800000, 31.99200000, 1),
(2633, 389, 'Buhinyuza', -0.74700000, 31.99300000, 1),
(2634, 389, 'Bushirambeho', -0.74600000, 31.99400000, 1),
(2635, 389, 'Gisenyi', -0.74500000, 31.99500000, 1),
(2636, 389, 'Kanyange', -0.74400000, 31.99600000, 1),
(2637, 389, 'Nkoronko', -0.74300000, 31.99700000, 1),
(2638, 389, 'Rutegama', -0.74200000, 31.99800000, 1),
(2639, 390, 'Cirambo', -0.74100000, 31.99900000, 1),
(2640, 390, 'Kinyota', -0.74000000, 32.00000000, 1),
(2641, 390, 'Kiyange', -0.73900000, 32.00100000, 1),
(2642, 390, 'Maramvya', -0.73800000, 32.00200000, 1),
(2643, 390, 'Rusagara', -0.73700000, 32.00300000, 1),
(2644, 390, 'Rwizingwe', -0.73600000, 32.00400000, 1),
(2645, 391, 'Butaha', -0.73500000, 32.00500000, 1),
(2646, 391, 'Gasera', -0.73400000, 32.00600000, 1),
(2647, 391, 'Gisimbawaga', -0.73300000, 32.00700000, 1),
(2648, 391, 'Kibuye', -0.73200000, 32.00800000, 1),
(2649, 391, 'Mutara', -0.73100000, 32.00900000, 1),
(2650, 392, 'Gasasa', -0.73000000, 32.01000000, 1),
(2651, 392, 'Gitaramuka', -0.72900000, 32.01100000, 1),
(2652, 392, 'Kagwa', -0.72800000, 32.01200000, 1),
(2653, 392, 'Kibenga', -0.72700000, 32.01300000, 1),
(2654, 392, 'Nyarutovu', -0.72600000, 32.01400000, 1),
(2655, 392, 'Rubuga', -0.72500000, 32.01500000, 1),
(2656, 393, 'Gasekanya', -0.72400000, 32.01600000, 1),
(2657, 393, 'Kibumbwe', -0.72300000, 32.01700000, 1),
(2658, 393, 'Mugende', -0.72200000, 32.01800000, 1),
(2659, 393, 'Muvumu', -0.72100000, 32.01900000, 1),
(2660, 393, 'Nyakabugu', -0.72000000, 32.02000000, 1),
(2661, 394, 'burenza', -0.71900000, 32.02100000, 1),
(2662, 394, 'Cigati', -0.71800000, 32.02200000, 1),
(2663, 394, 'Gasenyi', -0.71700000, 32.02300000, 1),
(2664, 394, 'Karamba', -0.71600000, 32.02400000, 1),
(2665, 394, 'Rudaraza', -0.71500000, 32.02500000, 1),
(2666, 394, 'Rutonganikwa', -0.71400000, 32.02600000, 1),
(2667, 394, 'Ruyaga', -0.71300000, 32.02700000, 1),
(2668, 394, 'Shanga', -0.71200000, 32.02800000, 1),
(2669, 395, 'Kigoma', -0.71100000, 32.02900000, 1),
(2670, 395, 'Mubaragaza', -0.71000000, 32.03000000, 1),
(2671, 395, 'Musenga', -0.70900000, 32.03100000, 1),
(2672, 395, 'Sagara', -0.70800000, 32.03200000, 1),
(2673, 395, 'Yagizo', -0.70700000, 32.03300000, 1),
(2674, 396, 'Banda', -0.70600000, 32.03400000, 1),
(2675, 396, 'Kajeri', -0.70500000, 32.03500000, 1),
(2676, 396, 'Karuri', -0.70400000, 32.03600000, 1),
(2677, 396, 'Muhweza', -0.70300000, 32.03700000, 1),
(2678, 396, 'Nyamabega', -0.70200000, 32.03800000, 1),
(2679, 396, 'Rukamba', -0.70100000, 32.03900000, 1),
(2680, 397, 'Bibara', -0.70000000, 32.04000000, 1),
(2681, 397, 'Kanyinya', -0.69900000, 32.04100000, 1),
(2682, 397, 'Rabiro', -0.69800000, 32.04200000, 1),
(2683, 397, 'Rwangara', -0.69700000, 32.04300000, 1),
(2684, 398, 'Bikinga', -0.69600000, 32.04400000, 1),
(2685, 398, 'Bugwana', -0.69500000, 32.04500000, 1),
(2686, 398, 'Kigozi', -0.69400000, 32.04600000, 1),
(2687, 398, 'Ngayane', -0.69300000, 32.04700000, 1),
(2688, 398, 'Ntunda', -0.69200000, 32.04800000, 1),
(2689, 399, 'Quartier Kigoma', -0.69100000, 32.04900000, 1),
(2690, 399, 'Quartier Kigwati', -0.69000000, 32.05000000, 1),
(2691, 399, 'Quartier Nyamugari', -0.68900000, 32.05100000, 1),
(2692, 399, 'Quartier Rubimba', -0.68800000, 32.05200000, 1),
(2693, 400, 'Gahahe', -0.68700000, 32.05300000, 1),
(2694, 400, 'Gahashi', -0.68600000, 32.05400000, 1),
(2695, 400, 'Gitandu', -0.68500000, 32.05500000, 1),
(2696, 400, 'Nyaruhinda', -0.68400000, 32.05600000, 1),
(2697, 400, 'Ruhata', -0.68300000, 32.05700000, 1),
(2698, 401, 'Bonero', -0.68200000, 32.05800000, 1),
(2699, 401, 'Burenza', -0.68100000, 32.05900000, 1),
(2700, 401, 'Mubaya', -0.68000000, 32.06000000, 1),
(2701, 401, 'Munyanira', -0.67900000, 32.06100000, 1),
(2702, 401, 'Rugazi', -0.67800000, 32.06200000, 1),
(2703, 401, 'Rusengo', -0.67700000, 32.06300000, 1),
(2704, 401, 'Tambi', -0.67600000, 32.06400000, 1),
(2705, 402, 'Buhangura', -0.67500000, 32.06500000, 1),
(2706, 402, 'Burenza', -0.67400000, 32.06600000, 1),
(2707, 402, 'Janga', -0.67300000, 32.06700000, 1),
(2708, 402, 'Masama', -0.67200000, 32.06800000, 1),
(2709, 402, 'Mubuga', -0.67100000, 32.06900000, 1),
(2710, 402, 'Rugari', -0.67000000, 32.07000000, 1),
(2711, 403, 'Gasenyi', -0.66900000, 32.07100000, 1),
(2712, 403, 'Kabuye', -0.66800000, 32.07200000, 1),
(2713, 403, 'Kigabiro', -0.66700000, 32.07300000, 1),
(2714, 403, 'Murehe', -0.66600000, 32.07400000, 1),
(2715, 403, 'Nete', -0.66500000, 32.07500000, 1),
(2716, 403, 'Taba', -0.66400000, 32.07600000, 1),
(2717, 404, 'Kivyeyi', -0.66300000, 32.07700000, 1),
(2718, 404, 'Martyazo', -0.66200000, 32.07800000, 1),
(2719, 404, 'Murambi', -0.66100000, 32.07900000, 1),
(2720, 404, 'Nyagisozi', -0.66000000, 32.08000000, 1),
(2721, 404, 'Quartier Gatabo', -0.65900000, 32.08100000, 1),
(2722, 405, 'Gahweza', -0.65800000, 32.08200000, 1),
(2723, 405, 'Kanyami', -0.65700000, 32.08300000, 1),
(2724, 405, 'Musongati', -0.65600000, 32.08400000, 1),
(2725, 405, 'Ngara', -0.65500000, 32.08500000, 1),
(2726, 406, 'Burenza', -0.65400000, 32.08600000, 1),
(2727, 406, 'Kanerwa', -0.65300000, 32.08700000, 1),
(2728, 406, 'Kiganda', -0.65200000, 32.08800000, 1),
(2729, 406, 'Nkomwe', -0.65100000, 32.08900000, 1),
(2730, 407, 'Bupfunda', -0.65000000, 32.09000000, 1),
(2731, 407, 'Munyinya', -0.64900000, 32.09100000, 1),
(2732, 407, 'Murinzi', -0.64800000, 32.09200000, 1),
(2733, 407, 'Musave', -0.64700000, 32.09300000, 1),
(2734, 407, 'Mushikamo', -0.64600000, 32.09400000, 1),
(2735, 407, 'Nkonyovu', -0.64500000, 32.09500000, 1),
(2736, 407, 'Nyakararo', -0.64400000, 32.09600000, 1),
(2737, 407, 'Nyamitwenzi', -0.64300000, 32.09700000, 1),
(2738, 408, 'Kayange', -0.64200000, 32.09800000, 1),
(2739, 408, 'Renga', -0.64100000, 32.09900000, 1),
(2740, 408, 'Rubumba', -0.64000000, 32.10000000, 1),
(2741, 408, 'Ruvumu', -0.63900000, 32.10100000, 1),
(2742, 409, 'Bubanda', -0.63800000, 32.10200000, 1),
(2743, 409, 'Camumandu', -0.63700000, 32.10300000, 1),
(2744, 409, 'Cumba', -0.63600000, 32.10400000, 1),
(2745, 409, 'Gashingwa', -0.63500000, 32.10500000, 1),
(2746, 409, 'Kinyoni', -0.63400000, 32.10600000, 1),
(2747, 409, 'Marumane', -0.63300000, 32.10700000, 1),
(2748, 409, 'Munanira', -0.63200000, 32.10800000, 1),
(2749, 409, 'Nyarukere', -0.63100000, 32.10900000, 1),
(2750, 409, 'Nyarunazi', -0.63000000, 32.11000000, 1),
(2751, 409, 'Rutegama', -0.62900000, 32.11100000, 1),
(2752, 409, 'Quartier Buryohe', -0.62800000, 32.11200000, 1),
(2753, 409, 'Quartier Gasange', -0.62700000, 32.11300000, 1),
(2754, 410, 'Busimba', -0.62600000, 32.11400000, 1),
(2755, 410, 'Kibogoye', -0.62500000, 32.11500000, 1),
(2756, 410, 'Mpehe', -0.62400000, 32.11600000, 1),
(2757, 410, 'Quartier Bugarama', -0.62300000, 32.11700000, 1),
(2758, 411, 'Gahaga', -0.62200000, 32.11800000, 1),
(2759, 411, 'Gikonge', -0.62100000, 32.11900000, 1),
(2760, 411, 'Kivogero', -0.62000000, 32.12000000, 1),
(2761, 411, 'Rweteto', -0.61900000, 32.12100000, 1),
(2762, 411, 'Shumba', -0.61800000, 32.12200000, 1),
(2763, 412, 'Birwana', -0.61700000, 32.12300000, 1),
(2764, 412, 'Buyaga', -0.61600000, 32.12400000, 1),
(2765, 412, 'Kirika', -0.61500000, 32.12500000, 1),
(2766, 412, 'Rabiro', -0.61400000, 32.12600000, 1),
(2767, 412, 'Quartier Kibumbu', -0.61300000, 32.12700000, 1),
(2768, 413, 'Kigina', -0.61200000, 32.12800000, 1),
(2769, 413, 'Kirembera', -0.61100000, 32.12900000, 1),
(2770, 413, 'Kiziba', -0.61000000, 32.13000000, 1),
(2771, 413, 'Mbuye', -0.60900000, 32.13100000, 1),
(2772, 413, 'Migezi', -0.60800000, 32.13200000, 1),
(2773, 413, 'Mugerera', -0.60700000, 32.13300000, 1),
(2774, 413, 'Murama', -0.60600000, 32.13400000, 1),
(2775, 413, 'Mwegera', -0.60500000, 32.13500000, 1),
(2776, 413, 'Nyakijwira', -0.60400000, 32.13600000, 1),
(2777, 413, 'Rwuya', -0.60300000, 32.13700000, 1),
(2778, 413, 'Teka', -0.60200000, 32.13800000, 1),
(2779, 413, 'Temere', -0.60100000, 32.13900000, 1),
(2780, 414, 'Biganda', -0.60000000, 32.14000000, 1),
(2781, 414, 'Burambana', -0.59900000, 32.14100000, 1),
(2782, 414, 'Masango', -0.59800000, 32.14200000, 1),
(2783, 414, 'Murambi', -0.59700000, 32.14300000, 1),
(2784, 414, 'Muramvya', -0.59600000, 32.14400000, 1),
(2785, 414, 'Musagara', -0.59500000, 32.14500000, 1),
(2786, 414, 'Quartier Mubarazi', -0.59400000, 32.14600000, 1),
(2787, 414, 'Quartier Muramvya', -0.59300000, 32.14700000, 1),
(2788, 415, 'Buhorwa', -0.59200000, 32.14800000, 1),
(2789, 415, 'Gaharo', -0.59100000, 32.14900000, 1),
(2790, 415, 'Kiziguro', -0.59000000, 32.15000000, 1),
(2791, 415, 'Musumba', -0.58900000, 32.15100000, 1),
(2792, 415, 'Nyarucamo', -0.58800000, 32.15200000, 1),
(2793, 416, 'Gatwaro', -0.58700000, 32.15300000, 1),
(2794, 416, 'Mirinzi', -0.58600000, 32.15400000, 1),
(2795, 416, 'Mubira', -0.58500000, 32.15500000, 1),
(2796, 416, 'Remera', -0.58400000, 32.15600000, 1),
(2797, 416, 'Ruhinga', -0.58300000, 32.15700000, 1),
(2798, 416, 'Shombo', -0.58200000, 32.15800000, 1),
(2799, 417, 'Buburu', -0.58100000, 32.15900000, 1),
(2800, 417, 'Gitaramuka', -0.58000000, 32.16000000, 1),
(2801, 417, 'Kiganda', -0.57900000, 32.16100000, 1),
(2802, 417, 'Mabaya', -0.57800000, 32.16200000, 1),
(2803, 417, 'Musumba', -0.57700000, 32.16300000, 1),
(2804, 418, 'Bwakira', -0.57600000, 32.16400000, 1),
(2805, 418, 'Kanyami', -0.57500000, 32.16500000, 1),
(2806, 418, 'Mago', -0.57400000, 32.16600000, 1),
(2807, 418, 'Maramvya', -0.57300000, 32.16700000, 1),
(2808, 418, 'Nyagitongati', -0.57200000, 32.16800000, 1),
(2809, 418, 'Nyamugari', -0.57100000, 32.16900000, 1),
(2810, 418, 'Rwuya', -0.57000000, 32.17000000, 1),
(2811, 419, 'Buburu', -0.56900000, 32.17100000, 1),
(2812, 419, 'Gisozi', -0.56800000, 32.17200000, 1),
(2813, 419, 'Kibimba', -0.56700000, 32.17300000, 1),
(2814, 419, 'Musivya', -0.56600000, 32.17400000, 1),
(2815, 419, 'Ndava', -0.56500000, 32.17500000, 1),
(2816, 419, 'Nyamiyaga', -0.56400000, 32.17600000, 1),
(2817, 419, 'Rweza', -0.56300000, 32.17700000, 1),
(2818, 419, 'Quartier Nyarukinya', -0.56200000, 32.17800000, 1),
(2819, 419, 'Quartier Ruyange', -0.56100000, 32.17900000, 1),
(2820, 420, 'Buhabwa', -0.56000000, 32.18000000, 1),
(2821, 420, 'Kanka', -0.55900000, 32.18100000, 1),
(2822, 420, 'Kariba', -0.55800000, 32.18200000, 1),
(2823, 420, 'Masango', -0.55700000, 32.18300000, 1),
(2824, 420, 'Nyabisiga', -0.55600000, 32.18400000, 1),
(2825, 420, 'Rubamvye', -0.55500000, 32.18500000, 1),
(2826, 421, 'Benja', -0.55400000, 32.18600000, 1),
(2827, 421, 'Kibogoye', -0.55300000, 32.18700000, 1),
(2828, 421, 'Musama', -0.55200000, 32.18800000, 1),
(2829, 421, 'Ruramba', -0.55100000, 32.18900000, 1),
(2830, 421, 'Rusivya', -0.55000000, 32.19000000, 1),
(2831, 421, 'Ruvumu', -0.54900000, 32.19100000, 1),
(2832, 421, 'Saswe', -0.54800000, 32.19200000, 1),
(2833, 422, 'Gasenyi', -0.54700000, 32.19300000, 1),
(2834, 422, 'Kirambi', -0.54600000, 32.19400000, 1),
(2835, 422, 'Makamba', -0.54500000, 32.19500000, 1),
(2836, 422, 'Nyamiyaga', -0.54400000, 32.19600000, 1),
(2837, 422, 'Nyamurenge', -0.54300000, 32.19700000, 1),
(2838, 422, 'Rwintare', -0.54200000, 32.19800000, 1),
(2839, 423, 'Bisoro', -0.54100000, 32.19900000, 1),
(2840, 423, 'Gitunga', -0.54000000, 32.20000000, 1),
(2841, 423, 'Migende', -0.53900000, 32.20100000, 1),
(2842, 423, 'Murehe', -0.53800000, 32.20200000, 1),
(2843, 423, 'Muyebe', -0.53700000, 32.20300000, 1),
(2844, 423, 'Nyakibari', -0.53600000, 32.20400000, 1),
(2845, 423, 'Rwankangoma', -0.53500000, 32.20500000, 1),
(2846, 424, 'Bisha', -0.53400000, 32.20600000, 1),
(2847, 424, 'Gatare', -0.53300000, 32.20700000, 1),
(2848, 424, 'Gihinga', -0.53200000, 32.20800000, 1),
(2849, 424, 'Kizi', -0.53100000, 32.20900000, 1),
(2850, 424, 'Ngara', -0.53000000, 32.21000000, 1),
(2851, 424, 'Nkundusi', -0.52900000, 32.21100000, 1),
(2852, 424, 'Rurtyazo', -0.52800000, 32.21200000, 1),
(2853, 424, 'Quartier Gasumo', -0.52700000, 32.21300000, 1),
(2854, 424, 'Quartier Kagoma', -0.52600000, 32.21400000, 1),
(2855, 424, 'Quartier Ruvumera', -0.52500000, 32.21500000, 1),
(2856, 425, 'Butegana', -0.52400000, 32.21600000, 1),
(2857, 425, 'Kiyange', -0.52300000, 32.21700000, 1),
(2858, 425, 'Mugero', -0.52200000, 32.21800000, 1),
(2859, 425, 'Musimbwe', -0.52100000, 32.21900000, 1),
(2860, 425, 'Nyagahwabare', -0.52000000, 32.22000000, 1),
(2861, 425, 'Nyakirwa', -0.51900000, 32.22100000, 1),
(2862, 426, 'Kirika', -0.51800000, 32.22200000, 1),
(2863, 426, 'Kivoga', -0.51700000, 32.22300000, 1),
(2864, 426, 'Mashunzi', -0.51600000, 32.22400000, 1),
(2865, 426, 'Munanira', -0.51500000, 32.22500000, 1),
(2866, 427, 'Fota', -0.51400000, 32.22600000, 1),
(2867, 427, 'Gahondo', -0.51300000, 32.22700000, 1),
(2868, 427, 'Gatsinga', -0.51200000, 32.22800000, 1),
(2869, 427, 'Kigarama', -0.51100000, 32.22900000, 1),
(2870, 427, 'Ngorore', -0.51000000, 32.23000000, 1),
(2871, 428, 'Buhogo', -0.50900000, 32.23100000, 1),
(2872, 428, 'Kavumu', -0.50800000, 32.23200000, 1),
(2873, 428, 'Kibungere', -0.50700000, 32.23300000, 1),
(2874, 428, 'Martyazo', -0.50600000, 32.23400000, 1),
(2875, 428, 'Musongati', -0.50500000, 32.23500000, 1),
(2876, 428, 'Muyebe', -0.50400000, 32.23600000, 1),
(2877, 429, 'Kamushiha', -0.50300000, 32.23700000, 1),
(2878, 429, 'Matongo', -0.50200000, 32.23800000, 1),
(2879, 429, 'Ngoro', -0.50100000, 32.23900000, 1),
(2880, 429, 'Nyabisaka', -0.50000000, 32.24000000, 1),
(2881, 429, 'Nyamurenge', -0.49900000, 32.24100000, 1),
(2882, 430, 'Butegeye', -0.49800000, 32.24200000, 1),
(2883, 430, 'Gihoma', -0.49700000, 32.24300000, 1),
(2884, 430, 'Kivomwa', -0.49600000, 32.24400000, 1),
(2885, 430, 'Mbogora', -0.49500000, 32.24500000, 1),
(2886, 430, 'Migera', -0.49400000, 32.24600000, 1),
(2887, 430, 'Muhaganya', -0.49300000, 32.24700000, 1),
(2888, 430, 'Muyange', -0.49200000, 32.24800000, 1),
(2889, 430, 'Nyarubayi', -0.49100000, 32.24900000, 1),
(2890, 431, 'Gitaramuka', -0.49000000, 32.25000000, 1),
(2891, 431, 'Kibogoye', -0.48900000, 32.25100000, 1),
(2892, 431, 'Kibungo', -0.48800000, 32.25200000, 1),
(2893, 431, 'Magamba', -0.48700000, 32.25300000, 1),
(2894, 431, 'Munago', -0.48600000, 32.25400000, 1),
(2895, 431, 'Nyamitore', -0.48500000, 32.25500000, 1),
(2896, 432, 'Bugera', -0.48400000, 32.25600000, 1),
(2897, 432, 'Higiro', -0.48300000, 32.25700000, 1),
(2898, 432, 'Kabogi', -0.48200000, 32.25800000, 1),
(2899, 432, 'Murago', -0.48100000, 32.25900000, 1),
(2900, 432, 'Rango', -0.48000000, 32.26000000, 1),
(2901, 433, 'Gisirtye', -0.47900000, 32.26100000, 1),
(2902, 433, 'Iteka', -0.47800000, 32.26200000, 1),
(2903, 433, 'Kirambi', -0.47700000, 32.26300000, 1),
(2904, 433, 'Musama', -0.47600000, 32.26400000, 1),
(2905, 433, 'Nyamibanga', -0.47500000, 32.26500000, 1),
(2906, 434, 'Butazi', -0.47400000, 32.26600000, 1),
(2907, 434, 'Gitaba', -0.47300000, 32.26700000, 1),
(2908, 434, 'Mpanuka', -0.47200000, 32.26800000, 1),
(2909, 434, 'Muyogoro', -0.47100000, 32.26900000, 1),
(2910, 434, 'Ndava', -0.47000000, 32.27000000, 1),
(2911, 435, 'Gatwe', -0.46900000, 32.27100000, 1),
(2912, 435, 'Kivuzo', -0.46800000, 32.27200000, 1),
(2913, 435, 'Miterama', -0.46700000, 32.27300000, 1),
(2914, 435, 'Mubuga', -0.46600000, 32.27400000, 1),
(2915, 435, 'Murama', -0.46500000, 32.27500000, 1),
(2916, 435, 'Taba', -0.46400000, 32.27600000, 1),
(2917, 436, 'Bugorora', -0.46300000, 32.27700000, 1),
(2918, 436, 'Bunyange', -0.46200000, 32.27800000, 1),
(2919, 436, 'Kiga', -0.46100000, 32.27900000, 1),
(2920, 436, 'Nkurunzi', -0.46000000, 32.28000000, 1),
(2921, 436, 'Nyamigogo', -0.45900000, 32.28100000, 1),
(2922, 437, 'Gikebuka', -0.45800000, 32.28200000, 1),
(2923, 437, 'Kibimba', -0.45700000, 32.28300000, 1),
(2924, 437, 'Kiyege', -0.45600000, 32.28400000, 1),
(2925, 437, 'Martyazo', -0.45500000, 32.28500000, 1),
(2926, 437, 'Mpumbu', -0.45400000, 32.28600000, 1),
(2927, 437, 'Mureba', -0.45300000, 32.28700000, 1),
(2928, 437, 'Nyagashanga', -0.45200000, 32.28800000, 1);
INSERT INTO `collines` (`id_colline`, `id_zone`, `colline_name`, `latitude`, `longitude`, `est_actif`) VALUES
(2929, 437, 'Nyamugari', -0.45100000, 32.28900000, 1),
(2930, 437, 'Rusaka', -0.45000000, 32.29000000, 1),
(2931, 437, 'Shana', -0.44900000, 32.29100000, 1),
(2932, 437, 'Quartier Gasekebuye', -0.44800000, 32.29200000, 1),
(2933, 437, 'Quartier Gihini', -0.44700000, 32.29300000, 1),
(2934, 438, 'Kinyovu', -0.44600000, 32.29400000, 1),
(2935, 438, 'Mahonda', -0.44500000, 32.29500000, 1),
(2936, 438, 'Murambi', -0.44400000, 32.29600000, 1),
(2937, 438, 'Namande', -0.44300000, 32.29700000, 1),
(2938, 438, 'Rucunda', -0.44200000, 32.29800000, 1),
(2939, 439, 'Gatonde', -0.44100000, 32.29900000, 1),
(2940, 439, 'Gitibu', -0.44000000, 32.30000000, 1),
(2941, 439, 'Mazita', -0.43900000, 32.30100000, 1),
(2942, 439, 'Nyenzi', -0.43800000, 32.30200000, 1),
(2943, 439, 'Ruvumu', -0.43700000, 32.30300000, 1),
(2944, 439, 'Taba', -0.43600000, 32.30400000, 1),
(2945, 440, 'Bikinga', -0.43500000, 32.30500000, 1),
(2946, 440, 'Gihogazi', -0.43400000, 32.30600000, 1),
(2947, 440, 'Kibezi', -0.43300000, 32.30700000, 1),
(2948, 440, 'Mugero', -0.43200000, 32.30800000, 1),
(2949, 440, 'Murago', -0.43100000, 32.30900000, 1),
(2950, 440, 'Ramba', -0.43000000, 32.31000000, 1),
(2951, 441, 'Butwe', -0.42900000, 32.31100000, 1),
(2952, 441, 'Gaharo', -0.42800000, 32.31200000, 1),
(2953, 441, 'Gikombe', -0.42700000, 32.31300000, 1),
(2954, 442, 'Kabwira', -0.42600000, 32.31400000, 1),
(2955, 442, 'Mugoboka', -0.42500000, 32.31500000, 1),
(2956, 442, 'Ruharo', -0.42400000, 32.31600000, 1),
(2957, 442, 'Rusasa', -0.42300000, 32.31700000, 1),
(2958, 442, 'Ruyogoro', -0.42200000, 32.31800000, 1),
(2959, 442, 'Rwimbogo', -0.42100000, 32.31900000, 1),
(2960, 443, 'Butamenwa', -0.42000000, 32.32000000, 1),
(2961, 443, 'Masama', -0.41900000, 32.32100000, 1),
(2962, 443, 'Rugwiza', -0.41800000, 32.32200000, 1),
(2963, 443, 'Ruhuma', -0.41700000, 32.32300000, 1),
(2964, 443, 'Rwinka', -0.41600000, 32.32400000, 1),
(2965, 444, 'Kizingoma', -0.41500000, 32.32500000, 1),
(2966, 444, 'Mugogo', -0.41400000, 32.32600000, 1),
(2967, 444, 'Ruvumu', -0.41300000, 32.32700000, 1),
(2968, 444, 'Ruyaga', -0.41200000, 32.32800000, 1),
(2969, 444, 'Taba', -0.41100000, 32.32900000, 1),
(2970, 445, 'Gahororo', -0.41000000, 32.33000000, 1),
(2971, 445, 'Kivoga', -0.40900000, 32.33100000, 1),
(2972, 445, 'Munanira', -0.40800000, 32.33200000, 1),
(2973, 445, 'Mushikanwa', -0.40700000, 32.33300000, 1),
(2974, 445, 'Ruganira', -0.40600000, 32.33400000, 1),
(2975, 445, 'Rutegama', -0.40500000, 32.33500000, 1),
(2976, 446, 'Bukirasazi', -0.40400000, 32.33600000, 1),
(2977, 446, 'Gatabo', -0.40300000, 32.33700000, 1),
(2978, 446, 'Gitaramuka', -0.40200000, 32.33800000, 1),
(2979, 446, 'Kiryama', -0.40100000, 32.33900000, 1),
(2980, 446, 'Nyabibuye', -0.40000000, 32.34000000, 1),
(2981, 447, 'Maramvya', -0.39900000, 32.34100000, 1),
(2982, 447, 'Mbabazi', -0.39800000, 32.34200000, 1),
(2983, 447, 'Mubanga', -0.39700000, 32.34300000, 1),
(2984, 447, 'Ngugu', -0.39600000, 32.34400000, 1),
(2985, 447, 'Nyarunazi', -0.39500000, 32.34500000, 1),
(2986, 447, 'Ruyogoro', -0.39400000, 32.34600000, 1),
(2987, 447, 'Rwandagaro', -0.39300000, 32.34700000, 1),
(2988, 447, 'Quartier Gatamba', -0.39200000, 32.34800000, 1),
(2989, 448, 'Kigora', -0.39100000, 32.34900000, 1),
(2990, 448, 'Kiyange', -0.39000000, 32.35000000, 1),
(2991, 448, 'Muhigo', -0.38900000, 32.35100000, 1),
(2992, 448, 'Muhororo', -0.38800000, 32.35200000, 1),
(2993, 449, 'Bihembe', -0.38700000, 32.35300000, 1),
(2994, 449, 'Gasenyi', -0.38600000, 32.35400000, 1),
(2995, 449, 'Gasivya', -0.38500000, 32.35500000, 1),
(2996, 449, 'Muzenga', -0.38400000, 32.35600000, 1),
(2997, 449, 'Nyamiyaga', -0.38300000, 32.35700000, 1),
(2998, 449, 'Rusamaza', -0.38200000, 32.35800000, 1),
(2999, 450, 'Kivoga', -0.38100000, 32.35900000, 1),
(3000, 450, 'Mujenjwa', -0.38000000, 32.36000000, 1),
(3001, 450, 'Rusi', -0.37900000, 32.36100000, 1),
(3002, 450, 'Shombo', -0.37800000, 32.36200000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `numero_commande` varchar(50) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_adresse_livraison` int(11) DEFAULT NULL,
  `sous_total` decimal(12,2) NOT NULL,
  `frais_livraison` decimal(10,2) DEFAULT 0.00,
  `montant_reduction` decimal(10,2) DEFAULT 0.00,
  `montant_total` decimal(12,2) NOT NULL,
  `code_coupon` varchar(50) DEFAULT NULL,
  `reduction_coupon` decimal(10,2) DEFAULT 0.00,
  `id_mode_payement` int(11) NOT NULL,
  `statut_paiement` enum('en_attente','en_cours','paye','echoue','rembourse') DEFAULT 'en_attente',
  `date_paiement` timestamp NULL DEFAULT NULL,
  `type_livraison` enum('domicile','point_relais') DEFAULT 'domicile',
  `id_point_relais` int(11) DEFAULT NULL,
  `id_transporteur` int(11) DEFAULT NULL,
  `statut_commande` enum('en_attente','confirme','en_preparation','expedie','en_livraison','livre','annule','retourne') DEFAULT 'en_attente',
  `numero_suivi` varchar(100) DEFAULT NULL,
  `date_expedition` timestamp NULL DEFAULT NULL,
  `date_livraison_prevue` timestamp NULL DEFAULT NULL,
  `date_livraison_reelle` timestamp NULL DEFAULT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `reception_confirmee` tinyint(1) DEFAULT 0,
  `date_confirmation_reception` timestamp NULL DEFAULT NULL,
  `note_client` text DEFAULT NULL,
  `note_interne` text DEFAULT NULL,
  `canal_commande` enum('web','mobile_android','mobile_ios','ussd') DEFAULT 'web',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `communes` (
  `id_commune` int(11) NOT NULL,
  `id_province` int(11) NOT NULL,
  `commune_name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `communes`
--

INSERT INTO `communes` (`id_commune`, `id_province`, `commune_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 1, 'Butaganzwa', -3.55000000, 30.45000000, 1),
(2, 1, 'Butihinda', -2.90000000, 30.50000000, 1),
(3, 1, 'Cankuzo', -3.21730000, 30.55800000, 1),
(4, 1, 'Gisagara', -3.30000000, 30.70000000, 1),
(5, 1, 'Gisuru', -3.28000000, 29.46000000, 1),
(6, 1, 'Muyinga', -2.84520000, 30.34140000, 1),
(7, 1, 'Ruyigi', -3.45890000, 30.24860000, 1),
(8, 2, 'Bubanza', -3.08690000, 29.39040000, 1),
(9, 2, 'Bukinanyana', -3.20000000, 29.54000000, 1),
(10, 2, 'Cibitoke', -2.88640000, 29.11930000, 1),
(11, 2, 'Isare', -3.45000000, 29.45000000, 1),
(12, 2, 'Mpanda', -3.22000000, 29.38000000, 1),
(13, 2, 'Mugere', -3.12000000, 29.62000000, 1),
(14, 2, 'Mugina', -3.10000000, 29.64000000, 1),
(15, 2, 'Muhuta', -3.08000000, 29.66000000, 1),
(16, 2, 'Mukaza', -3.38460000, 29.36140000, 1),
(17, 2, 'Ntahangwa', -3.31560000, 29.37000000, 1),
(18, 2, 'Rwibaga', -3.02000000, 29.72000000, 1),
(19, 3, 'Bururi', -3.20380000, 29.62410000, 1),
(20, 3, 'Makamba', -4.13490000, 29.87890000, 1),
(21, 3, 'Matana', -3.85000000, 29.70000000, 1),
(22, 3, 'Musongati', -2.94000000, 29.80000000, 1),
(23, 3, 'Nyanza', -4.13330000, 29.80000000, 1),
(24, 3, 'Rumonge', -3.97490000, 29.43990000, 1),
(25, 3, 'Rutana', -3.96900000, 30.00590000, 1),
(26, 4, 'Busoni', -2.50000000, 30.15000000, 1),
(27, 4, 'Kayanza', -2.49120000, 29.63080000, 1),
(28, 4, 'Kiremba', -2.82000000, 29.92000000, 1),
(29, 4, 'Kirundo', -2.58460000, 30.09560000, 1),
(30, 4, 'Matongo', -2.78000000, 29.96000000, 1),
(31, 4, 'Muhanga', -2.76000000, 29.98000000, 1),
(32, 4, 'Ngozi', -2.88570000, 29.82990000, 1),
(33, 4, 'Tangara', -2.85000000, 30.00000000, 1),
(34, 5, 'Bugendana', -2.70000000, 30.04000000, 1),
(35, 5, 'Gishubi', -2.68000000, 30.06000000, 1),
(36, 5, 'Gitega', -3.42640000, 29.93060000, 1),
(37, 5, 'Karusi', -3.26410000, 29.16210000, 1),
(38, 5, 'Kiganda', -2.62000000, 30.12000000, 1),
(39, 5, 'Muramvya', -3.26880000, 29.60690000, 1),
(40, 5, 'Mwaro', -3.51810000, 29.70000000, 1),
(41, 5, 'Nyabihanga', -2.56000000, 30.18000000, 1),
(42, 5, 'Shombo', -2.54000000, 30.20000000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `config_paiement_vendeur`
--

CREATE TABLE `config_paiement_vendeur` (
  `id_config` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `methode_principale` enum('mobile_money','virement_bancaire') NOT NULL,
  `operateur_mobile` varchar(50) DEFAULT NULL,
  `numero_mobile_money` varchar(20) DEFAULT NULL,
  `nom_abonne_mobile` varchar(200) DEFAULT NULL,
  `nom_titulaire` varchar(200) DEFAULT NULL,
  `numero_compte` varchar(50) DEFAULT NULL,
  `nom_banque` varchar(100) DEFAULT NULL,
  `est_verifie` tinyint(1) DEFAULT 0,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `coupons` (
  `id_coupon` int(11) NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_reduction` enum('pourcentage','montant_fixe','livraison_gratuite') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valeur_reduction` decimal(12,2) NOT NULL,
  `montant_min_achat` decimal(12,2) DEFAULT NULL,
  `montant_max_reduction` decimal(12,2) DEFAULT NULL,
  `limite_utilisation` int(11) DEFAULT NULL,
  `nombre_utilisations` int(11) DEFAULT 0,
  `limite_par_utilisateur` int(11) DEFAULT 1,
  `date_debut` timestamp NULL DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `applicable_a` enum('tout','categories','produits','vendeurs') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'tout',
  `ids_applicables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents_vendeur`
--

CREATE TABLE `documents_vendeur` (
  `id_document` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `type_document` enum('carte_identite','passeport','licence_commerce','attestation_fiscale','justificatif_domicile') NOT NULL,
  `numero_document` varchar(100) DEFAULT NULL,
  `fichier_document` varchar(500) NOT NULL,
  `statut_verification` enum('en_attente','verifie','refuse') DEFAULT 'en_attente',
  `date_verification` timestamp NULL DEFAULT NULL,
  `verifie_par` int(11) DEFAULT NULL,
  `motif_refus` text DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `date_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents_vendeur`
--

INSERT INTO `documents_vendeur` (`id_document`, `id_vendeur`, `type_document`, `numero_document`, `fichier_document`, `statut_verification`, `date_verification`, `verifie_par`, `motif_refus`, `date_expiration`, `date_upload`) VALUES
(1, 1, 'passeport', '023823', 'uploads/documents_vendeurs/document_20260423_152906_69ea3ac268d03.png', 'en_attente', NULL, NULL, NULL, '2026-04-23', '2026-04-23 13:29:06');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations_vendeurs`
--

CREATE TABLE `evaluations_vendeurs` (
  `id_evaluation` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `note_globale` tinyint(1) NOT NULL,
  `note_communication` tinyint(1) DEFAULT NULL,
  `note_livraison` tinyint(1) DEFAULT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_approuve` tinyint(1) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_statut_commande`
--

CREATE TABLE `historique_statut_commande` (
  `id_historique` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `modifie_par` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `images_produit` (
  `id_image` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `url_image` varchar(500) NOT NULL,
  `url_miniature` varchar(500) DEFAULT NULL,
  `texte_alt` varchar(255) DEFAULT NULL,
  `est_principale` tinyint(1) DEFAULT 0,
  `ordre_affichage` int(11) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `ips_bloquees` (
  `id_ip` int(11) NOT NULL,
  `adresse_ip` varchar(45) NOT NULL,
  `motif` text DEFAULT NULL,
  `date_blocage` timestamp NOT NULL DEFAULT current_timestamp(),
  `bloque_par` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liste_souhaits`
--

CREATE TABLE `liste_souhaits` (
  `id_souhait` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `litiges_commandes` (
  `id_litige` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_plaignant` int(11) NOT NULL,
  `type_plaignant` enum('acheteur','vendeur') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_defendeur` int(11) NOT NULL,
  `raison` enum('non_recu','endommage','non_conforme','paiement','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pieces_jointes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `statut` enum('ouvert','en_mediation','resolu_acheteur','resolu_vendeur','ferme') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ouvert',
  `mediateur_id` int(11) DEFAULT NULL,
  `decision` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant_rembourse` decimal(12,2) DEFAULT NULL,
  `date_resolution` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs_audit`
--

CREATE TABLE `logs_audit` (
  `id_log` bigint(20) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `type_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_cible` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cible` int(11) DEFAULT NULL,
  `valeurs_avant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `valeurs_apres` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mode_payement`
--

CREATE TABLE `mode_payement` (
  `id_mode_payement` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `type` enum('mobile_money','carte_bancaire','virement','especes_livraison') NOT NULL DEFAULT 'mobile_money',
  `logo_url` varchar(500) DEFAULT NULL,
  `frais_fixe` decimal(10,2) DEFAULT 0.00,
  `frais_pourcentage` decimal(5,2) DEFAULT 0.00,
  `instructions` text DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `ordre_affichage` int(11) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `newsletter_abonnes` (
  `id_abonne` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type_canal` enum('sms','push','in_app') NOT NULL,
  `categorie` enum('commande','paiement','livraison','securite','systeme') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `est_lue` tinyint(1) DEFAULT 0,
  `date_lecture` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements_vendeurs`
--

CREATE TABLE `paiements_vendeurs` (
  `id_paiement` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `id_config_paiement` int(11) DEFAULT NULL,
  `date_debut_periode` date NOT NULL,
  `date_fin_periode` date NOT NULL,
  `nombre_commandes` int(11) DEFAULT 0,
  `total_revenus` decimal(15,2) DEFAULT 0.00,
  `total_commissions` decimal(15,2) DEFAULT 0.00,
  `total_remboursements` decimal(15,2) DEFAULT 0.00,
  `montant_net` decimal(15,2) NOT NULL,
  `statut` enum('en_attente','en_cours','paye','echoue') DEFAULT 'en_attente',
  `date_paiement` timestamp NULL DEFAULT NULL,
  `reference_transaction` varchar(100) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiements_vendeurs`
--

INSERT INTO `paiements_vendeurs` (`id_paiement`, `id_vendeur`, `id_config_paiement`, `date_debut_periode`, `date_fin_periode`, `nombre_commandes`, `total_revenus`, `total_commissions`, `total_remboursements`, `montant_net`, `statut`, `date_paiement`, `reference_transaction`, `date_creation`) VALUES
(1, 1, 2, '2026-03-01', '2026-03-31', 25, 12500.00, 1250.00, 0.00, 11250.00, 'paye', '2026-04-05 08:30:00', 'VIREMENT_BNP_MARS_001', '2026-04-23 15:08:53');

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE `paniers` (
  `id_panier` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `points_relais` (
  `id_point` int(11) NOT NULL,
  `nom` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('boutique_partenaire','kiosque','bureau_poste') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'boutique_partenaire',
  `adresse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_commune` int(11) DEFAULT NULL,
  `id_quartier` int(11) DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `horaires` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `capacite_max` int(11) DEFAULT 50,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `id_vendeur` int(11) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `sku` varchar(100) NOT NULL,
  `code_produit` varchar(50) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `slug_produit` varchar(300) NOT NULL,
  `description_courte` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `marque` varchar(100) DEFAULT NULL,
  `prix_base` decimal(12,2) NOT NULL,
  `prix_promo` decimal(12,2) DEFAULT NULL,
  `date_debut_promo` timestamp NULL DEFAULT NULL,
  `date_fin_promo` timestamp NULL DEFAULT NULL,
  `quantite_actuelle` int(11) DEFAULT 0,
  `seuil_stock_bas` int(11) DEFAULT 5,
  `statut_stock` enum('en_stock','stock_bas','rupture_stock') DEFAULT 'en_stock',
  `poids_kg` decimal(8,3) DEFAULT NULL,
  `longueur_cm` decimal(8,2) DEFAULT NULL,
  `largeur_cm` decimal(8,2) DEFAULT NULL,
  `hauteur_cm` decimal(8,2) DEFAULT NULL,
  `type_produit` enum('simple','variable') DEFAULT 'simple',
  `note_moyenne` decimal(3,2) DEFAULT 0.00,
  `nombre_avis` int(11) DEFAULT 0,
  `nombre_ventes` int(11) DEFAULT 0,
  `nombre_vues` int(11) DEFAULT 0,
  `statut` enum('brouillon','en_attente','actif','suspendu','supprime') DEFAULT 'brouillon',
  `date_publication` timestamp NULL DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `id_vendeur`, `id_categorie`, `sku`, `code_produit`, `nom_produit`, `slug_produit`, `description_courte`, `description`, `marque`, `prix_base`, `prix_promo`, `date_debut_promo`, `date_fin_promo`, `quantite_actuelle`, `seuil_stock_bas`, `statut_stock`, `poids_kg`, `longueur_cm`, `largeur_cm`, `hauteur_cm`, `type_produit`, `note_moyenne`, `nombre_avis`, `nombre_ventes`, `nombre_vues`, `statut`, `date_publication`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, 5, 8, 'SKU001', 'PROD001', 'iPhone 14 Pro', 'iphone-14-pro', 'Smartphone haut de gamme', 'iPhone 14 Pro avec écran Super Retina XDR', 'Apple', 1200000.00, 1100000.00, '2026-04-12 22:00:00', '2026-06-12 22:00:00', 25, 5, 'en_stock', 0.240, NULL, NULL, NULL, 'simple', 4.80, 12, 8, 250, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-09-01 14:33:45'),
(2, 1, 9, 'SKU002', 'PROD002', 'Ordinateur Portable Dell', 'ordinateur-portable-dell', 'PC portable performance', 'Dell Inspiron 15 avec processeur Intel Core i7', 'Dell', 850000.00, 799000.00, '2026-04-12 22:00:00', '2026-04-27 22:00:00', 10, 3, 'en_stock', 2.100, NULL, NULL, NULL, 'simple', 4.50, 8, 5, 180, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-09-01 14:34:00'),
(3, 1, 11, 'SKU003', 'PROD003', 'T-Shirt Homme', 't-shirt-homme', 'T-shirt en coton', 'T-shirt de qualité supérieure 100% coton', 'Nike', 25000.00, 19990.00, '2026-04-12 22:00:00', '2026-05-02 22:00:00', 130, 20, 'en_stock', 0.200, NULL, NULL, NULL, 'variable', 4.20, 25, 30, 321, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-09-01 14:34:12'),
(4, 5, 13, 'SKU004', 'PROD004', 'Chaussures de Sport', 'chaussures-sport', 'Chaussures running', 'Chaussures légères pour la course', 'Adidas', 45000.00, 39990.00, NULL, NULL, 115, 10, 'en_stock', 0.800, NULL, NULL, NULL, 'variable', 4.60, 18, 22, 299, 'actif', '2026-04-13 20:05:49', 1, '2026-04-13 20:05:49', '2026-09-01 14:34:23');

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

CREATE TABLE `profils` (
  `id_profil` int(11) NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `provinces` (
  `id_province` int(11) NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `province_name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `provinces`
--

INSERT INTO `provinces` (`id_province`, `uuid`, `province_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 'cfd785df-919e-11f1-a013-9c7bef735b1f', 'BUHUMUZA', -3.21730000, 30.55800000, 1),
(2, 'cfd78d9e-919e-11f1-a013-9c7bef735b1f', 'BUJUMBURA', -3.36140000, 29.35990000, 1),
(3, 'cfd78fb0-919e-11f1-a013-9c7bef735b1f', 'BURUNGA', -4.13490000, 29.87890000, 1),
(4, 'cfd790d6-919e-11f1-a013-9c7bef735b1f', 'BUTANYERERA', -2.88570000, 29.82990000, 1),
(5, 'cfd791e5-919e-11f1-a013-9c7bef735b1f', 'GITEGA', -3.42640000, 29.93060000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `qr_confirmations`
--

CREATE TABLE `qr_confirmations` (
  `id_qr` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `qr_image_url` varchar(500) DEFAULT NULL,
  `date_expiration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `est_utilise` tinyint(1) DEFAULT 0,
  `date_utilisation` timestamp NULL DEFAULT NULL,
  `latitude_scan` decimal(10,8) DEFAULT NULL,
  `longitude_scan` decimal(11,8) DEFAULT NULL,
  `id_transporteur` int(11) DEFAULT NULL,
  `photo_livraison_url` varchar(500) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `quartiers` (
  `id_quartier` int(11) NOT NULL,
  `id_commune` int(11) NOT NULL,
  `quartier_name` varchar(150) NOT NULL,
  `zone` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `retours_remboursements`
--

CREATE TABLE `retours_remboursements` (
  `id_retour` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_article` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type` enum('retour_produit','remboursement_partiel','remboursement_total') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motif` enum('produit_defectueux','non_conforme','erreur_livraison','changement_avis','produit_endommage','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photos_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `montant_demande` decimal(12,2) NOT NULL,
  `montant_approuve` decimal(12,2) DEFAULT NULL,
  `statut` enum('demande','en_cours','approuve','refuse','rembourse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'demande',
  `traite_par` int(11) DEFAULT NULL,
  `motif_refus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `methode_remboursement` enum('mobile_money','virement') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'mobile_money',
  `date_traitement` timestamp NULL DEFAULT NULL,
  `date_remboursement` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `retours_remboursements`
--

INSERT INTO `retours_remboursements` (`id_retour`, `id_commande`, `id_article`, `id_utilisateur`, `type`, `motif`, `description`, `photos_urls`, `montant_demande`, `montant_approuve`, `statut`, `traite_par`, `motif_refus`, `methode_remboursement`, `date_traitement`, `date_remboursement`, `date_creation`) VALUES
(3, 4, 4, 2, 'retour_produit', 'changement_avis', 'Je souhaite retourner le T-Shirt car la taille ne correspond pas. Je souhaite un échange ou un remboursement.', '[\"/uploads/retours/t-shirt_taille.jpg\"]', 25000.00, NULL, 'en_cours', 1, NULL, 'virement', '2026-04-23 14:00:00', NULL, '2026-04-23 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `IdSetting` int(11) NOT NULL,
  `KeyValue` varchar(250) NOT NULL,
  `TitlePage` varchar(200) DEFAULT NULL,
  `Value` text DEFAULT NULL,
  `IsFile` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `soldes_vendeurs` (
  `id_solde` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `solde_disponible` decimal(15,2) DEFAULT 0.00,
  `solde_en_attente` decimal(15,2) DEFAULT 0.00,
  `total_gagne` decimal(15,2) DEFAULT 0.00,
  `total_retire` decimal(15,2) DEFAULT 0.00,
  `dernier_paiement` timestamp NULL DEFAULT NULL,
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `soldes_vendeurs`
--

INSERT INTO `soldes_vendeurs` (`id_solde`, `id_vendeur`, `solde_disponible`, `solde_en_attente`, `total_gagne`, `total_retire`, `dernier_paiement`, `date_modification`) VALUES
(5, 5, 0.00, 0.00, 0.00, 0.00, NULL, '2026-05-18 23:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `suivi_gps`
--

CREATE TABLE `suivi_gps` (
  `id_suivi` bigint(20) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_transporteur` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `vitesse_kmh` decimal(6,2) DEFAULT NULL,
  `timestamp_gps` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tentatives_connexion`
--

CREATE TABLE `tentatives_connexion` (
  `id_tentative` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `email_tente` varchar(255) DEFAULT NULL,
  `adresse_ip` varchar(45) NOT NULL,
  `reussie` tinyint(1) DEFAULT 0,
  `motif_echec` varchar(100) DEFAULT NULL,
  `date_tentative` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(56, 18, 'dushimepaul51@gmail.com', '127.0.0.1', 1, NULL, '2026-05-19 14:35:39'),
(57, NULL, 'admin@vip-school.com', '154.119.31.82', 0, 'Email non trouvé', '2026-08-24 12:51:03'),
(58, 1, 'admin@abemarket.com', '154.119.31.82', 0, 'Mot de passe incorrect', '2026-08-24 13:14:59'),
(59, 1, 'admin@abemarket.com', '154.119.31.82', 1, NULL, '2026-08-24 13:17:27');

-- --------------------------------------------------------

--
-- Structure de la table `transactions_paiement`
--

CREATE TABLE `transactions_paiement` (
  `id_transaction` int(11) NOT NULL,
  `reference_interne` varchar(100) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_mode_payement` int(11) NOT NULL,
  `type_transaction` enum('paiement','remboursement','virement_vendeur') NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `frais` decimal(10,2) DEFAULT 0.00,
  `montant_net` decimal(15,2) NOT NULL,
  `devise` varchar(3) DEFAULT 'BIF',
  `telephone_payeur` varchar(20) DEFAULT NULL,
  `nom_payeur` varchar(200) DEFAULT NULL,
  `reference_operateur` varchar(100) DEFAULT NULL,
  `statut` enum('initie','en_attente','confirme','echoue','annule','rembourse') DEFAULT 'initie',
  `message_statut` varchar(500) DEFAULT NULL,
  `date_confirmation` timestamp NULL DEFAULT NULL,
  `adresse_ip` varchar(45) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions_paiement`
--

INSERT INTO `transactions_paiement` (`id_transaction`, `reference_interne`, `id_commande`, `id_utilisateur`, `id_mode_payement`, `type_transaction`, `montant`, `frais`, `montant_net`, `devise`, `telephone_payeur`, `nom_payeur`, `reference_operateur`, `statut`, `message_statut`, `date_confirmation`, `adresse_ip`, `date_creation`) VALUES
(4, 'TRX-20260423-001', 3, 2, 1, 'paiement', 41490.00, 622.35, 40867.65, 'BIF', '+25762345678', 'Marie Client', 'BANCOBU_REF_789456123', 'confirme', 'Paiement confirmé par Bancobu', '2026-04-23 08:15:00', '192.168.1.100', '2026-04-23 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `transporteurs`
--

CREATE TABLE `transporteurs` (
  `id_transporteur` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `type` enum('interne','partenaire') DEFAULT 'interne',
  `nom` varchar(200) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `photo_url` varchar(500) DEFAULT NULL,
  `type_vehicule` enum('moto','voiture','velo','camionnette','pied') DEFAULT 'moto',
  `plaque` varchar(20) DEFAULT NULL,
  `latitude_actuelle` decimal(10,8) DEFAULT NULL,
  `longitude_actuelle` decimal(11,8) DEFAULT NULL,
  `derniere_position` timestamp NULL DEFAULT NULL,
  `est_disponible` tinyint(1) DEFAULT 1,
  `note_moyenne` decimal(3,2) DEFAULT 0.00,
  `livraisons_totales` int(11) DEFAULT 0,
  `livraisons_reussies` int(11) DEFAULT 0,
  `statut` enum('actif','inactif','suspendu') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transporteurs`
--

INSERT INTO `transporteurs` (`id_transporteur`, `id_utilisateur`, `type`, `nom`, `telephone`, `whatsapp`, `photo_url`, `type_vehicule`, `plaque`, `latitude_actuelle`, `longitude_actuelle`, `derniere_position`, `est_disponible`, `note_moyenne`, `livraisons_totales`, `livraisons_reussies`, `statut`, `date_creation`) VALUES
(1, NULL, 'interne', 'Paul Livreur', '+25764567890', '+25764567890', 'uploads/transporteurs/transporteur_20260423_174103_69ea59afaf06a.jpeg', 'moto', 'AB123CD', NULL, NULL, NULL, 1, 4.80, 120, 115, 'actif', '2026-04-13 20:05:49');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `email_verifie` tinyint(1) DEFAULT 0,
  `telephone_verifie` tinyint(1) DEFAULT 0,
  `deux_facteurs_actif` tinyint(1) DEFAULT 0,
  `methode_2fa` enum('sms','email') DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `est_banni` tinyint(1) DEFAULT 0,
  `motif_bannissement` text DEFAULT NULL,
  `derniere_connexion` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token_reset` varchar(100) DEFAULT NULL,
  `date_reset_expiration` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `mot_de_passe`, `prenom`, `nom`, `telephone`, `avatar_url`, `email_verifie`, `telephone_verifie`, `deux_facteurs_actif`, `methode_2fa`, `est_actif`, `est_banni`, `motif_bannissement`, `derniere_connexion`, `date_creation`, `date_modification`, `token_reset`, `date_reset_expiration`) VALUES
(1, 'admin@abemarket.com', 'c93ccd78b2076528346216b3b2f701e6', 'Administateur', 'Admin', '+25761234567', 'attachments/Users/2026041322362469dd6fe800b3f.jpeg', 1, 1, 0, NULL, 1, 0, NULL, '2026-08-24 13:17:27', '2026-04-13 14:21:16', '2026-09-03 08:14:21', NULL, NULL),
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

CREATE TABLE `utilisateur_profils` (
  `id_utilisateur` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `attribue_par` int(11) DEFAULT NULL,
  `date_attribution` timestamp NOT NULL DEFAULT current_timestamp()
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

CREATE TABLE `variantes_produit` (
  `id_variante` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributs_variante` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `prix` decimal(12,2) DEFAULT NULL,
  `quantite_actuelle` int(11) DEFAULT 0,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `vendeurs` (
  `id_vendeur` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `nom_boutique` varchar(255) NOT NULL,
  `slug_boutique` varchar(255) NOT NULL,
  `logo_boutique` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type_vendeur` enum('particulier','entreprise') DEFAULT 'particulier',
  `nom_entreprise` varchar(200) DEFAULT NULL,
  `numero_nif` varchar(50) DEFAULT NULL,
  `numero_rc` varchar(50) DEFAULT NULL,
  `id_province` int(11) DEFAULT NULL,
  `id_commune` int(11) DEFAULT NULL,
  `id_quartier` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `taux_commission` decimal(5,2) DEFAULT 10.00,
  `delai_paiement_jours` int(11) DEFAULT 7,
  `note_moyenne` decimal(3,2) DEFAULT 0.00,
  `nombre_avis` int(11) DEFAULT 0,
  `total_commandes` int(11) DEFAULT 0,
  `est_approuve` tinyint(1) DEFAULT 0,
  `date_approbation` timestamp NULL DEFAULT NULL,
  `approuve_par` int(11) DEFAULT NULL,
  `statut` enum('en_attente','actif','suspendu','banni') DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vendeurs`
--

INSERT INTO `vendeurs` (`id_vendeur`, `id_utilisateur`, `nom_boutique`, `slug_boutique`, `logo_boutique`, `description`, `type_vendeur`, `nom_entreprise`, `numero_nif`, `numero_rc`, `id_province`, `id_commune`, `id_quartier`, `latitude`, `longitude`, `telephone`, `whatsapp`, `taux_commission`, `delai_paiement_jours`, `note_moyenne`, `nombre_avis`, `total_commandes`, `est_approuve`, `date_approbation`, `approuve_par`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 1, 'Boutique Admin', 'boutique-admin', NULL, NULL, 'particulier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 7, 0.00, 0, 0, 1, NULL, NULL, 'actif', '2026-04-23 10:55:57', '2026-04-23 10:55:57'),
(5, 18, 'BOBO', 'bobo', 'attachments/Users/202605182357166a0ba75c172cb.jpg', '', 'particulier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '784594593', '784594593', 10.00, 7, 0.00, 0, 0, 0, NULL, NULL, 'en_attente', '2026-05-18 21:57:16', '2026-05-18 23:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `zones`
--

CREATE TABLE `zones` (
  `id_zone` int(11) NOT NULL,
  `id_commune` int(11) NOT NULL,
  `zone_name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `zones`
--

INSERT INTO `zones` (`id_zone`, `id_commune`, `zone_name`, `latitude`, `longitude`, `est_actif`) VALUES
(1, 1, 'Bisinde', -3.37500000, 29.36500000, 1),
(2, 1, 'Biyorwa', -3.37000000, 29.37000000, 1),
(3, 1, 'Mugege', -3.36500000, 29.37500000, 1),
(4, 1, 'Muriza', -3.36000000, 29.38000000, 1),
(5, 1, 'Rugongo', -3.35500000, 29.38500000, 1),
(6, 2, 'Butarugera', -3.35000000, 29.39000000, 1),
(7, 2, 'Butihinda', -3.34500000, 29.39500000, 1),
(8, 2, 'Burambira', -3.34000000, 29.40000000, 1),
(9, 2, 'Buvumbi', -3.33500000, 29.40500000, 1),
(10, 2, 'Gashoho', -3.33000000, 29.41000000, 1),
(11, 2, 'Gisanze', -3.32500000, 29.41500000, 1),
(12, 2, 'Giteranyi', -2.70030000, 30.45000000, 1),
(13, 2, 'Kamaramagambo', -3.31500000, 29.42500000, 1),
(14, 3, 'Cankuzo', -3.21670000, 30.65000000, 1),
(15, 3, 'Gitanga', -3.30500000, 29.43500000, 1),
(16, 3, 'Kigamba', -3.30000000, 29.44000000, 1),
(17, 3, 'Minyare', -3.29500000, 29.44500000, 1),
(18, 4, 'Bumba', -3.29000000, 29.45000000, 1),
(19, 4, 'Camazi', -3.28500000, 29.45500000, 1),
(20, 4, 'Cendajuru', -3.28000000, 29.46000000, 1),
(21, 4, 'Gisagara', -3.27500000, 29.46500000, 1),
(22, 4, 'Mugera', -3.27000000, 29.47000000, 1),
(23, 4, 'Mishiha', -3.26500000, 29.47500000, 1),
(24, 4, 'Mwiruzi', -3.26000000, 29.48000000, 1),
(25, 4, 'Nyamugari', -3.25500000, 29.48500000, 1),
(26, 4, 'Twinkwavu', -3.25000000, 29.49000000, 1),
(27, 5, 'Gisuru', -3.24500000, 29.49500000, 1),
(28, 5, 'Kabanga', -3.24000000, 29.50000000, 1),
(29, 5, 'Kinyinya', -3.23500000, 29.50500000, 1),
(30, 5, 'Muhwazi', -3.23000000, 29.51000000, 1),
(31, 5, 'Ndemeka', -3.22500000, 29.51500000, 1),
(32, 5, 'Nyabitare', -3.22000000, 29.52000000, 1),
(33, 5, 'Nyabitsinda', -3.21500000, 29.52500000, 1),
(34, 5, 'Rukobe', -3.21000000, 29.53000000, 1),
(35, 6, 'Bwasare', -3.20500000, 29.53500000, 1),
(36, 6, 'Buhinyuza', -3.20000000, 29.54000000, 1),
(37, 6, 'Cumba', -3.19500000, 29.54500000, 1),
(38, 6, 'Gasave', -3.19000000, 29.55000000, 1),
(39, 6, 'Gasorwe', -3.18500000, 29.55500000, 1),
(40, 6, 'Higiro', -3.18000000, 29.56000000, 1),
(41, 6, 'Jarama', -3.17500000, 29.56500000, 1),
(42, 6, 'Kiyanza', -3.17000000, 29.57000000, 1),
(43, 6, 'Kayenzi', -3.16500000, 29.57500000, 1),
(44, 6, 'Kiremba', -3.16000000, 29.58000000, 1),
(45, 6, 'Munagano', -3.15500000, 29.58500000, 1),
(46, 6, 'Murama', -3.15000000, 29.59000000, 1),
(47, 6, 'Mwakiro', -3.14500000, 29.59500000, 1),
(48, 6, 'Muyinga', -3.14000000, 29.60000000, 1),
(49, 6, 'Rugabano', -3.13500000, 29.60500000, 1),
(50, 6, 'Rugari', -3.13000000, 29.61000000, 1),
(51, 7, 'Busoro', -3.12500000, 29.61500000, 1),
(52, 7, 'Butezi', -3.12000000, 29.62000000, 1),
(53, 7, 'Bwagiriza', -3.11500000, 29.62500000, 1),
(54, 7, 'Kayongozi', -3.11000000, 29.63000000, 1),
(55, 7, 'Kirambi', -3.10500000, 29.63500000, 1),
(56, 7, 'Mubira', -3.10000000, 29.64000000, 1),
(57, 7, 'Bweru', -3.09500000, 29.64500000, 1),
(58, 7, 'Rusengo', -3.09000000, 29.65000000, 1),
(59, 7, 'Ruyigi', -3.48330000, 30.51670000, 1),
(60, 8, 'Bubanza', -3.08330000, 29.35000000, 1),
(61, 8, 'Buvyuko', -3.07500000, 29.66500000, 1),
(62, 8, 'Kivyuka', -3.07000000, 29.67000000, 1),
(63, 8, 'Mitakataka', -3.06500000, 29.67500000, 1),
(64, 8, 'Muramba', -3.06000000, 29.68000000, 1),
(65, 8, 'Musigati', -3.05500000, 29.68500000, 1),
(66, 8, 'Muyebe', -3.05000000, 29.69000000, 1),
(67, 8, 'Ntamba', -3.04500000, 29.69500000, 1),
(68, 9, 'Buganda', -3.04000000, 29.70000000, 1),
(69, 9, 'Buhayira', -3.03500000, 29.70500000, 1),
(70, 9, 'Bumba', -3.03000000, 29.71000000, 1),
(71, 9, 'Buzirasazi', -3.02500000, 29.71500000, 1),
(72, 9, 'Gahabura', -3.02000000, 29.72000000, 1),
(73, 9, 'Masango', -3.01500000, 29.72500000, 1),
(74, 9, 'Ndava', -3.01000000, 29.73000000, 1),
(75, 9, 'Ndora', -3.00500000, 29.73500000, 1),
(76, 9, 'Rusenda', -3.00000000, 29.74000000, 1),
(77, 10, 'Buhindo', -2.99500000, 29.74500000, 1),
(78, 10, 'Cibitoke', -2.99000000, 29.75000000, 1),
(79, 10, 'Kiramira', -2.98500000, 29.75500000, 1),
(80, 10, 'Murwi', -2.98000000, 29.76000000, 1),
(81, 10, 'Ngoma', -2.97500000, 29.76500000, 1),
(82, 10, 'Rugombo', -2.95000000, 29.10000000, 1),
(83, 10, 'Rukana', -2.96500000, 29.77500000, 1),
(84, 11, 'Kibuye', -2.96000000, 29.78000000, 1),
(85, 11, 'Kirama', -2.95500000, 29.78500000, 1),
(86, 11, 'Mageyo', -2.95000000, 29.79000000, 1),
(87, 11, 'Martyazo', -2.94500000, 29.79500000, 1),
(88, 11, 'Mubimbi', -2.94000000, 29.80000000, 1),
(89, 11, 'Rushubi', -2.93500000, 29.80500000, 1),
(90, 11, 'Ryarusera', -2.93000000, 29.81000000, 1),
(91, 12, 'Buringa', -2.92500000, 29.81500000, 1),
(92, 12, 'Butanuka', -2.92000000, 29.82000000, 1),
(93, 12, 'Gihanga', -2.91500000, 29.82500000, 1),
(94, 12, 'Mpanda', -3.22000000, 29.38000000, 1),
(95, 12, 'Mudubugu', -2.90500000, 29.83500000, 1),
(96, 12, 'Musenyi', -2.90000000, 29.84000000, 1),
(97, 12, 'Muzinda', -2.89500000, 29.84500000, 1),
(98, 12, 'Ruce', -2.89000000, 29.85000000, 1),
(99, 12, 'Rugazi', -2.88500000, 29.85500000, 1),
(100, 13, 'Buhanda', -2.88000000, 29.86000000, 1),
(101, 13, 'Gomvyi', -2.87500000, 29.86500000, 1),
(102, 13, 'Kabezi', -2.87000000, 29.87000000, 1),
(103, 13, 'Kanyosha', -2.86500000, 29.87500000, 1),
(104, 13, 'Kinindo', -2.86000000, 29.88000000, 1),
(105, 13, 'Kiyenzi', -2.85500000, 29.88500000, 1),
(106, 13, 'Mubone', -2.85000000, 29.89000000, 1),
(107, 13, 'Musaga', -2.84500000, 29.89500000, 1),
(108, 13, 'Ramba', -2.84000000, 29.90000000, 1),
(109, 13, 'Ruyaga', -2.83500000, 29.90500000, 1),
(110, 13, 'Ruziba', -2.83000000, 29.91000000, 1),
(111, 14, 'Buhoro', -2.82500000, 29.91500000, 1),
(112, 14, 'Butahana', -2.82000000, 29.92000000, 1),
(113, 14, 'Mabayi', -2.81500000, 29.92500000, 1),
(114, 14, 'Mugina', -2.81000000, 29.93000000, 1),
(115, 14, 'Nyamakarabo', -2.80500000, 29.93500000, 1),
(116, 14, 'Rubona', -2.80000000, 29.94000000, 1),
(117, 14, 'Rugajo', -2.79500000, 29.94500000, 1),
(118, 14, 'Ruhororo', -2.79000000, 29.95000000, 1),
(119, 14, 'Ruziba', -2.78500000, 29.95500000, 1),
(120, 15, 'Bugarama', -2.78000000, 29.96000000, 1),
(121, 15, 'Busenge', -2.77500000, 29.96500000, 1),
(122, 15, 'Gitaza', -2.77000000, 29.97000000, 1),
(123, 15, 'Magara', -2.76500000, 29.97500000, 1),
(124, 15, 'Muhuta', -2.76000000, 29.98000000, 1),
(125, 15, 'Ruteme', -2.75500000, 29.98500000, 1),
(126, 15, 'Rutongo', -2.75000000, 29.99000000, 1),
(127, 16, 'Buyenzi', -3.37000000, 29.35000000, 1),
(128, 16, 'Bwiza', -3.39000000, 29.37000000, 1),
(129, 16, 'Mukaza', -2.73500000, 30.00500000, 1),
(130, 16, 'Muyira', -2.73000000, 30.01000000, 1),
(131, 16, 'Nyakabiga', -2.72500000, 30.01500000, 1),
(132, 17, 'Benga', -2.72000000, 30.02000000, 1),
(133, 17, 'Buterere', -2.71500000, 30.02500000, 1),
(134, 17, 'Cibitoke', -2.71000000, 30.03000000, 1),
(135, 17, 'Gatumba', -2.70500000, 30.03500000, 1),
(136, 17, 'Gihosha', -2.70000000, 30.04000000, 1),
(137, 17, 'Kamenge', -2.69500000, 30.04500000, 1),
(138, 17, 'Kinama', -2.69000000, 30.05000000, 1),
(139, 17, 'Kirekura', -2.68500000, 30.05500000, 1),
(140, 17, 'Mutimbuzi', -2.68000000, 30.06000000, 1),
(141, 17, 'Ngagara', -2.67500000, 30.06500000, 1),
(142, 17, 'Nyambuye', -2.67000000, 30.07000000, 1),
(143, 17, 'Rubirizi', -2.66500000, 30.07500000, 1),
(144, 17, 'Rukaramu', -2.66000000, 30.08000000, 1),
(145, 18, 'Bikanka', -2.65500000, 30.08500000, 1),
(146, 18, 'Jenda', -2.65000000, 30.09000000, 1),
(147, 18, 'Kankima', -2.64500000, 30.09500000, 1),
(148, 18, 'Kigina', -2.64000000, 30.10000000, 1),
(149, 18, 'Matara', -2.63500000, 30.10500000, 1),
(150, 18, 'Mugongo', -2.63000000, 30.11000000, 1),
(151, 18, 'Mukike', -2.62500000, 30.11500000, 1),
(152, 18, 'Nyabibondo', -2.62000000, 30.12000000, 1),
(153, 18, 'Nyabiraba', -2.61500000, 30.12500000, 1),
(154, 18, 'Rukina', -2.61000000, 30.13000000, 1),
(155, 19, 'Bamba', -2.60500000, 30.13500000, 1),
(156, 19, 'Binyuro', -2.60000000, 30.14000000, 1),
(157, 19, 'Bururi', -3.95000000, 29.61670000, 1),
(158, 19, 'Condi', -2.59000000, 30.15000000, 1),
(159, 19, 'Burunga', -2.58500000, 30.15500000, 1),
(160, 19, 'Gitsiro', -2.58000000, 30.16000000, 1),
(161, 19, 'Kajondi', -2.57500000, 30.16500000, 1),
(162, 19, 'Muhweza', -2.57000000, 30.17000000, 1),
(163, 19, 'Munini', -2.56500000, 30.17500000, 1),
(164, 19, 'Muzenga', -2.56000000, 30.18000000, 1),
(165, 19, 'Rutovu', -2.55500000, 30.18500000, 1),
(166, 19, 'Vyanda', -2.55000000, 30.19000000, 1),
(167, 20, 'Bukeye', -2.54500000, 30.19500000, 1),
(168, 20, 'Bigina', -2.54000000, 30.20000000, 1),
(169, 20, 'Nyantakara', -2.53500000, 30.20500000, 1),
(170, 20, 'Canda', -2.53000000, 30.21000000, 1),
(171, 20, 'Dunga', -2.52500000, 30.21500000, 1),
(172, 20, 'Gatabo', -2.52000000, 30.22000000, 1),
(173, 20, 'Gitaba', -2.51500000, 30.22500000, 1),
(174, 20, 'Gisenyi', -2.51000000, 30.23000000, 1),
(175, 20, 'Kabuye', -2.50500000, 30.23500000, 1),
(176, 20, 'Kayogoro', -2.50000000, 30.24000000, 1),
(177, 20, 'Kibago', -2.49500000, 30.24500000, 1),
(178, 20, 'Kiyange', -2.49000000, 30.25000000, 1),
(179, 20, 'Makamba', -4.13330000, 29.80000000, 1),
(180, 21, 'Gasibe', -2.48000000, 30.26000000, 1),
(181, 21, 'Gisarenda', -2.47500000, 30.26500000, 1),
(182, 21, 'Kibezi', -2.47000000, 30.27000000, 1),
(183, 21, 'Kivumu', -2.46500000, 30.27500000, 1),
(184, 21, 'Kiryama', -2.46000000, 30.28000000, 1),
(185, 21, 'Mahwa', -2.45500000, 30.28500000, 1),
(186, 21, 'Matana', -3.85000000, 29.70000000, 1),
(187, 21, 'Muheka', -2.44500000, 30.29500000, 1),
(188, 21, 'Mugamba', -2.44000000, 30.30000000, 1),
(189, 21, 'Mwumba', -2.43500000, 30.30500000, 1),
(190, 21, 'Ndago', -2.43000000, 30.31000000, 1),
(191, 21, 'Nyagasasa', -2.42500000, 30.31500000, 1),
(192, 21, 'Ruvumvu', -2.42000000, 30.32000000, 1),
(193, 21, 'Songa', -2.41500000, 30.32500000, 1),
(194, 21, 'Vyuya', -2.41000000, 30.33000000, 1),
(195, 22, 'Butezi', -2.40500000, 30.33500000, 1),
(196, 22, 'Gakungu', -2.40000000, 30.34000000, 1),
(197, 22, 'Gatakazi', -2.39500000, 30.34500000, 1),
(198, 22, 'Giharo', -2.39000000, 30.35000000, 1),
(199, 22, 'Kayero', -2.38500000, 30.35500000, 1),
(200, 22, 'Kiguhu', -2.38000000, 30.36000000, 1),
(201, 22, 'Mpinga', -2.37500000, 30.36500000, 1),
(202, 22, 'Mugondo', -2.37000000, 30.37000000, 1),
(203, 22, 'Musongati', -2.36500000, 30.37500000, 1),
(204, 22, 'Muzye', -2.36000000, 30.38000000, 1),
(205, 22, 'Ngoma', -2.35500000, 30.38500000, 1),
(206, 22, 'Shanga', -2.35000000, 30.39000000, 1),
(207, 23, 'Gishiha', -2.34500000, 30.39500000, 1),
(208, 23, 'Gitara', -2.34000000, 30.40000000, 1),
(209, 23, 'Kabonga', -2.33500000, 30.40500000, 1),
(210, 23, 'Kayogoro', -2.33000000, 30.41000000, 1),
(211, 23, 'Kayove', -2.32500000, 30.41500000, 1),
(212, 23, 'Kazirabageni', -2.32000000, 30.42000000, 1),
(213, 23, 'Mabanda', -2.31500000, 30.42500000, 1),
(214, 23, 'Mpinga', -2.31000000, 30.43000000, 1),
(215, 23, 'Mukubano', -2.30500000, 30.43500000, 1),
(216, 23, 'Mukungu', -2.30000000, 30.44000000, 1),
(217, 23, 'Muyange', -2.29500000, 30.44500000, 1),
(218, 23, 'Nyanza', -2.29000000, 30.45000000, 1),
(219, 23, 'Vugizo', -2.28500000, 30.45500000, 1),
(220, 24, 'Buruhukiro', -2.28000000, 30.46000000, 1),
(221, 24, 'Burambi', -2.27500000, 30.46500000, 1),
(222, 24, 'Buyengero', -2.27000000, 30.47000000, 1),
(223, 24, 'Gatete', -2.26500000, 30.47500000, 1),
(224, 24, 'Kigwena', -2.26000000, 30.48000000, 1),
(225, 24, 'Kizuka', -2.25500000, 30.48500000, 1),
(226, 24, 'Maramvya', -2.25000000, 30.49000000, 1),
(227, 24, 'Mariza', -2.24500000, 30.49500000, 1),
(228, 24, 'Minago', -2.24000000, 30.50000000, 1),
(229, 24, 'Mudende', -2.23500000, 30.50500000, 1),
(230, 24, 'Muzenga', -2.23000000, 30.51000000, 1),
(231, 24, 'Rumonge', -3.98330000, 29.43330000, 1),
(232, 24, 'Rusabagi', -2.22000000, 30.52000000, 1),
(233, 25, 'Bukemba', -2.21500000, 30.52500000, 1),
(234, 25, 'Butare', -2.21000000, 30.53000000, 1),
(235, 25, 'Gitaba', -2.20500000, 30.53500000, 1),
(236, 25, 'Busoni', -2.20000000, 30.54000000, 1),
(237, 25, 'Bwambarangwe', -2.19500000, 30.54500000, 1),
(238, 25, 'Cumba', -2.19000000, 30.55000000, 1),
(239, 25, 'Gisenyi', -2.18500000, 30.55500000, 1),
(240, 25, 'Gitobe', -2.18000000, 30.56000000, 1),
(241, 25, 'Kabanga', -2.17500000, 30.56500000, 1),
(242, 25, 'Kimeza', -2.17000000, 30.57000000, 1),
(243, 25, 'Mukerwa', -2.16500000, 30.57500000, 1),
(244, 25, 'Murore', -2.16000000, 30.58000000, 1),
(245, 25, 'Nyagisozi', -2.15500000, 30.58500000, 1),
(246, 25, 'Nyenzi', -2.15000000, 30.59000000, 1),
(247, 25, 'Shore', -2.14500000, 30.59500000, 1),
(248, 26, 'Baziro', -2.14000000, 30.60000000, 1),
(249, 26, 'Bugorora', -2.13500000, 30.60500000, 1),
(250, 26, 'Buhoro', -2.13000000, 30.61000000, 1),
(251, 26, 'Busoni', -2.50000000, 30.15000000, 1),
(252, 26, 'Bwambarangwe', -2.12000000, 30.62000000, 1),
(253, 26, 'Cumba', -2.11500000, 30.62500000, 1),
(254, 26, 'Gisenyi', -2.11000000, 30.63000000, 1),
(255, 26, 'Gitobe', -2.10500000, 30.63500000, 1),
(256, 26, 'Kabanga', -2.10000000, 30.64000000, 1),
(257, 26, 'Kimeza', -2.09500000, 30.64500000, 1),
(258, 26, 'Mukerwa', -2.09000000, 30.65000000, 1),
(259, 26, 'Murore', -2.08500000, 30.65500000, 1),
(260, 26, 'Nyagisozi', -2.08000000, 30.66000000, 1),
(261, 26, 'Nyenzi', -2.07500000, 30.66500000, 1),
(262, 26, 'Shore', -2.07000000, 30.67000000, 1),
(263, 27, 'Jene', -2.06500000, 30.67500000, 1),
(264, 27, 'Kabarore', -2.06000000, 30.68000000, 1),
(265, 27, 'Kabuye', -2.05500000, 30.68500000, 1),
(266, 27, 'Kayanza', -2.05000000, 30.69000000, 1),
(267, 27, 'Mparamirundi', -2.04500000, 30.69500000, 1),
(268, 27, 'Murima', -2.04000000, 30.70000000, 1),
(269, 27, 'Muruta', -2.03500000, 30.70500000, 1),
(270, 27, 'Nkonge', -2.03000000, 30.71000000, 1),
(271, 27, 'Nyabihogo', -2.02500000, 30.71500000, 1),
(272, 27, 'Rugazi', -2.02000000, 30.72000000, 1),
(273, 27, 'Rukere', -2.01500000, 30.72500000, 1),
(274, 27, 'Rwegura', -2.01000000, 30.73000000, 1),
(275, 28, 'Birambi', -2.00500000, 30.73500000, 1),
(276, 28, 'Bugina', -2.00000000, 30.74000000, 1),
(277, 28, 'Cindonyi', -1.99500000, 30.74500000, 1),
(278, 28, 'Gakere', -1.99000000, 30.75000000, 1),
(279, 28, 'Giheta', -1.98500000, 30.75500000, 1),
(280, 28, 'Kiremba', -1.98000000, 30.76000000, 1),
(281, 28, 'Marangara', -1.97500000, 30.76500000, 1),
(282, 28, 'Musasa', -1.97000000, 30.77000000, 1),
(283, 28, 'Nyamugari', -1.96500000, 30.77500000, 1),
(284, 28, 'Nyamurenza', -2.95000000, 30.15000000, 1),
(285, 29, 'Bugabira', -1.95500000, 30.78500000, 1),
(286, 29, 'Bukuba', -1.95000000, 30.79000000, 1),
(287, 29, 'Cendajuru', -1.94500000, 30.79500000, 1),
(288, 29, 'Cewe', -1.94000000, 30.80000000, 1),
(289, 29, 'Gashingwa', -1.93500000, 30.80500000, 1),
(290, 29, 'Gihosha', -1.93000000, 30.81000000, 1),
(291, 29, 'Gikuyo', -1.92500000, 30.81500000, 1),
(292, 29, 'Kavomo', -1.92000000, 30.82000000, 1),
(293, 29, 'Kigina', -1.91500000, 30.82500000, 1),
(294, 29, 'Kigoma', -1.91000000, 30.83000000, 1),
(295, 29, 'Kiri', -1.90500000, 30.83500000, 1),
(296, 29, 'Kirundo', -2.58330000, 30.08330000, 1),
(297, 29, 'Kiyonza', -1.89500000, 30.84500000, 1),
(298, 29, 'Mugendo', -1.89000000, 30.85000000, 1),
(299, 29, 'Murungurira', -1.88500000, 30.85500000, 1),
(300, 29, 'Ntega', -1.88000000, 30.86000000, 1),
(301, 29, 'Nyamabuye', -1.87500000, 30.86500000, 1),
(302, 29, 'Runyankezi', -1.87000000, 30.87000000, 1),
(303, 29, 'Rushubije', -1.86500000, 30.87500000, 1),
(304, 29, 'Vumbi', -1.86000000, 30.88000000, 1),
(305, 30, 'Banga', -1.85500000, 30.88500000, 1),
(306, 30, 'Burarana', -1.85000000, 30.89000000, 1),
(307, 30, 'Busangana', -1.84500000, 30.89500000, 1),
(308, 30, 'Butaganzwa', -1.84000000, 30.90000000, 1),
(309, 30, 'Gatara', -1.83500000, 30.90500000, 1),
(310, 30, 'Kabuye', -1.83000000, 30.91000000, 1),
(311, 30, 'Mbirizi', -1.82500000, 30.91500000, 1),
(312, 30, 'Ngoro', -1.82000000, 30.92000000, 1),
(313, 30, 'Ninga', -1.81500000, 30.92500000, 1),
(314, 30, 'Nyabibuye', -1.81000000, 30.93000000, 1),
(315, 30, 'Ruganza', -1.80500000, 30.93500000, 1),
(316, 31, 'Bisha', -1.80000000, 30.94000000, 1),
(317, 31, 'Gaheta', -1.79500000, 30.94500000, 1),
(318, 31, 'Gahombo', -1.79000000, 30.95000000, 1),
(319, 31, 'Gikomero', -1.78500000, 30.95500000, 1),
(320, 31, 'Muhanga', -1.78000000, 30.96000000, 1),
(321, 31, 'Mibazi', -1.77500000, 30.96500000, 1),
(322, 31, 'Mubogora', -1.77000000, 30.97000000, 1),
(323, 31, 'Nzewe', -1.76500000, 30.97500000, 1),
(324, 31, 'Rango', -1.76000000, 30.98000000, 1),
(325, 32, 'Buhiga', -1.75500000, 30.98500000, 1),
(326, 32, 'Busiga', -1.75000000, 30.99000000, 1),
(327, 32, 'Buye', -1.74500000, 30.99500000, 1),
(328, 32, 'Gatsinda', -1.74000000, 31.00000000, 1),
(329, 32, 'Makaba', -1.73500000, 31.00500000, 1),
(330, 32, 'Mivo', -1.73000000, 31.01000000, 1),
(331, 32, 'Mubuga', -1.72500000, 31.01500000, 1),
(332, 32, 'Mugomera', -1.72000000, 31.02000000, 1),
(333, 32, 'Mwumba', -1.71500000, 31.02500000, 1),
(334, 32, 'Ngozi', -2.90500000, 30.06100000, 1),
(335, 32, 'Rukeco', -1.70500000, 31.03500000, 1),
(336, 32, 'Rwabiriro', -1.70000000, 31.04000000, 1),
(337, 33, 'Gasezerwa', -1.69500000, 31.04500000, 1),
(338, 33, 'Gashikanwa', -1.69000000, 31.05000000, 1),
(339, 33, 'Gatobo', -1.68500000, 31.05500000, 1),
(340, 33, 'Kabuye', -1.68000000, 31.06000000, 1),
(341, 33, 'Kananira', -1.67500000, 31.06500000, 1),
(342, 33, 'Mubanga', -1.67000000, 31.07000000, 1),
(343, 33, 'Musenyi', -1.66500000, 31.07500000, 1),
(344, 33, 'Ngoma', -1.66000000, 31.08000000, 1),
(345, 33, 'Nyagatovu', -1.65500000, 31.08500000, 1),
(346, 33, 'Remera', -1.65000000, 31.09000000, 1),
(347, 33, 'Ruhororo', -1.64500000, 31.09500000, 1),
(348, 33, 'Taba', -1.64000000, 31.10000000, 1),
(349, 33, 'Tangara', -1.63500000, 31.10500000, 1),
(350, 34, 'Bitare', -1.63000000, 31.11000000, 1),
(351, 34, 'Bugendana', -1.62500000, 31.11500000, 1),
(352, 34, 'Gitongo', -1.62000000, 31.12000000, 1),
(353, 34, 'Mugera', -1.61500000, 31.12500000, 1),
(354, 34, 'Mutaho', -1.61000000, 31.13000000, 1),
(355, 34, 'Mutoyi', -1.60500000, 31.13500000, 1),
(356, 34, 'Rwisabi', -1.60000000, 31.14000000, 1),
(357, 35, 'Bukirasazi', -1.59500000, 31.14500000, 1),
(358, 35, 'Bukoro', -1.59000000, 31.15000000, 1),
(359, 35, 'Buraza', -1.58500000, 31.15500000, 1),
(360, 35, 'Butezi', -1.58000000, 31.16000000, 1),
(361, 35, 'Gishubi', -1.57500000, 31.16500000, 1),
(362, 35, 'Kavumu', -1.57000000, 31.17000000, 1),
(363, 35, 'Kibere', -1.56500000, 31.17500000, 1),
(364, 35, 'Kibuye', -1.56000000, 31.18000000, 1),
(365, 35, 'Mahonda', -1.55500000, 31.18500000, 1),
(366, 35, 'Mugaruro', -1.55000000, 31.19000000, 1),
(367, 35, 'Murambi', -1.54500000, 31.19500000, 1),
(368, 35, 'Nyabiraba', -1.54000000, 31.20000000, 1),
(369, 35, 'Nyabitanga', -1.53500000, 31.20500000, 1),
(370, 35, 'Nyarusange', -1.53000000, 31.21000000, 1),
(371, 35, 'Ryansoro', -1.52500000, 31.21500000, 1),
(372, 36, 'Buhevyi', -1.52000000, 31.22000000, 1),
(373, 36, 'Buhinda', -1.51500000, 31.22500000, 1),
(374, 36, 'Giheta', -1.51000000, 31.23000000, 1),
(375, 36, 'Itaba', -1.50500000, 31.23500000, 1),
(376, 36, 'Kabanga', -1.50000000, 31.24000000, 1),
(377, 36, 'Kiriba', -1.49500000, 31.24500000, 1),
(378, 36, 'Makebuko', -1.49000000, 31.25000000, 1),
(379, 36, 'Maramvya', -1.48500000, 31.25500000, 1),
(380, 36, 'Mariza', -1.48000000, 31.26000000, 1),
(381, 36, 'Mubuga', -1.47500000, 31.26500000, 1),
(382, 36, 'Mungwa', -1.47000000, 31.27000000, 1),
(383, 36, 'Murenda', -1.46500000, 31.27500000, 1),
(384, 36, 'Nyamugari', -1.46000000, 31.28000000, 1),
(385, 36, 'Rutegama', -1.45500000, 31.28500000, 1),
(386, 37, 'Bugenyuzi', -1.45000000, 31.29000000, 1),
(387, 37, 'Buhiga', -1.44500000, 31.29500000, 1),
(388, 37, 'Buhindye', -1.44000000, 31.30000000, 1),
(389, 37, 'Buhinyuza', -1.43500000, 31.30500000, 1),
(390, 37, 'Cirambo', -1.43000000, 31.31000000, 1),
(391, 37, 'Gisimbawaga', -1.42500000, 31.31500000, 1),
(392, 37, 'Gitaramuka', -1.42000000, 31.32000000, 1),
(393, 37, 'Kibumbwe', -1.41500000, 31.32500000, 1),
(394, 37, 'Mayenzi', -1.41000000, 31.33000000, 1),
(395, 37, 'Mubaragaza', -1.40500000, 31.33500000, 1),
(396, 37, 'Muhweza', -1.40000000, 31.34000000, 1),
(397, 37, 'Mutumba', -1.39500000, 31.34500000, 1),
(398, 37, 'Ntunda', -1.39000000, 31.35000000, 1),
(399, 37, 'Nyamugari', -1.38500000, 31.35500000, 1),
(400, 37, 'Nyaruhinda', -1.38000000, 31.36000000, 1),
(401, 37, 'Rugazi', -1.37500000, 31.36500000, 1),
(402, 38, 'Buhangura', -1.37000000, 31.37000000, 1),
(403, 38, 'Gasura', -1.36500000, 31.37500000, 1),
(404, 38, 'Gatabo', -1.36000000, 31.38000000, 1),
(405, 38, 'Kanyami', -1.35500000, 31.38500000, 1),
(406, 38, 'Kiganda', -1.35000000, 31.39000000, 1),
(407, 38, 'Mushikamo', -1.34500000, 31.39500000, 1),
(408, 38, 'Renga', -1.34000000, 31.40000000, 1),
(409, 38, 'Rutegama', -1.33500000, 31.40500000, 1),
(410, 39, 'Bugarama', -1.33000000, 31.41000000, 1),
(411, 39, 'Bukeye', -1.32500000, 31.41500000, 1),
(412, 39, 'Kibumbu', -1.32000000, 31.42000000, 1),
(413, 39, 'Mbuye', -1.31500000, 31.42500000, 1),
(414, 39, 'Muramvya', -1.31000000, 31.43000000, 1),
(415, 39, 'Nyarucamo', -1.30500000, 31.43500000, 1),
(416, 39, 'Shombo', -1.30000000, 31.44000000, 1),
(417, 40, 'Bisoro', -1.29500000, 31.44500000, 1),
(418, 40, 'Gatwe', -1.29000000, 31.45000000, 1),
(419, 40, 'Gisozi', -1.28500000, 31.45500000, 1),
(420, 40, 'Kanka', -1.28000000, 31.46000000, 1),
(421, 40, 'Kayokwe', -1.27500000, 31.46500000, 1),
(422, 40, 'Makamba', -1.27000000, 31.47000000, 1),
(423, 40, 'Muyebe', -1.26500000, 31.47500000, 1),
(424, 40, 'Mwaro', -3.53330000, 29.68330000, 1),
(425, 40, 'Nyakararo', -1.25500000, 31.48500000, 1),
(426, 40, 'Rorero', -1.25000000, 31.49000000, 1),
(427, 41, 'Fota', -1.24500000, 31.49500000, 1),
(428, 41, 'Kibungere', -1.24000000, 31.50000000, 1),
(429, 41, 'Matongo', -1.23500000, 31.50500000, 1),
(430, 41, 'Mbogora', -1.23000000, 31.51000000, 1),
(431, 41, 'Munago', -1.22500000, 31.51500000, 1),
(432, 41, 'Murago', -1.22000000, 31.52000000, 1),
(433, 41, 'Musama', -1.21500000, 31.52500000, 1),
(434, 41, 'Ndava', -1.21000000, 31.53000000, 1),
(435, 41, 'Nyabihanga', -1.20500000, 31.53500000, 1),
(436, 41, 'Nyarucamo', -1.20000000, 31.54000000, 1),
(437, 41, 'Rusaka', -1.19500000, 31.54500000, 1),
(438, 41, 'Yanza', -1.19000000, 31.55000000, 1),
(439, 42, 'Gatonde', -1.18500000, 31.55500000, 1),
(440, 42, 'Gihogazi', -1.18000000, 31.56000000, 1),
(441, 42, 'Gikombe', -1.17500000, 31.56500000, 1),
(442, 42, 'Masabo', -1.17000000, 31.57000000, 1),
(443, 42, 'Masama', -1.16500000, 31.57500000, 1),
(444, 42, 'Mugogo', -1.16000000, 31.58000000, 1),
(445, 42, 'Munanira', -1.15500000, 31.58500000, 1),
(446, 42, 'Nyabibuye', -1.15000000, 31.59000000, 1),
(447, 42, 'Nyabikere', -1.14500000, 31.59500000, 1),
(448, 42, 'Nyarurambi', -1.14000000, 31.60000000, 1),
(449, 42, 'Rusamaza', -1.13500000, 31.60500000, 1),
(450, 42, 'Rusi', -1.13000000, 31.61000000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `zones_livraison`
--

CREATE TABLE `zones_livraison` (
  `id_zone_liv` int(11) NOT NULL,
  `nom_zone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_province` int(11) DEFAULT NULL,
  `ids_communes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ids_quartiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `cout_base` decimal(8,2) NOT NULL,
  `seuil_livraison_gratuite` decimal(10,2) DEFAULT NULL,
  `cout_par_kg` decimal(6,2) DEFAULT NULL,
  `delai_min_jours` int(11) DEFAULT NULL,
  `delai_max_jours` int(11) DEFAULT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id_adresse`),
  ADD KEY `idx_adresses_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_adresses_gps` (`latitude`,`longitude`),
  ADD KEY `id_province` (`id_province`),
  ADD KEY `id_commune` (`id_commune`),
  ADD KEY `id_quartier` (`id_quartier`),
  ADD KEY `id_zone` (`id_zone`),
  ADD KEY `id_colline` (`id_colline`);

--
-- Index pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  ADD PRIMARY KEY (`id_appro`),
  ADD KEY `idx_appro_produit` (`id_produit`),
  ADD KEY `idx_appro_variante` (`id_variante`),
  ADD KEY `idx_appro_vendeur` (`id_vendeur`);

--
-- Index pour la table `articles_commande`
--
ALTER TABLE `articles_commande`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `idx_articles_commande` (`id_commande`),
  ADD KEY `idx_articles_vendeur` (`id_vendeur`),
  ADD KEY `idx_articles_produit` (`id_produit`),
  ADD KEY `id_variante` (`id_variante`);

--
-- Index pour la table `avis_produits`
--
ALTER TABLE `avis_produits`
  ADD PRIMARY KEY (`id_avis`),
  ADD UNIQUE KEY `uk_avis_produit_commande` (`id_produit`,`id_utilisateur`,`id_commande`),
  ADD KEY `idx_avis_produit` (`id_produit`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id_banner`);

--
-- Index pour la table `blacklist_ips`
--
ALTER TABLE `blacklist_ips`
  ADD PRIMARY KEY (`id_blacklist`),
  ADD UNIQUE KEY `adresse_ip` (`adresse_ip`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `slug_categorie` (`slug_categorie`),
  ADD KEY `idx_categories_parent` (`id_parent`),
  ADD KEY `idx_categories_actif` (`est_actif`);

--
-- Index pour la table `codes_otp`
--
ALTER TABLE `codes_otp`
  ADD PRIMARY KEY (`id_otp`),
  ADD KEY `idx_otp_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `collines`
--
ALTER TABLE `collines`
  ADD PRIMARY KEY (`id_colline`),
  ADD KEY `idx_collines_zone` (`id_zone`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `numero_commande` (`numero_commande`),
  ADD KEY `idx_commandes_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_commandes_statut` (`statut_commande`),
  ADD KEY `idx_commandes_date` (`date_creation`),
  ADD KEY `idx_commandes_transporteur` (`id_transporteur`),
  ADD KEY `id_adresse_livraison` (`id_adresse_livraison`),
  ADD KEY `id_mode_payement` (`id_mode_payement`),
  ADD KEY `id_point_relais` (`id_point_relais`);

--
-- Index pour la table `communes`
--
ALTER TABLE `communes`
  ADD PRIMARY KEY (`id_commune`),
  ADD KEY `idx_communes_province` (`id_province`);

--
-- Index pour la table `config_paiement_vendeur`
--
ALTER TABLE `config_paiement_vendeur`
  ADD PRIMARY KEY (`id_config`),
  ADD KEY `idx_config_vendeur` (`id_vendeur`);

--
-- Index pour la table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id_coupon`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `documents_vendeur`
--
ALTER TABLE `documents_vendeur`
  ADD PRIMARY KEY (`id_document`),
  ADD KEY `idx_documents_vendeur` (`id_vendeur`);

--
-- Index pour la table `evaluations_vendeurs`
--
ALTER TABLE `evaluations_vendeurs`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD UNIQUE KEY `uk_eval_vendeur_commande` (`id_vendeur`,`id_commande`),
  ADD KEY `idx_eval_vendeur` (`id_vendeur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `historique_statut_commande`
--
ALTER TABLE `historique_statut_commande`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `idx_historique_commande` (`id_commande`);

--
-- Index pour la table `images_produit`
--
ALTER TABLE `images_produit`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `idx_images_produit` (`id_produit`),
  ADD KEY `idx_images_variante` (`id_variante`);

--
-- Index pour la table `ips_bloquees`
--
ALTER TABLE `ips_bloquees`
  ADD PRIMARY KEY (`id_ip`),
  ADD UNIQUE KEY `adresse_ip` (`adresse_ip`);

--
-- Index pour la table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  ADD PRIMARY KEY (`id_souhait`),
  ADD UNIQUE KEY `unique_souhait` (`id_utilisateur`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `litiges_commandes`
--
ALTER TABLE `litiges_commandes`
  ADD PRIMARY KEY (`id_litige`),
  ADD KEY `idx_litiges_commande` (`id_commande`),
  ADD KEY `idx_litiges_statut` (`statut`);

--
-- Index pour la table `logs_audit`
--
ALTER TABLE `logs_audit`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_audit_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_audit_type` (`type_action`),
  ADD KEY `idx_audit_date` (`date_creation`);

--
-- Index pour la table `mode_payement`
--
ALTER TABLE `mode_payement`
  ADD PRIMARY KEY (`id_mode_payement`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `newsletter_abonnes`
--
ALTER TABLE `newsletter_abonnes`
  ADD PRIMARY KEY (`id_abonne`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `idx_notif_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_notif_lue` (`est_lue`);

--
-- Index pour la table `paiements_vendeurs`
--
ALTER TABLE `paiements_vendeurs`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `idx_paiements_vendeur` (`id_vendeur`),
  ADD KEY `id_config_paiement` (`id_config_paiement`);

--
-- Index pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD PRIMARY KEY (`id_panier`),
  ADD UNIQUE KEY `unique_panier` (`id_utilisateur`,`id_produit`,`id_variante`),
  ADD KEY `idx_paniers_utilisateur` (`id_utilisateur`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `points_relais`
--
ALTER TABLE `points_relais`
  ADD PRIMARY KEY (`id_point`),
  ADD KEY `idx_relais_gps` (`latitude`,`longitude`),
  ADD KEY `id_commune` (`id_commune`),
  ADD KEY `id_quartier` (`id_quartier`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `slug_produit` (`slug_produit`),
  ADD UNIQUE KEY `code_produit` (`code_produit`),
  ADD KEY `idx_produits_vendeur` (`id_vendeur`),
  ADD KEY `idx_produits_categorie` (`id_categorie`),
  ADD KEY `idx_produits_statut` (`statut`);

--
-- Index pour la table `profils`
--
ALTER TABLE `profils`
  ADD PRIMARY KEY (`id_profil`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Index pour la table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id_province`);

--
-- Index pour la table `qr_confirmations`
--
ALTER TABLE `qr_confirmations`
  ADD PRIMARY KEY (`id_qr`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `idx_qr_commande` (`id_commande`),
  ADD KEY `idx_qr_transporteur` (`id_transporteur`);

--
-- Index pour la table `quartiers`
--
ALTER TABLE `quartiers`
  ADD PRIMARY KEY (`id_quartier`),
  ADD KEY `idx_quartiers_commune` (`id_commune`);

--
-- Index pour la table `retours_remboursements`
--
ALTER TABLE `retours_remboursements`
  ADD PRIMARY KEY (`id_retour`),
  ADD KEY `idx_retours_commande` (`id_commande`),
  ADD KEY `idx_retours_statut` (`statut`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`IdSetting`),
  ADD UNIQUE KEY `KeyValue` (`KeyValue`);

--
-- Index pour la table `soldes_vendeurs`
--
ALTER TABLE `soldes_vendeurs`
  ADD PRIMARY KEY (`id_solde`),
  ADD UNIQUE KEY `id_vendeur` (`id_vendeur`);

--
-- Index pour la table `suivi_gps`
--
ALTER TABLE `suivi_gps`
  ADD PRIMARY KEY (`id_suivi`),
  ADD KEY `idx_suivi_commande` (`id_commande`),
  ADD KEY `idx_suivi_transporteur` (`id_transporteur`),
  ADD KEY `idx_suivi_timestamp` (`timestamp_gps`);

--
-- Index pour la table `tentatives_connexion`
--
ALTER TABLE `tentatives_connexion`
  ADD PRIMARY KEY (`id_tentative`),
  ADD KEY `idx_tentatives_ip` (`adresse_ip`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `transactions_paiement`
--
ALTER TABLE `transactions_paiement`
  ADD PRIMARY KEY (`id_transaction`),
  ADD UNIQUE KEY `reference_interne` (`reference_interne`),
  ADD KEY `idx_transactions_commande` (`id_commande`),
  ADD KEY `idx_transactions_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_transactions_statut` (`statut`),
  ADD KEY `id_mode_payement` (`id_mode_payement`);

--
-- Index pour la table `transporteurs`
--
ALTER TABLE `transporteurs`
  ADD PRIMARY KEY (`id_transporteur`),
  ADD KEY `idx_transporteurs_disponible` (`est_disponible`),
  ADD KEY `idx_transporteurs_statut` (`statut`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_utilisateurs_telephone` (`telephone`);

--
-- Index pour la table `utilisateur_profils`
--
ALTER TABLE `utilisateur_profils`
  ADD PRIMARY KEY (`id_utilisateur`,`id_profil`),
  ADD KEY `id_profil` (`id_profil`);

--
-- Index pour la table `variantes_produit`
--
ALTER TABLE `variantes_produit`
  ADD PRIMARY KEY (`id_variante`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_variantes_produit` (`id_produit`);

--
-- Index pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  ADD PRIMARY KEY (`id_vendeur`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  ADD UNIQUE KEY `slug_boutique` (`slug_boutique`),
  ADD KEY `idx_vendeurs_statut` (`statut`),
  ADD KEY `id_province` (`id_province`),
  ADD KEY `id_commune` (`id_commune`),
  ADD KEY `id_quartier` (`id_quartier`);

--
-- Index pour la table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id_zone`),
  ADD KEY `idx_zones_commune` (`id_commune`);

--
-- Index pour la table `zones_livraison`
--
ALTER TABLE `zones_livraison`
  ADD PRIMARY KEY (`id_zone_liv`),
  ADD KEY `idx_zone_province` (`id_province`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  MODIFY `id_appro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `articles_commande`
--
ALTER TABLE `articles_commande`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avis_produits`
--
ALTER TABLE `avis_produits`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `banners`
--
ALTER TABLE `banners`
  MODIFY `id_banner` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `blacklist_ips`
--
ALTER TABLE `blacklist_ips`
  MODIFY `id_blacklist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `codes_otp`
--
ALTER TABLE `codes_otp`
  MODIFY `id_otp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `collines`
--
ALTER TABLE `collines`
  MODIFY `id_colline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3003;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `communes`
--
ALTER TABLE `communes`
  MODIFY `id_commune` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `config_paiement_vendeur`
--
ALTER TABLE `config_paiement_vendeur`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id_coupon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `documents_vendeur`
--
ALTER TABLE `documents_vendeur`
  MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `evaluations_vendeurs`
--
ALTER TABLE `evaluations_vendeurs`
  MODIFY `id_evaluation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_statut_commande`
--
ALTER TABLE `historique_statut_commande`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `images_produit`
--
ALTER TABLE `images_produit`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `ips_bloquees`
--
ALTER TABLE `ips_bloquees`
  MODIFY `id_ip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  MODIFY `id_souhait` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `litiges_commandes`
--
ALTER TABLE `litiges_commandes`
  MODIFY `id_litige` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logs_audit`
--
ALTER TABLE `logs_audit`
  MODIFY `id_log` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mode_payement`
--
ALTER TABLE `mode_payement`
  MODIFY `id_mode_payement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `newsletter_abonnes`
--
ALTER TABLE `newsletter_abonnes`
  MODIFY `id_abonne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paiements_vendeurs`
--
ALTER TABLE `paiements_vendeurs`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paniers`
--
ALTER TABLE `paniers`
  MODIFY `id_panier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `points_relais`
--
ALTER TABLE `points_relais`
  MODIFY `id_point` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `profils`
--
ALTER TABLE `profils`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id_province` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `qr_confirmations`
--
ALTER TABLE `qr_confirmations`
  MODIFY `id_qr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `quartiers`
--
ALTER TABLE `quartiers`
  MODIFY `id_quartier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `retours_remboursements`
--
ALTER TABLE `retours_remboursements`
  MODIFY `id_retour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `IdSetting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `soldes_vendeurs`
--
ALTER TABLE `soldes_vendeurs`
  MODIFY `id_solde` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `suivi_gps`
--
ALTER TABLE `suivi_gps`
  MODIFY `id_suivi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tentatives_connexion`
--
ALTER TABLE `tentatives_connexion`
  MODIFY `id_tentative` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `transactions_paiement`
--
ALTER TABLE `transactions_paiement`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `transporteurs`
--
ALTER TABLE `transporteurs`
  MODIFY `id_transporteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `variantes_produit`
--
ALTER TABLE `variantes_produit`
  MODIFY `id_variante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  MODIFY `id_vendeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `zones`
--
ALTER TABLE `zones`
  MODIFY `id_zone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=451;

--
-- AUTO_INCREMENT pour la table `zones_livraison`
--
ALTER TABLE `zones_livraison`
  MODIFY `id_zone_liv` int(11) NOT NULL AUTO_INCREMENT;

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
