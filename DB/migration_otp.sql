-- Migration SQL pour sécuriser codes_otp et optimiser les performances

-- Étape 1 : Corriger le type de la date d'expiration pour supprimer l'option 'ON UPDATE CURRENT_TIMESTAMP' automatique
ALTER TABLE `codes_otp` MODIFY `date_expiration` DATETIME NOT NULL;

-- Étape 2 : Ajouter un index composite pour optimiser la recherche et la validation des codes OTP actifs
ALTER TABLE `codes_otp` ADD INDEX `idx_otp_validation` (`id_utilisateur`, `utilise`, `type_otp`);
