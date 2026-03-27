-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : mariadb_gp:3306
-- Généré le : ven. 27 mars 2026 à 01:50
-- Version du serveur : 11.8.6-MariaDB-ubu2404
-- Version de PHP : 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_projets`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `projet_id` int(10) UNSIGNED NOT NULL,
  `projet_etape_id` int(10) UNSIGNED DEFAULT NULL,
  `type_document_id` smallint(5) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `statut_document` enum('brouillon','soumis','en_revision','a_corriger','valide','refuse','archive') NOT NULL DEFAULT 'brouillon',
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modification` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `projet_id`, `projet_etape_id`, `type_document_id`, `titre`, `description`, `statut_document`, `cree_par`, `date_creation`, `date_modification`) VALUES
(17, 6, NULL, 1, 'test', NULL, 'brouillon', 3, '2026-03-25 06:05:34', '2026-03-25 06:05:34'),
(18, 6, 21, 1, 'test 5', NULL, 'brouillon', 1, '2026-03-25 06:29:25', '2026-03-25 06:29:25'),
(19, 6, 21, 4, 'test création admin', NULL, 'brouillon', 3, '2026-03-25 22:53:39', '2026-03-25 22:53:39'),
(20, 6, 29, 1, 'test création admin', NULL, 'brouillon', 1, '2026-03-26 02:36:14', '2026-03-26 02:36:14'),
(21, 6, 29, 8, 'test avec policy', NULL, 'brouillon', 1, '2026-03-26 03:04:28', '2026-03-26 03:04:28'),
(22, 6, 29, 1, 'test upload non admin avec droits upload', NULL, 'brouillon', 5, '2026-03-26 03:18:48', '2026-03-26 03:18:48'),
(23, 6, 29, 1, 'test admin avec droit 2', NULL, 'brouillon', 5, '2026-03-26 03:26:52', '2026-03-26 03:26:52'),
(24, 6, 32, 3, '2026_03_26_test_upload_admin', NULL, 'brouillon', 1, '2026-03-26 03:31:08', '2026-03-26 03:31:08'),
(25, 6, 32, 6, 'test', NULL, 'brouillon', 1, '2026-03-26 03:34:50', '2026-03-26 03:34:50'),
(26, 6, 23, 1, 'test', NULL, 'brouillon', 5, '2026-03-26 03:39:07', '2026-03-26 03:39:07'),
(27, 6, 29, 1, 'test', NULL, 'brouillon', 5, '2026-03-26 03:54:32', '2026-03-26 03:54:32'),
(28, 6, 29, 2, 'test', NULL, 'brouillon', 1, '2026-03-26 03:55:24', '2026-03-26 03:55:24'),
(29, 9, 34, 1, 'CR_reu_01_ADSUP_AMO', NULL, 'brouillon', 1, '2026-03-26 21:58:32', '2026-03-26 21:58:32');

-- --------------------------------------------------------

--
-- Structure de la table `document_commentaires`
--

CREATE TABLE `document_commentaires` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `document_version_id` int(10) UNSIGNED DEFAULT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `type_commentaire` enum('simple','reserve','demande_correction','validation','refus') NOT NULL DEFAULT 'simple',
  `commentaire` text NOT NULL,
  `est_resolu` tinyint(1) NOT NULL DEFAULT 0,
  `date_commentaire` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_validations`
--

CREATE TABLE `document_validations` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `document_version_id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `decision` enum('valide','refuse','demande_correction') NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_decision` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_versions`
--

