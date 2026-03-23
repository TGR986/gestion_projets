-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : mariadb_gp:3306
-- Généré le : lun. 23 mars 2026 à 22:12
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
  `cree_par` int(10) UNSIGNED NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modification` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `depose_par` int(10) UNSIGNED NOT NULL,
  `commentaire_version` text DEFAULT NULL,
  `est_version_courante` tinyint(1) NOT NULL DEFAULT 1,
  `date_depot` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(5, '2026_03_20_071451_add_parent_id_to_projet_etapes_table', 3);

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
(2, '2026_pro_1', 'Archives Wallis', 'Construction d\'un bâtiment d\'archives sur l\'île de Wallis', 'construction', 'en_cours', NULL, '2026-03-23', '2028-05-30', NULL, 1, '2026-03-19 20:23:28', '2026-03-22 21:56:07'),
(4, '2026_pro_AT', 'Assemblée territoriale', 'Destruction puis reconstruction du bâtiment administratif de l\'Assemblée territoriale sur l\'île de Wallis', 'démolition et construction', 'en_preparation', NULL, '2026-04-27', '2028-07-31', NULL, 1, '2026-03-23 03:36:19', '2026-03-23 03:37:09');

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
  `date_ouverture` datetime DEFAULT NULL,
  `date_cloture` datetime DEFAULT NULL,
  `validee_par` int(10) UNSIGNED DEFAULT NULL,
  `commentaire_validation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projet_etapes`
--

INSERT INTO `projet_etapes` (`id`, `projet_id`, `parent_id`, `etape_modele_id`, `titre_personnalise`, `ordre_reel`, `statut`, `date_ouverture`, `date_cloture`, `validee_par`, `commentaire_validation`) VALUES
(6, 2, NULL, 1, NULL, 1, 'validee', '2026-03-23 03:32:18', NULL, NULL, NULL),
(7, 2, NULL, 2, NULL, 2, 'en_cours', '2026-03-23 03:32:32', NULL, NULL, NULL),
(14, 2, 6, 6, NULL, 1, 'a_faire', NULL, NULL, NULL, NULL),
(15, 2, NULL, 3, NULL, 3, 'a_faire', NULL, NULL, NULL, NULL);

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
(4, 2, 3, 'Chargée de mission gestion et performance', 0, 1, '2026-03-23 03:57:49'),
(5, 2, 4, 'Conducteur d\'opération Ingénierie Publique', 0, 1, '2026-03-23 04:00:02');

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
(3, 'validateur_technique', 'Validateur technique', 'Validation des éléments techniques'),
(4, 'validateur_financier', 'Validateur financier', 'Validation des éléments financiers'),
(5, 'lecteur', 'Lecteur', 'Consultation uniquement');

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
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Thibault', 'thibault.gruson@adsup.wf', 1, NULL, '$2y$12$YFXGyBEUneA11HvFLm1L1eatYblVsA2nt0fiL3UDw6l0TaAWkJKNe', NULL, '2026-03-19 06:45:05', '2026-03-19 06:45:05'),
(2, 'deuxieme_utilisateur', 'deuxieme_utilisateur@test.com', 0, NULL, '$2y$12$2IJ3ufjTNvljqar2e92aOu8WWjkl2VIZLtIpAlzPjkuW2HGI2B/Iq', NULL, '2026-03-19 21:42:53', '2026-03-19 21:42:53'),
(3, 'Audrey', 'audrey.battistel@wallis-et-futuna.pref.gouv.fr', 1, NULL, '$2y$12$aoFQwTdzttbKmpa7K8sLz.2XgucrDRETLqWx.Tn/MePaR72NA6H4.', NULL, '2026-03-23 03:46:34', '2026-03-23 03:46:34'),
(4, 'Vincent', 'vincent.pegoraro@wallis-et-futuna.pref.gouv.fr', 1, NULL, '$2y$12$ShSJ.91mtKKmHzWBGNzycel1wDXnfcDSwWNJ2CHPE9no2Y3I5kUjK', 'OJRaaDgrnDtZL28nLyGm9nEsot0BDGGRWTDahCX1KOVCIuzTWIhlHe9WgkfZ', '2026-03-23 03:52:00', '2026-03-23 03:52:00');

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
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `role_id` smallint(5) UNSIGNED NOT NULL,
  `date_attribution` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD KEY `fk_documents_createur` (`cree_par`),
  ADD KEY `idx_documents_projet` (`projet_id`),
  ADD KEY `idx_documents_etape` (`projet_etape_id`),
  ADD KEY `idx_documents_type` (`type_document_id`),
  ADD KEY `idx_documents_statut` (`statut_document`),
  ADD KEY `idx_documents_projet_statut` (`projet_id`,`statut_document`);

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
  ADD KEY `fk_document_versions_utilisateur` (`depose_par`),
  ADD KEY `idx_document_versions_document` (`document_id`),
  ADD KEY `idx_document_versions_courante` (`document_id`,`est_version_courante`),
  ADD KEY `idx_document_versions_hash` (`hash_fichier`);

--
-- Index pour la table `etapes_modele`
--
ALTER TABLE `etapes_modele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_etapes_modele_code` (`code`),
  ADD KEY `idx_etapes_modele_phase_ordre` (`phase`,`ordre_affichage`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etapes_modele`
--
ALTER TABLE `etapes_modele`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `projet_etapes`
--
ALTER TABLE `projet_etapes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateur_roles`
--
ALTER TABLE `utilisateur_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_documents_createur` FOREIGN KEY (`cree_par`) REFERENCES `utilisateurs` (`id`) ON UPDATE CASCADE,
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
  ADD CONSTRAINT `fk_document_versions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_versions_utilisateur` FOREIGN KEY (`depose_par`) REFERENCES `utilisateurs` (`id`) ON UPDATE CASCADE;

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
-- Contraintes pour la table `utilisateur_roles`
--
ALTER TABLE `utilisateur_roles`
  ADD CONSTRAINT `fk_utilisateur_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_roles_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
