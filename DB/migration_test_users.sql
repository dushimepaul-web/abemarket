-- =============================================
-- MIGRATION : Correction des profils et ajout des données manquantes
-- pour tester les dashboards multi-rôles
-- =============================================

-- 0. Ajouter la colonne permissions si elle manque (présente dans le schéma mais absente de la DB)
ALTER TABLE `profils` ADD COLUMN `permissions` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL AFTER `description`;

-- 1. Ajouter l'utilisateur admin manquant dans vendeurs (pour que le dashboard vendeur fonctionne)
-- L'admin (id=1) a déjà un vendeur (id_vendeur=1) mais vendeur@example.com (id=3) n'en a pas
INSERT INTO `vendeurs` (`id_vendeur`, `id_utilisateur`, `nom_boutique`, `slug_boutique`, `description`, `type_vendeur`, `taux_commission`, `delai_paiement_jours`, `est_approuve`, `statut`, `date_creation`)
VALUES (NULL, 3, 'Boutique Pierre', 'boutique-pierre', 'Boutique de test', 'particulier', 10.00, 7, 1, 'actif', NOW())
ON DUPLICATE KEY UPDATE `est_approuve` = 1, `statut` = 'actif';

-- 2. Ajouter le transporteur pour livreur@example.com (id_utilisateur=4)
INSERT INTO `transporteurs` (`id_transporteur`, `id_utilisateur`, `type`, `nom`, `telephone`, `est_disponible`, `statut`, `date_creation`)
VALUES (NULL, 4, 'interne', 'Paul Livreur', '+25764567890', 1, 'actif', NOW())
ON DUPLICATE KEY UPDATE `id_utilisateur` = 4;

-- 3. Correction des profils :
--    - livreur (id 4) : profil 6 (livreur) en premier, profil 4 (vendeur) en second
--    - support (id 7) : profil 8 (support) au lieu de profil 4 (vendeur)

-- Livreur : mettre à jour (4,4) → (4,6) et ajouter (4,4) comme secondaire
DELETE FROM `utilisateur_profils` WHERE `id_utilisateur` = 4 AND `id_profil` IN (4, 6);
INSERT INTO `utilisateur_profils` (`id_utilisateur`, `id_profil`, `attribue_par`, `date_attribution`) VALUES
(4, 6, 1, NOW()),
(4, 4, 1, NOW());

-- Support : remplacer (7,4) par (7,8)
DELETE FROM `utilisateur_profils` WHERE `id_utilisateur` = 7;
INSERT INTO `utilisateur_profils` (`id_utilisateur`, `id_profil`, `attribue_par`, `date_attribution`) VALUES
(7, 8, 1, NOW());