CREATE TABLE `document_versions` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `numero_version` smallint(5) UNSIGNED NOT NULL,
  `nom_fichier_original` varchar(255) NOT NULL,
  `nom_fichier_stocke` varchar(255) NOT NULL,
  `chemin_fichier` varchar(500) NOT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `taille_octets` bigint(20) UNSIGNED DEFAULT NULL,
  `hash_fichier` char(64) DEFAULT NULL,
  `depose_par` bigint(20) UNSIGNED NOT NULL,
  `commentaire_version` text DEFAULT NULL,
  `est_version_courante` tinyint(1) NOT NULL DEFAULT 1,
  `date_depot` datetime NOT NULL DEFAULT current_timestamp(),
  `type_validation` varchar(255) DEFAULT NULL,
  `commentaire_validation` text DEFAULT NULL,
  `valide_par` bigint(20) UNSIGNED DEFAULT NULL,
  `date_validation` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document_versions`
--

INSERT INTO `document_versions` (`id`, `document_id`, `numero_version`, `nom_fichier_original`, `nom_fichier_stocke`, `chemin_fichier`, `extension`, `mime_type`, `taille_octets`, `hash_fichier`, `depose_par`, `commentaire_version`, `est_version_courante`, `date_depot`, `type_validation`, `commentaire_validation`, `valide_par`, `date_validation`) VALUES
(21, 17, 1, '01_13AT2018_initiale.pdf', '348d9404-433e-4c94-ada5-c6416c0681bd.pdf', 'documents/348d9404-433e-4c94-ada5-c6416c0681bd.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 3, 'Version initiale', 0, '2026-03-25 06:05:34', NULL, NULL, NULL, NULL),
(22, 17, 2, '01_13AT2018_initiale.txt', '60138327-8b46-4db6-800d-845a9d85ba28.txt', 'documents/60138327-8b46-4db6-800d-845a9d85ba28.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 3, 'nouvelle version', 1, '2026-03-25 06:06:38', NULL, NULL, NULL, NULL),
(23, 18, 1, '01_13AT2018_initiale.txt', '1f74e197-cefc-4658-9c9d-c0606f0bf82f.txt', 'documents/1f74e197-cefc-4658-9c9d-c0606f0bf82f.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 1, 'Version initiale', 0, '2026-03-25 06:29:25', NULL, NULL, NULL, NULL),
(24, 18, 2, '01_13AT2018_initiale.txt', 'fb311404-1c8c-4952-afce-e65dea95a38b.txt', 'documents/fb311404-1c8c-4952-afce-e65dea95a38b.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 1, NULL, 1, '2026-03-25 20:15:04', NULL, NULL, NULL, NULL),
(25, 19, 1, '01_13AT2018_initiale.pdf', '1bfaaf52-6b33-40d7-b15a-c4ca35664982.pdf', 'documents/1bfaaf52-6b33-40d7-b15a-c4ca35664982.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 3, 'Version initiale', 1, '2026-03-25 22:53:39', NULL, NULL, NULL, NULL),
(26, 20, 1, '01_13AT2018_initiale.pdf', '9979bab9-39f6-4ec6-bca6-886e84664a47.pdf', 'documents/9979bab9-39f6-4ec6-bca6-886e84664a47.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 1, 'Version initiale', 0, '2026-03-26 02:36:14', NULL, NULL, NULL, NULL),
(27, 20, 2, '01_13AT2018_initiale.pdf', '233e96f4-ddbe-45b2-81ce-bb8f21f3bfdb.pdf', 'documents/233e96f4-ddbe-45b2-81ce-bb8f21f3bfdb.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 1, 'test de commentaire', 1, '2026-03-26 02:38:09', NULL, NULL, NULL, NULL),
(28, 21, 1, '01_13AT2018_initiale.txt', '718e125f-787b-4914-ac9a-021de984fe55.txt', 'documents/718e125f-787b-4914-ac9a-021de984fe55.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 1, 'Version initiale', 1, '2026-03-26 03:04:28', NULL, NULL, NULL, NULL),
(29, 22, 1, '01_13AT2018_initiale.pdf', '616b4cea-10d4-4ad8-aedc-81092402af96.pdf', 'documents/616b4cea-10d4-4ad8-aedc-81092402af96.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 5, 'Version initiale', 1, '2026-03-26 03:18:48', NULL, NULL, NULL, NULL),
(30, 23, 1, '01_13AT2018_initiale.pdf', '64bc01bf-a387-4d7a-8b19-debed541e113.pdf', 'documents/64bc01bf-a387-4d7a-8b19-debed541e113.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 5, 'Version initiale', 1, '2026-03-26 03:26:52', NULL, NULL, NULL, NULL),
(31, 24, 1, '01_13AT2018_initiale.pdf', '7697d263-80e2-4b40-955c-4ae05d79978e.pdf', 'documents/7697d263-80e2-4b40-955c-4ae05d79978e.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 1, 'Version initiale', 1, '2026-03-26 03:31:08', NULL, NULL, NULL, NULL),
(32, 25, 1, '01_13AT2018_initiale.txt', '88dcadc7-19d0-4f24-ac56-5bdf3e44f278.txt', 'documents/88dcadc7-19d0-4f24-ac56-5bdf3e44f278.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 1, 'Version initiale', 0, '2026-03-26 03:34:50', NULL, NULL, NULL, NULL),
(33, 25, 2, '01_13AT2018_initiale.pdf', '9ed20c2d-f8ec-4010-8179-8911b53214e0.pdf', 'documents/9ed20c2d-f8ec-4010-8179-8911b53214e0.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 1, 'test', 1, '2026-03-26 03:35:08', NULL, NULL, NULL, NULL),
(34, 26, 1, '01_13AT2018_initiale.pdf', '7b395939-4b5b-49e2-b77f-31fa47017272.pdf', 'documents/7b395939-4b5b-49e2-b77f-31fa47017272.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 5, 'Version initiale', 1, '2026-03-26 03:39:07', NULL, NULL, NULL, NULL),
(35, 27, 1, '01_13AT2018_initiale.pdf', 'e492b349-0a49-4c77-8c95-0ffc3479a526.pdf', 'documents/e492b349-0a49-4c77-8c95-0ffc3479a526.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 5, 'Version initiale', 1, '2026-03-26 03:54:32', NULL, NULL, NULL, NULL),
(36, 28, 1, '01_13AT2018_initiale.txt', '7f4e9e8a-1e66-455c-9f9b-1ceaf6fccfe7.txt', 'documents/7f4e9e8a-1e66-455c-9f9b-1ceaf6fccfe7.txt', 'txt', 'text/plain', 14298, 'b01442e516890a23a52abb8e5ad000b3e133bcaf2a366a910f85ee7051c84dbe', 1, 'Version initiale', 1, '2026-03-26 03:55:24', NULL, NULL, NULL, NULL),
(37, 29, 1, '01_13AT2018_initiale.pdf', '17ab9676-50f7-427c-b09f-a5bc965572a3.pdf', 'documents/17ab9676-50f7-427c-b09f-a5bc965572a3.pdf', 'pdf', 'application/pdf', 2758203, 'c77ee8c7fdadfbab24ff738c2cb0747416b76ec76b20bbed606bdc16db018c1d', 1, 'Version initiale', 1, '2026-03-26 21:58:32', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `document_version_validations`
--

CREATE TABLE `document_version_validations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_version_id` int(10) UNSIGNED NOT NULL,
  `type_validation` varchar(255) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `valide_par` bigint(20) UNSIGNED DEFAULT NULL,
  `date_validation` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document_version_validations`
--

