-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : jeu. 18 déc. 2025 à 15:20
-- Version du serveur : 8.4.6
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_sanctions`
--

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id_classe` int NOT NULL,
  `nom` varchar(30) NOT NULL,
  `niveau` varchar(15) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id_classe`, `nom`, `niveau`, `date_creation`) VALUES
(2, 'BTS SIO', 'BTS', '2025-12-11 15:19:43'),
(3, 'Prem STI2D', 'Premiere', '2025-12-11 15:19:43'),
(4, 'Term general', 'Terminale', '2025-12-11 15:19:43'),
(5, 'Term', 'Terminale', '2025-12-17 09:17:47');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

CREATE TABLE `eleves` (
  `id` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `id_classe` int NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`id`, `nom`, `prenom`, `date_naissance`, `id_classe`, `date_creation`) VALUES
(2, 'Mettetal', 'Ethann', '2006-10-11', 2, '2025-12-17 09:25:41'),
(3, 'Markiewicz', 'Benjamin', '2004-05-05', 2, '2025-12-17 09:26:41'),
(4, 'Amand', 'Alexandre', '2003-09-30', 2, '2025-12-17 09:27:29'),
(5, 'Patapim', 'Brr Brr', '2010-07-06', 3, '2025-12-17 09:32:41'),
(6, 'Acchini', 'Alessandro', '2006-10-27', 2, '2025-12-17 10:15:20');

-- --------------------------------------------------------

--
-- Structure de la table `professeurs`
--

CREATE TABLE `professeurs` (
  `id` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `matiere` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `professeurs`
--

INSERT INTO `professeurs` (`id`, `nom`, `prenom`, `matiere`) VALUES
(1, 'Lamy', 'Franck', 'Informatique'),
(2, 'David', 'Dominique', 'Réseau');

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

CREATE TABLE `sanctions` (
  `id` int NOT NULL,
  `id_professeur` int NOT NULL,
  `id_eleve` int NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `motif` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sanctions`
--

INSERT INTO `sanctions` (`id`, `id_professeur`, `id_eleve`, `type`, `date`, `motif`) VALUES
(1, 1, 6, 'Exclusion Parking', '2025-05-30 00:00:00', 'Roule trop vite sur le parking de l\'établissement'),
(2, 2, 3, 'Avertissement', '2025-12-17 00:00:00', 'Pt routeur'),
(3, 2, 4, 'Exclusion Temporaire', '2025-12-17 00:00:00', 'FSFUHQSF IQSF IUQFS HGQGS FQGI FSQF IUGQS FIUQGUISF IUGQSF GIUQFG IUQIUGSF IGUQSF GIUQSFIUG QIGUSF GIUFQ'),
(4, 2, 6, 'Retenue', '2025-12-18 00:00:00', 'Y\'a vrmt besoin de justifier?');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `email`, `name`, `surname`, `password_hash`) VALUES
(1, 'test@test.test', 'TestName', 'TestSurname', '$2y$12$o5Rrskt1gu7j7gMXapgCleiai2uyEMwB6uTu64fOz0ztv30HC1SU2'),
(3, 'mettetalethann@gmail.com', 'Mettetal', 'Ethann', '$2y$12$KH4MuhIZP1nktcj4/1Y8y.KVKfTfkUMuXSomkZ/wYwhqeId3vQvM2'),
(16, 'ethann@gmail.com', 'EEE', 'EEE', '$2y$12$OS1lI8GOJT5qFDFgyanRu.bLcRjiGNrjLO9Vpugmk6i48se3ijHmi'),
(17, 'eeee@eeee.com', 'eee', 'eeee', '$2y$12$gl26.Y.cDpLFjWyYYFD6ieYM0Xt5uYeg.ji2l0KE96/ykhwYOXX2u'),
(18, 'benji.marki70@gmail.com', 'Markiewicz', 'Benjamin', '$2y$12$ppNX.Qkj7UuA0WgnXtBwpun9vBPWmGLZeY.HR.udcA02DBP72qgD2');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id_classe`);

--
-- Index pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ELEVES_CLASSES` (`id_classe`);

--
-- Index pour la table `professeurs`
--
ALTER TABLE `professeurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_SANCTION_PROFESSEUR` (`id_professeur`),
  ADD KEY `FK_SANCTION_ELEVE` (`id_eleve`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id_classe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `eleves`
--
ALTER TABLE `eleves`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `professeurs`
--
ALTER TABLE `professeurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `FK_ELEVES_CLASSES` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD CONSTRAINT `sanctions_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanctions_ibfk_2` FOREIGN KEY (`id_professeur`) REFERENCES `professeurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
