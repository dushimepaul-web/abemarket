-- Table contact_messages pour stocker les messages du formulaire de contact
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id_message` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telephone` VARCHAR(50) DEFAULT NULL,
  `sujet` VARCHAR(100) DEFAULT 'question',
  `message` TEXT NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `id_utilisateur` INT(11) DEFAULT NULL,
  `statut` ENUM('nouveau', 'lu', 'repondu') DEFAULT 'nouveau',
  `date_creation` DATETIME NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