INSERT INTO `document_version_validations` (`id`, `document_version_id`, `type_validation`, `commentaire`, `valide_par`, `date_validation`, `created_at`, `updated_at`) VALUES
(8, 22, 'refus', 'refus !', 3, '2026-03-25 06:06:59', '2026-03-25 06:06:59', '2026-03-25 06:06:59'),
(9, 22, 'validation_technique', NULL, 3, '2026-03-25 06:07:24', '2026-03-25 06:07:24', '2026-03-25 06:07:24'),
(10, 22, 'validation_administrative', NULL, 3, '2026-03-25 06:07:34', '2026-03-25 06:07:34', '2026-03-25 06:07:34'),
(11, 22, 'validation_financiere', NULL, 3, '2026-03-25 06:07:39', '2026-03-25 06:07:39', '2026-03-25 06:07:39'),
(12, 23, 'validation_technique', NULL, 1, '2026-03-25 06:29:36', '2026-03-25 06:29:36', '2026-03-25 06:29:36'),
(13, 23, 'validation_financiere', NULL, 1, '2026-03-25 20:14:37', '2026-03-25 20:14:37', '2026-03-25 20:14:37'),
(14, 23, 'refus', 'test', 1, '2026-03-25 20:14:49', '2026-03-25 20:14:49', '2026-03-25 20:14:49'),
(15, 37, 'validation_administrative', NULL, 1, '2026-03-26 22:04:33', '2026-03-26 22:04:33', '2026-03-26 22:04:33');

-- --------------------------------------------------------

--
-- Structure de la table `etapes_modele`
--

CREATE TABLE `etapes_modele` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `ordre_affichage` smallint(5) UNSIGNED NOT NULL,
  `phase` enum('programmation','lancement','consultation','execution','cloture') NOT NULL,
  `obligatoire` tinyint(1) NOT NULL DEFAULT 1,
  `type_niveau` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etapes_modele`
--

INSERT INTO `etapes_modele` (`id`, `code`, `libelle`, `description`, `ordre_affichage`, `phase`, `obligatoire`, `type_niveau`) VALUES
(1, 'programmation', 'Programmation', 'Définition du besoin et cadrage initial', 1, 'programmation', 1, 'étape'),
(2, 'lancement', 'Lancement', 'Démarrage officiel de la procédure', 2, 'lancement', 1, 'étape'),
(3, 'consultation', 'Consultation', 'Analyse des offres et choix', 3, 'consultation', 1, 'étape'),
(4, 'execution', 'Exécution', 'Suivi opérationnel du projet', 4, 'execution', 1, 'étape'),
(5, 'cloture', 'Clôture', 'Finalisation, réception et archivage', 5, 'cloture', 1, 'étape'),
(6, 'definition_besoin', 'Définition du besoin', 'Analyse technique et fonctionnelle de ce qu\'il faut acheter', 1, 'programmation', 1, 'sous-étape'),
(7, 'estimation_financiere', 'Estimation financière', 'Évaluation du montant prévisionnel (pour choisir la procédure)', 2, 'programmation', 1, 'sous-étape'),
(8, 'sourcing_etude_marche', 'Sourcing (Étude de marché)', 'Rencontre avec des fournisseurs potentiels pour connaître l\'état de l\'art (sans favoriser personne)', 3, 'programmation', 0, 'sous-étape'),
(9, 'choix_procedure', 'Choix de la procédure', 'Détermination de la forme (Appel d\'offres ouvert/restreint, MAPA, etc.) et de l\'allotissement (découpage en lots)', 4, 'programmation', 1, 'sous-étape'),
(10, 'redaction_dce', 'Rédaction du DCE', 'Élaboration des pièces contractuelles (Cahier des charges, Règlement de consultation, etc.)', 5, 'programmation', 1, 'sous-étape'),
(11, 'publication_aapc', 'Publication de l\'avis (AAPC)', 'Diffusion sur les supports officiels (BOAMP, JOUE, presse) et sur le profil d\'acheteur', 1, 'lancement', 1, 'sous-étape'),
(12, 'mise_disposition_dce', 'Mise à disposition du DCE', 'Téléchargement libre pour les candidats', 2, 'lancement', 1, 'sous-étape'),
(13, 'gestion_questions_reponses', 'Gestion des questions/réponses', 'Répondre aux précisions demandées par les candidats (en toute transparence pour tous)', 3, 'lancement', 0, 'sous-étape'),
(14, 'reception_plis', 'Réception des plis', 'Clôture de la période de dépôt et enregistrement des offres', 4, 'lancement', 1, 'sous-étape'),
(15, 'ouverture_plis', 'Ouverture des plis', 'Vérification de l\'intégrité des dossiers', 1, 'consultation', 1, 'sous-étape'),
(16, 'examen_candidatures', 'Examen des candidatures', 'Vérification des capacités administratives, techniques et financières des entreprises', 2, 'consultation', 1, 'sous-étape'),
(17, 'analyse_offres', 'Analyse des offres', 'Notation des offres selon les critères définis (prix, valeur technique, etc.)', 3, 'consultation', 1, 'sous-étape'),
(18, 'negociation', 'Négociation (si autorisée)', 'Échanges avec les candidats pour optimiser les offres', 4, 'consultation', 0, 'sous-étape'),
(19, 'choix_attributaire', 'Choix de l\'attributaire', 'Sélection de l\'offre économiquement la plus avantageuse et rédaction du rapport d\'analyse', 5, 'consultation', 1, 'sous-étape'),
(20, 'reception_prestations_travaux', 'Réception des prestations/travaux', 'Signature du procès-verbal de réception (avec ou sans réserves)', 1, 'cloture', 1, 'sous-étape'),
(21, 'dgd', 'Décompte Général Définitif (DGD)', 'Arrêt final des comptes du marché', 2, 'cloture', 1, 'sous-étape'),
(22, 'liberation_garanties', 'Libération des garanties', 'Restitution de la retenue de garantie si tout est conforme', 3, 'cloture', 0, 'sous-étape'),
(23, 'bilan_archivage', 'Bilan et archivage', 'Retour d\'expérience (REX) et conservation légale du dossier', 4, 'cloture', 1, 'sous-étape'),
(28, 'notification', 'Notification', 'Signature officielle du marché qui marque le point de départ juridique', 1, 'execution', 1, 'sous-étape'),
(29, 'reunion_lancement', 'Réunion de lancement', 'Cadrage opérationnel avec le titulaire', 2, 'execution', 0, 'sous-étape'),
(30, 'suivi_prestations', 'Suivi des prestations', 'Contrôle de la qualité, respect des délais et des livrables', 3, 'execution', 1, 'sous-étape'),
(31, 'gestion_financiere', 'Gestion financière', 'Vérification du service fait, paiement des factures et gestion des éventuelles révisions de prix', 4, 'execution', 1, 'sous-étape'),
(32, 'gestion_modifications', 'Gestion des modifications', 'Rédaction d\'avenants si le périmètre doit évoluer légèrement', 5, 'execution', 0, 'sous-étape');

-- --------------------------------------------------------

--
-- Structure de la table `etape_commentaires`
--

CREATE TABLE `etape_commentaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `projet_etape_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `contenu` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etape_commentaires`
--

