-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 29 mai 2025 à 14:17
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_file_attente_2`
--

-- --------------------------------------------------------

--
-- Structure de la table `consultation`
--

CREATE TABLE `consultation` (
  `id_consultation` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `id_medecin` int(11) NOT NULL,
  `date_consultation` date DEFAULT curdate(),
  `heure_arrivee` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('en attente','en consultation','terminé','annulé') DEFAULT 'en attente',
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `consultation`
--

INSERT INTO `consultation` (`id_consultation`, `id_patient`, `id_medecin`, `date_consultation`, `heure_arrivee`, `statut`, `commentaire`) VALUES
(8, 6, 9, '2025-05-26', '2025-05-29 15:47:00', 'terminé', 'poids 10kg, mal de ventre'),
(9, 3, 3, '2025-05-27', '2025-05-29 08:54:00', 'terminé', 'mal de ventre, maux de tête,  famine'),
(10, 7, 3, '2025-05-27', '2025-05-29 08:59:00', 'terminé', 'paludisme'),
(11, 2, 3, '2025-05-27', '2025-05-29 09:02:00', 'terminé', 'angine '),
(12, 6, 3, '2025-05-27', '2025-05-29 09:08:00', 'terminé', 'maux de tête '),
(13, 10, 3, '2025-05-27', '2025-05-29 14:54:00', 'terminé', 'mal de pied'),
(14, 17, 9, '2025-05-27', '2025-05-29 18:57:00', 'terminé', 'mal au pied'),
(17, 2, 3, '2025-05-28', '2025-05-29 17:12:00', 'terminé', 'mal de tete'),
(18, 1, 8, '2025-05-28', '2025-05-29 09:21:00', 'en attente', 'mal de tete'),
(19, 6, 3, '2025-05-28', '2025-05-29 19:22:00', 'en attente', 'mal de tete');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id_patient` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `adresse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id_patient`, `nom`, `prenom`, `date_naissance`, `sexe`, `telephone`, `adresse`) VALUES
(1, 'Bekem', 'Edna', '2014-01-29', 'F', '678432345', 'Yaounde'),
(2, 'teukam', 'wato', '2007-01-10', 'M', '698764510', 'Mbouda'),
(3, 'luciola', 'lucie', '2011-07-15', 'F', '656341320', 'Bafoussam'),
(6, 'Rosy', 'pascal', '1986-12-01', 'F', '+237 699168894', 'Fin goudron maeture, Bafoussam, Cameroun'),
(7, 'bencini', 'nyetam', '2025-05-02', 'M', '693744120', 'DJEUMOUN'),
(9, 'rylane', 'john', '2009-07-12', 'M', '+1 (241) 183-68', 'yaounde'),
(10, 'Melisa', 'joyce', '2016-05-08', 'F', '+237 657403416', 'yaounde1'),
(11, 'tchouapi', 'joseph', '1975-07-04', 'M', '+237 699168894', 'bafousam, eveche'),
(12, 'migel', 'ange', '2016-03-22', 'M', '+1 (704) 182-23', 'bafoussam, maeture'),
(13, 'brodon', 'jony', '2013-03-02', 'M', '+1 (241) 183-68', 'bafoussam'),
(14, 'angelo', 'jony', '2009-02-15', 'M', '+1 (241) 183-68', 'bafoussam'),
(15, 'gladis', 'any', '2005-06-25', 'M', '+237 657403416', 'bafoussam'),
(16, 'Vitae vel rerum est', 'Dolor et labore occa', '1977-04-11', 'F', '+1 (175) 386-73', 'Perferendis officia '),
(17, 'chris', 'anderson', '2009-03-02', 'M', '670201164', 'maeture, bafoussam, cameroun'),
(18, 'wato', 'teukam', '2025-05-28', 'M', '678900980', 'maeture Bafoussam, Cameroun'),
(19, 'tamo', 'tenwo', '2025-05-01', 'M', '123456789', 'malade'),
(20, 'Valdes', 'tamo', '2025-05-01', 'M', '1234567', 'malade');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `nom_service` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id_service`, `nom_service`) VALUES
(1, 'Service Pediatrie'),
(2, 'Service Radiologie'),
(3, 'Service Medecin Generaliste'),
(4, 'Service Ophtamologie'),
(5, 'Service Chirugie'),
(6, 'Service des Systèmes d\'Information'),
(7, 'Service des Soins Infirmiers'),
(8, 'Dermatologue');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('admin','medecin','infirmier') NOT NULL,
  `id_service` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `id_service`) VALUES
(2, 'daryl', 'steve', 'daryl@gmail.com', '$2y$10$m2gi2hm0dpVaDqyjkYV6eOExA0oo2XBGll7OfmfQTD9kcnSF3N/Mm', 'admin', 6),
(3, 'tsinda', 'rihanna', 'tsinda@gmail.com', '$2y$10$52yMzIisvAn2e0zPnSXC8eYXocezOe7hO5hES4EKhtK6S60/J6NQu', 'medecin', 1),
(4, 'kenne', 'ismaella', 'isma@gmail.com', '$2y$10$NdUaEP9iizC6uVSgWw4Lc.7fG9aP3C0F036TuBhbYGp/tkW6y0THC', 'infirmier', 7),
(7, 'chris ', 'anderson', 'chris@gmail.com', '$2y$10$odDsjZUn5g3on8zTbvjFQe3wq6Rzk3p7XsQk4cM6NwhWVkPSj/hPC', 'medecin', 2),
(8, 'saturo', 'gojo', 'saturo@gmail.com', '$2y$10$HzoPDNIwNFL0A9FkshbqsODjyvfXHCTXrMw1gKDE8xvOmzBYaTrpi', 'medecin', 3),
(9, 'senkou', 'ichigami', 'senkou@gmail.com', '$2y$10$YixrGOjhGMnA/a7YwZSZFubB7Gm00ElsT.dSH9Pf5ym.n3SQ2f9mm', 'medecin', 4),
(10, 'sung', 'jinhu', 'sung@gmail.com', '$2y$10$V/3.vZupkrvt4466oy89n.1YrhPLXsHV2i4WbscGvs5iXtdr8g042', 'medecin', 5),
(33, 'richy', 'lion', 'richy@gmail.com', '$2y$10$TcMSfJh5seHPCma5zKhyq.hToqiAtZIiM0ZEoLOR/sTuLtFqu2st6', 'infirmier', 7);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`id_consultation`),
  ADD KEY `id_patient` (`id_patient`),
  ADD KEY `id_medecin` (`id_medecin`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id_patient`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_service` (`id_service`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `id_consultation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id_patient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`id_patient`) REFERENCES `patients` (`id_patient`),
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`id_medecin`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