INSERT INTO `etape_commentaires` (`id`, `projet_etape_id`, `user_id`, `contenu`, `created_at`, `updated_at`) VALUES
(10, 21, 1, 'Bienvenue dans les commentaires !', '2026-03-26 01:45:30', '2026-03-26 01:45:30'),
(11, 33, 1, 'test commentaires', '2026-03-26 21:53:11', '2026-03-26 21:53:11');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal_audit`
--

CREATE TABLE `journal_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED DEFAULT NULL,
  `projet_id` int(10) UNSIGNED DEFAULT NULL,
  `objet_type` varchar(50) NOT NULL,
  `objet_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `details_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_json`)),
  `adresse_ip` varchar(45) DEFAULT NULL,
  `date_action` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_19_064921_add_is_admin_to_users_table', 2),
(5, '2026_03_20_071451_add_parent_id_to_projet_etapes_table', 3),
(6, '2026_03_24_052459_create_etape_commentaires_table', 4),
(7, '2026_03_25_020611_add_motif_refus_to_projet_etapes_table', 5),
(8, '2026_03_25_030558_add_validation_fields_to_documents_table', 6),
(9, '2026_03_25_041629_move_validation_fields_from_documents_to_document_versions_table', 7),
(10, '2026_03_25_044510_create_document_version_validations_table', 8),
(11, '2026_03_25_205145_create_role_type_document_permissions_table', 9),
(12, '2026_03_25_205614_create_role_validation_permissions_table', 10),
(13, '2026_03_26_052404_add_profile_fields_to_users_table', 11),
(14, '2026_03_26_064141_add_is_active_to_users_table', 12);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `type_notification` varchar(50) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `lien_interne` varchar(500) DEFAULT NULL,
  `est_lue` tinyint(1) NOT NULL DEFAULT 0,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(10) UNSIGNED NOT NULL,
  `code_projet` varchar(50) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type_projet` varchar(100) DEFAULT NULL,
  `statut_global` enum('brouillon','en_preparation','en_cours','suspendu','termine','archive') NOT NULL DEFAULT 'brouillon',
  `budget_previsionnel` decimal(15,2) DEFAULT NULL,
  `date_debut_prevue` date DEFAULT NULL,
  `date_fin_prevue` date DEFAULT NULL,
  `date_fin_reelle` date DEFAULT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modification` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `code_projet`, `intitule`, `description`, `type_projet`, `statut_global`, `budget_previsionnel`, `date_debut_prevue`, `date_fin_prevue`, `date_fin_reelle`, `cree_par`, `date_creation`, `date_modification`) VALUES
(6, '2026_01', 'Archives Wallis', 'Construction d\'un bâtiment d\'archives sur l\'île de Wallis', 'construction', 'en_preparation', NULL, '2026-03-27', '2028-05-31', NULL, 3, '2026-03-25 06:02:35', '2026-03-26 01:33:50'),
(7, '2026_02', 'Assemblée territoriale', 'Démolition et reconstruction du bâtiment administratif de l\'Assemblée territoriale sur l\'île de Wallis', 'démolition et construction', 'en_cours', NULL, '2026-03-26', '2027-12-20', NULL, 1, '2026-03-26 01:32:38', '2026-03-26 01:34:22'),
(8, '2026_03', 'Hôpital de Kaleveleve', 'Construction d\'un nouvel hôpital sur l\'île de Futuna', 'construction', 'brouillon', NULL, NULL, NULL, NULL, 1, '2026-03-26 01:35:55', '2026-03-26 01:35:55'),
(9, '2026_04', 'Lycée d\'État de Mata-Utu', 'Marché de rénovation du Lycée', 'Rénovation', 'en_preparation', NULL, NULL, NULL, NULL, 1, '2026-03-26 21:50:17', '2026-03-26 21:50:17');

-- --------------------------------------------------------

--
-- Structure de la table `projet_etapes`
--

CREATE TABLE `projet_etapes` (
  `id` int(10) UNSIGNED NOT NULL,
  `projet_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `etape_modele_id` smallint(5) UNSIGNED NOT NULL,
  `titre_personnalise` varchar(255) DEFAULT NULL,
  `ordre_reel` smallint(5) UNSIGNED NOT NULL,
  `statut` enum('a_faire','en_cours','en_attente_validation','validee','refusee','bloquee','cloturee') NOT NULL DEFAULT 'a_faire',
  `motif_refus` text DEFAULT NULL,
  `date_ouverture` datetime DEFAULT NULL,
  `date_cloture` datetime DEFAULT NULL,
  `validee_par` int(10) UNSIGNED DEFAULT NULL,
  `commentaire_validation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projet_etapes`
--

INSERT INTO `projet_etapes` (`id`, `projet_id`, `parent_id`, `etape_modele_id`, `titre_personnalise`, `ordre_reel`, `statut`, `motif_refus`, `date_ouverture`, `date_cloture`, `validee_par`, `commentaire_validation`) VALUES
(21, 6, NULL, 1, NULL, 1, 'validee', NULL, NULL, '2026-03-26 01:41:34', 1, NULL),
(22, 6, 21, 11, NULL, 1, 'validee', NULL, NULL, NULL, NULL, NULL),
(23, 6, 21, 12, NULL, 2, 'validee', NULL, NULL, NULL, NULL, NULL),
(24, 6, NULL, 2, NULL, 2, 'validee', NULL, '2026-03-26 01:41:43', '2026-03-26 01:41:54', 1, NULL),
(25, 6, NULL, 3, NULL, 3, 'en_attente_validation', NULL, '2026-03-26 01:42:00', NULL, NULL, NULL),
(26, 6, NULL, 4, NULL, 4, 'en_cours', NULL, NULL, NULL, NULL, NULL),
(28, 6, NULL, 5, NULL, 5, 'a_faire', NULL, NULL, NULL, NULL, NULL),
(29, 6, 21, 8, NULL, 3, 'a_faire', NULL, NULL, NULL, NULL, NULL),
(30, 6, 21, 9, NULL, 4, 'a_faire', NULL, NULL, NULL, NULL, NULL),
(31, 6, 21, 10, NULL, 5, 'a_faire', NULL, NULL, NULL, NULL, NULL),
(32, 6, 24, 11, NULL, 1, 'a_faire', NULL, NULL, NULL, NULL, NULL),
(33, 9, NULL, 1, NULL, 1, 'en_cours', NULL, '2026-03-26 21:51:35', NULL, NULL, NULL),
(34, 9, 33, 6, NULL, 1, 'en_cours', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `projet_etape_validateurs`
--

CREATE TABLE `projet_etape_validateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `projet_etape_id` int(10) UNSIGNED NOT NULL,
  `type_cible` enum('utilisateur','role','service') NOT NULL,
  `cible_id` int(10) UNSIGNED NOT NULL,
  `validation_obligatoire` tinyint(1) NOT NULL DEFAULT 1,
  `ordre_validation` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `nb_validations_min` smallint(5) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet_etape_validations`
--

CREATE TABLE `projet_etape_validations` (
  `id` int(10) UNSIGNED NOT NULL,
  `projet_etape_id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `decision` enum('valide','refuse','demande_correction') NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_decision` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet_utilisateurs`
--

CREATE TABLE `projet_utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `projet_id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `fonction_projet` varchar(150) DEFAULT NULL,
  `est_chef_projet` tinyint(1) NOT NULL DEFAULT 0,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `date_affectation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projet_utilisateurs`
--

INSERT INTO `projet_utilisateurs` (`id`, `projet_id`, `utilisateur_id`, `fonction_projet`, `est_chef_projet`, `actif`, `date_affectation`) VALUES
(7, 6, 4, 'Conducteur d\'opération Ingénierie Publique', 1, 1, '2026-03-26 01:43:24'),
(8, 6, 3, 'Chargée de mission gestion et performance', 0, 1, '2026-03-26 01:43:47'),
(9, 9, 4, 'Conducteur d\'opération Ingénierie Publique', 0, 1, '2026-03-26 21:52:12'),
(10, 9, 3, 'Chargée de mission gestion et performance', 0, 1, '2026-03-26 21:52:25');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `code`, `libelle`, `description`) VALUES
(1, 'administrateur', 'Administrateur', 'Administration complète de l’application'),
(2, 'chef_projet', 'Chef de projet', 'Pilotage du projet et suivi global'),
(3, 'validation_technique', 'Validation technique', 'Validation des éléments techniques'),
(4, 'validation_financiere', 'Validation financière', 'Validation des éléments financiers'),
(5, 'lecteur', 'Lecteur', 'Consultation uniquement'),
(6, 'validation_administrative', 'Validation administrative', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_type_document_permissions`
--

CREATE TABLE `role_type_document_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` smallint(5) UNSIGNED NOT NULL,
  `type_document_id` smallint(5) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 0,
  `can_upload` tinyint(1) NOT NULL DEFAULT 0,
  `can_download` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_type_document_permissions`
--

INSERT INTO `role_type_document_permissions` (`id`, `role_id`, `type_document_id`, `can_view`, `can_upload`, `can_download`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_validation_permissions`
--

CREATE TABLE `role_validation_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` smallint(5) UNSIGNED NOT NULL,
  `validation_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_validation_permissions`
--

INSERT INTO `role_validation_permissions` (`id`, `role_id`, `validation_type`, `created_at`, `updated_at`) VALUES
(1, 4, 'validation_financiere', '2026-03-26 05:56:41', '2026-03-26 05:56:41'),
(2, 3, 'validation_technique', '2026-03-26 05:56:41', '2026-03-26 05:56:41'),
(3, 6, 'validation_administrative', '2026-03-26 05:56:41', '2026-03-26 05:56:41');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `code`, `nom`, `description`) VALUES
(1, 'ingenierie', 'Pôle ingénierie', 'Suivi technique et ingénierie'),
(2, 'marches_publics', 'Service des marchés publics', 'Gestion des procédures de marchés'),
(3, 'finances', 'Service des finances', 'Suivi financier et budgétaire'),
(4, 'direction', 'Direction', 'Validation hiérarchique et arbitrage');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types_documents`
--

CREATE TABLE `types_documents` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `domaine` enum('technique','administratif','financier','mixte') NOT NULL DEFAULT 'administratif',
  `actif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_documents`
--

INSERT INTO `types_documents` (`id`, `code`, `libelle`, `description`, `domaine`, `actif`) VALUES
(1, 'compte_rendu', 'Compte rendu', 'Compte rendu de réunion ou de chantier', 'administratif', 1),
(2, 'note_technique', 'Note technique', 'Document technique ou avis technique', 'technique', 1),
(3, 'contrat', 'Contrat', 'Contrat ou pièce contractuelle', 'administratif', 1),
(4, 'avenant', 'Avenant', 'Avenant ou modification contractuelle', 'administratif', 1),
(5, 'plan', 'Plan', 'Plan, schéma ou pièce graphique', 'technique', 1),
(6, 'tableau_financier', 'Tableau financier', 'Suivi financier ou budgétaire', 'financier', 1),
(7, 'photo', 'Photo', 'Photo de chantier ou pièce visuelle', 'technique', 1),
(8, 'rapport_analyse', 'Rapport d’analyse', 'Rapport d’analyse des offres ou candidatures', 'mixte', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `fonction` varchar(255) DEFAULT NULL,
  `structure` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `prenom`, `fonction`, `structure`, `email`, `is_admin`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'GRUSON', 'Thibault', 'Chargé des ressources documentaires et numériques', 'ADSUP', 'thibault.gruson@adsup.wf', 1, 1, NULL, '$2y$12$YFXGyBEUneA11HvFLm1L1eatYblVsA2nt0fiL3UDw6l0TaAWkJKNe', NULL, '2026-03-19 06:45:05', '2026-03-19 06:45:05'),
(2, 'deuxieme_utilisateur', NULL, NULL, NULL, 'deuxieme_utilisateur@test.com', 0, 1, NULL, '$2y$12$2IJ3ufjTNvljqar2e92aOu8WWjkl2VIZLtIpAlzPjkuW2HGI2B/Iq', NULL, '2026-03-19 21:42:53', '2026-03-19 21:42:53'),
(3, 'Audrey', NULL, NULL, 'ADSUP', 'audrey.battistel@wallis-et-futuna.pref.gouv.fr', 1, 1, NULL, '$2y$12$aoFQwTdzttbKmpa7K8sLz.2XgucrDRETLqWx.Tn/MePaR72NA6H4.', NULL, '2026-03-23 03:46:34', '2026-03-23 03:46:34'),
(4, 'PEGORARO', 'Vincent', 'Conducteur d\'Opération Ingénirie Publique', 'ADSUP', 'vincent.pegoraro@wallis-et-futuna.pref.gouv.fr', 1, 1, NULL, '$2y$12$ShSJ.91mtKKmHzWBGNzycel1wDXnfcDSwWNJ2CHPE9no2Y3I5kUjK', 'OJRaaDgrnDtZL28nLyGm9nEsot0BDGGRWTDahCX1KOVCIuzTWIhlHe9WgkfZ', '2026-03-23 03:52:00', '2026-03-26 22:25:38'),
(5, 'Test Upload', NULL, NULL, NULL, 'test.upload@example.com', 0, 1, NULL, '$2y$12$qVEKpByaGPZOXDD6L9vBx.BDkOYXP9QVT2k39PF6oPLfkGatA2U/e', 'D1QOjZ2XA5vbwkyaw9IkF16SsBZNSN6xksyPvEjIBZezVeZupAnnjAs4LjIW', '2026-03-26 03:06:23', '2026-03-26 03:06:23'),
(6, 'test', NULL, NULL, NULL, 'test.utilisateur@gestionpro.com', 0, 1, NULL, '$2y$12$18hwmg98Fj3J5JLQdc3dz.MJQIl03qAmTEuaEA9K5SDxGM4PSG9fi', NULL, '2026-03-26 05:09:51', '2026-03-26 05:09:51'),
(7, 'Test User 2', NULL, NULL, NULL, 'test.user2@example.com', 0, 1, NULL, '$2y$12$l90Je6JPcJ7cNh4/.V2QZuuAuSj8VxvGdTF/jOrqwKbauxBO0X2Rm', NULL, '2026-03-26 05:11:32', '2026-03-26 05:11:32'),
(8, 'Test User 3', NULL, NULL, NULL, 'test.user3@example.com', 0, 1, NULL, '$2y$12$qZDhVWdxuWEL74eY8YIN4u9DBvfveHC5cFmEhdQj8FWUerXNLuEuO', NULL, '2026-03-26 05:17:20', '2026-03-26 05:17:20'),
(9, 'Test User 4', 'Jean', 'Assistant maître d\'oeuvre', 'SECAL', 'jean.test@exemple.com', 0, 1, NULL, '$2y$12$9NTDoNMCSNrWUBY/mOic7OIMWtuH9PMGwN4TaDwDC0FsglkmLfjzO', NULL, '2026-03-26 05:34:56', '2026-03-26 05:34:56');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `mot_de_passe_hash` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modification` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `derniere_connexion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe_hash`, `actif`, `date_creation`, `date_modification`, `derniere_connexion`) VALUES
(1, 'Gruson', 'Thibault', 'thibault@example.local', 'temporaire', 1, '2026-03-19 07:33:20', '2026-03-19 07:33:20', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_roles`
--

CREATE TABLE `utilisateur_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` smallint(5) UNSIGNED NOT NULL,
  `date_attribution` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur_roles`
--

INSERT INTO `utilisateur_roles` (`id`, `utilisateur_id`, `role_id`, `date_attribution`) VALUES
(2, 5, 2, '2026-03-26 03:15:19'),
(3, 6, 5, '2026-03-26 05:09:51'),
(4, 7, 3, '2026-03-26 05:11:32'),
(5, 8, 4, '2026-03-26 05:17:20'),
(6, 9, 3, '2026-03-26 05:34:56'),
(7, 3, 1, '2026-03-26 06:17:24');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_services`
--

CREATE TABLE `utilisateur_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `service_id` smallint(5) UNSIGNED NOT NULL,
  `date_attribution` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_documents_projet` (`projet_id`),
  ADD KEY `idx_documents_etape` (`projet_etape_id`),
  ADD KEY `idx_documents_type` (`type_document_id`),
  ADD KEY `idx_documents_statut` (`statut_document`),
  ADD KEY `idx_documents_projet_statut` (`projet_id`,`statut_document`),
  ADD KEY `fk_documents_createur` (`cree_par`);

--
-- Index pour la table `document_commentaires`
--
ALTER TABLE `document_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_document_commentaires_document` (`document_id`),
  ADD KEY `idx_document_commentaires_version` (`document_version_id`),
  ADD KEY `idx_document_commentaires_utilisateur` (`utilisateur_id`);

--
-- Index pour la table `document_validations`
--
ALTER TABLE `document_validations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_document_validations_document` (`document_id`),
  ADD KEY `idx_document_validations_version` (`document_version_id`),
  ADD KEY `idx_document_validations_utilisateur` (`utilisateur_id`),
  ADD KEY `idx_document_validations_decision` (`decision`);

--
-- Index pour la table `document_versions`
--
ALTER TABLE `document_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_document_numero_version` (`document_id`,`numero_version`),
  ADD KEY `idx_document_versions_document` (`document_id`),
  ADD KEY `idx_document_versions_courante` (`document_id`,`est_version_courante`),
  ADD KEY `idx_document_versions_hash` (`hash_fichier`),
  ADD KEY `fk_document_versions_utilisateur` (`depose_par`),
  ADD KEY `document_versions_valide_par_foreign` (`valide_par`);

--
-- Index pour la table `document_version_validations`
--
ALTER TABLE `document_version_validations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doc_version_type_validation_unique` (`document_version_id`,`type_validation`),
  ADD KEY `document_version_validations_valide_par_foreign` (`valide_par`);

--
-- Index pour la table `etapes_modele`
--
ALTER TABLE `etapes_modele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_etapes_modele_code` (`code`),
  ADD KEY `idx_etapes_modele_phase_ordre` (`phase`,`ordre_affichage`);

--
-- Index pour la table `etape_commentaires`
--
ALTER TABLE `etape_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etape_commentaires_projet_etape_id_foreign` (`projet_etape_id`),
  ADD KEY `etape_commentaires_user_id_foreign` (`user_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `journal_audit`
--
ALTER TABLE `journal_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_journal_audit_utilisateur` (`utilisateur_id`),
  ADD KEY `idx_journal_audit_projet` (`projet_id`),
  ADD KEY `idx_journal_audit_objet` (`objet_type`,`objet_id`),
  ADD KEY `idx_journal_audit_action` (`action`),
  ADD KEY `idx_journal_audit_date` (`date_action`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_utilisateur_lue` (`utilisateur_id`,`est_lue`),
  ADD KEY `idx_notifications_date` (`date_creation`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_projets_code_projet` (`code_projet`),
  ADD KEY `idx_projets_statut` (`statut_global`),
  ADD KEY `idx_projets_dates` (`date_debut_prevue`,`date_fin_prevue`),
  ADD KEY `fk_projets_createur` (`cree_par`);

--
-- Index pour la table `projet_etapes`
--
ALTER TABLE `projet_etapes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_projet_etape` (`projet_id`,`parent_id`,`ordre_reel`),
  ADD KEY `fk_projet_etapes_modele` (`etape_modele_id`),
  ADD KEY `fk_projet_etapes_validee_par` (`validee_par`),
  ADD KEY `idx_projet_etapes_projet` (`projet_id`),
  ADD KEY `idx_projet_etapes_statut` (`statut`),
  ADD KEY `idx_projet_etapes_projet_statut` (`projet_id`,`statut`),
  ADD KEY `projet_etapes_parent_id_foreign` (`parent_id`);

--
-- Index pour la table `projet_etape_validateurs`
--
ALTER TABLE `projet_etape_validateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_validateurs_etape` (`projet_etape_id`),
  ADD KEY `idx_validateurs_cible` (`type_cible`,`cible_id`);

--
-- Index pour la table `projet_etape_validations`
--
ALTER TABLE `projet_etape_validations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projet_etape_validations_etape` (`projet_etape_id`),
  ADD KEY `idx_projet_etape_validations_utilisateur` (`utilisateur_id`),
  ADD KEY `idx_projet_etape_validations_decision` (`decision`);

--
-- Index pour la table `projet_utilisateurs`
--
ALTER TABLE `projet_utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_projet_utilisateur` (`projet_id`,`utilisateur_id`),
  ADD KEY `idx_projet_utilisateurs_chef` (`projet_id`,`est_chef_projet`),
  ADD KEY `idx_projet_utilisateurs_actif` (`projet_id`,`actif`),
  ADD KEY `fk_projet_utilisateurs_user` (`utilisateur_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_roles_code` (`code`);

--
-- Index pour la table `role_type_document_permissions`
--
ALTER TABLE `role_type_document_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_role_type_document` (`role_id`,`type_document_id`),
  ADD KEY `idx_rtdp_role` (`role_id`),
  ADD KEY `idx_rtdp_type_document` (`type_document_id`);

--
-- Index pour la table `role_validation_permissions`
--
ALTER TABLE `role_validation_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_role_validation_type` (`role_id`,`validation_type`),
  ADD KEY `idx_rvp_role` (`role_id`),
  ADD KEY `idx_rvp_validation_type` (`validation_type`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_services_code` (`code`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `types_documents`
--
ALTER TABLE `types_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_types_documents_code` (`code`),
  ADD KEY `idx_types_documents_domaine` (`domaine`,`actif`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_utilisateurs_email` (`email`);

--
-- Index pour la table `utilisateur_roles`
--
ALTER TABLE `utilisateur_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_utilisateur_role` (`utilisateur_id`,`role_id`),
  ADD KEY `fk_utilisateur_roles_role` (`role_id`);

--
-- Index pour la table `utilisateur_services`
--
ALTER TABLE `utilisateur_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_utilisateur_service` (`utilisateur_id`,`service_id`),
  ADD KEY `fk_utilisateur_services_service` (`service_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `document_commentaires`
--
ALTER TABLE `document_commentaires`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_validations`
--
ALTER TABLE `document_validations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_versions`
--
ALTER TABLE `document_versions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `document_version_validations`
--
ALTER TABLE `document_version_validations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `etapes_modele`
--
ALTER TABLE `etapes_modele`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `etape_commentaires`
--
ALTER TABLE `etape_commentaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `journal_audit`
--
ALTER TABLE `journal_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `projet_etapes`
--
ALTER TABLE `projet_etapes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `projet_etape_validateurs`
--
ALTER TABLE `projet_etape_validateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projet_etape_validations`
--
ALTER TABLE `projet_etape_validations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projet_utilisateurs`
--
ALTER TABLE `projet_utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `role_type_document_permissions`
--
ALTER TABLE `role_type_document_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `role_validation_permissions`
--
ALTER TABLE `role_validation_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `types_documents`
--
ALTER TABLE `types_documents`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateur_roles`
--
ALTER TABLE `utilisateur_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateur_services`
--
ALTER TABLE `utilisateur_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_createur` FOREIGN KEY (`cree_par`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_etape` FOREIGN KEY (`projet_etape_id`) REFERENCES `projet_etapes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_projet` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_type` FOREIGN KEY (`type_document_id`) REFERENCES `types_documents` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_commentaires`
--
ALTER TABLE `document_commentaires`
  ADD CONSTRAINT `fk_document_commentaires_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_commentaires_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_commentaires_version` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_validations`
--
ALTER TABLE `document_validations`
  ADD CONSTRAINT `fk_document_validations_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_validations_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_validations_version` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_versions`
--
ALTER TABLE `document_versions`
  ADD CONSTRAINT `document_versions_valide_par_foreign` FOREIGN KEY (`valide_par`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_document_versions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_versions_utilisateur` FOREIGN KEY (`depose_par`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_version_validations`
--
ALTER TABLE `document_version_validations`
  ADD CONSTRAINT `document_version_validations_document_version_id_foreign` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_version_validations_valide_par_foreign` FOREIGN KEY (`valide_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `etape_commentaires`
--
ALTER TABLE `etape_commentaires`
  ADD CONSTRAINT `etape_commentaires_projet_etape_id_foreign` FOREIGN KEY (`projet_etape_id`) REFERENCES `projet_etapes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `etape_commentaires_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `journal_audit`
--
ALTER TABLE `journal_audit`
  ADD CONSTRAINT `fk_journal_audit_projet` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_journal_audit_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `fk_projets_createur` FOREIGN KEY (`cree_par`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `projet_etapes`
--
ALTER TABLE `projet_etapes`
  ADD CONSTRAINT `fk_projet_etapes_modele` FOREIGN KEY (`etape_modele_id`) REFERENCES `etapes_modele` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projet_etapes_projet` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projet_etapes_validee_par` FOREIGN KEY (`validee_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `projet_etapes_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `projet_etapes` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `projet_etape_validateurs`
--
ALTER TABLE `projet_etape_validateurs`
  ADD CONSTRAINT `fk_projet_etape_validateurs_etape` FOREIGN KEY (`projet_etape_id`) REFERENCES `projet_etapes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `projet_etape_validations`
--
ALTER TABLE `projet_etape_validations`
  ADD CONSTRAINT `fk_projet_etape_validations_etape` FOREIGN KEY (`projet_etape_id`) REFERENCES `projet_etapes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projet_etape_validations_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `projet_utilisateurs`
--
ALTER TABLE `projet_utilisateurs`
  ADD CONSTRAINT `fk_projet_utilisateurs_projet` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_projet_utilisateurs_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `role_type_document_permissions`
--
ALTER TABLE `role_type_document_permissions`
  ADD CONSTRAINT `fk_rtdp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rtdp_type_document` FOREIGN KEY (`type_document_id`) REFERENCES `types_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `role_validation_permissions`
--
ALTER TABLE `role_validation_permissions`
  ADD CONSTRAINT `fk_rvp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_roles`
--
ALTER TABLE `utilisateur_roles`
  ADD CONSTRAINT `fk_utilisateur_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_roles_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_services`
--
ALTER TABLE `utilisateur_services`
  ADD CONSTRAINT `fk_utilisateur_services_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_services_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
