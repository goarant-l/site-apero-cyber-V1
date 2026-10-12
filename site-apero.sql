-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 30 avr. 2026 à 15:18
-- Version du serveur : 11.4.2-MariaDB
-- Version de PHP : 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site-apero`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `idAdresse` int(11) NOT NULL,
  `rue` varchar(32) NOT NULL,
  `codePostal` smallint(6) NOT NULL,
  `ville` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `idCategorie` int(11) NOT NULL,
  `libelleCategorie` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `libelleCategorie`) VALUES
(1, 'Bières'),
(2, 'Fromages'),
(3, 'Charcuterie');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClient` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`idClient`, `nom`, `prenom`, `pseudo`, `mdp`, `mail`, `telephone`) VALUES
(1, 'goarant', 'lorene', 'lolo', '123456', NULL, NULL),
(2, 'piron', 'alexandre', 'biberon', '123456', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `noCommande` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  `idStatut` char(3) NOT NULL,
  `dateCommande` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`noCommande`, `idClient`, `idStatut`, `dateCommande`) VALUES
(4, 1, 'ENC', '2026-04-30');

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

CREATE TABLE `contenir` (
  `noCommande` int(11) NOT NULL,
  `numProduit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contenir`
--

INSERT INTO `contenir` (`noCommande`, `numProduit`, `quantite`) VALUES
(4, 2, 3),
(4, 6, 2),
(4, 9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `habiter`
--

CREATE TABLE `habiter` (
  `idAdresse` int(11) NOT NULL,
  `idClient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `numProduit` int(11) NOT NULL,
  `idCategorie` int(11) NOT NULL,
  `nomProduit` varchar(32) NOT NULL,
  `descriptif` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `prixUnitaire` decimal(8,2) DEFAULT NULL,
  `nomImage` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`numProduit`, `idCategorie`, `nomProduit`, `descriptif`, `stock`, `prixUnitaire`, `nomImage`) VALUES
(1, 1, 'Bière blonde', 'Bière au goût malté, caramel, légèrement toasté, avec une légère amertume. Prix (33cl bouteille).', 28, 5.00, 'biere_blonde.jpg'),
(2, 1, 'Bière aux fruits rouges', 'Bière artisanale avec ajout de fruit rouges, goût sucré, peu amer.\r\nPrix (33cl bouteille).', 12, 4.50, 'biere-rousse.png'),
(3, 1, 'Bière brune', 'Bière foncée aux arôme ronde et parfois corsé. Prix (33cl bouteille).', 35, 7.00, 'biere_brune.jpg'),
(4, 2, 'Tomme de Bretagne', 'Fromage rustique à pâte pressée, goût lacté et parfois noisette.\r\nPrix au kg.', 54, 30.00, 'tomme.png'),
(5, 2, 'Bleue de Bellevue', 'Fromage breton à pâte persillée, doux pour un bleu, crémeux léger.\r\nPrix au kg.', 32, 25.00, 'bleu-fromage.png'),
(6, 2, 'Gouda', 'Fromage néerlandais doux jeune, plus noisette et intense en vieillissant. Prix au kg.', 21, 23.00, 'gouda.png'),
(7, 3, 'Saucissons aux noisettes', 'Saucisson sec avec morceaux de noisettes, goût salé, croquant, doux. Prix à la pièce.', 38, 13.00, 'saucisson-poivre.png'),
(8, 3, 'Jambon fumé', 'Jambon salé puis fumé, goût boisé, salé et doux, très polyvalent. Prix au kg.', 19, 25.00, 'jambon_fume.jpg'),
(9, 3, 'Chorizo', 'Saucisson au paprika, goût fumé et épicé. Prix à la pièce.', 15, 6.00, 'chorizo-doux.png'),
(10, 1, 'Bière ambrée', 'Bière au goût malté, légèrement toasté avec parfois une légère amertume. Prix (33cl bouteille).', 36, 12.00, 'biere_ambree.png'),
(11, 2, 'Fromage  brie AOP', 'Fromage à pâte molle, très crémeux et doux, devient plus fort en affinant. Prix au kg.', 16, 29.00, 'brie.png'),
(12, 3, 'Coppa', 'Charcuterie d\'échine de porc, goût fin, poivré et fondant. Prix au kg.', 86, 35.00, 'coppa.png');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `idStatut` char(3) NOT NULL,
  `libelleStatut` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`idStatut`, `libelleStatut`) VALUES
('ANN', 'Annulée'),
('ENC', 'En cours'),
('ENV', 'Envoyée'),
('PAY', 'Payée'),
('VAL', 'Validée');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `codeUser` int(11) NOT NULL,
  `login` varchar(15) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `role` varchar(3) NOT NULL DEFAULT 'usr'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`codeUser`, `login`, `mdp`, `nom`, `prenom`, `role`) VALUES
(1, 'leroy-a', '123456', 'Leroy', 'Achille', 'adm'),
(2, 'denis-e', '654321', 'Denis', 'Emma', 'usr'),
(3, 'olsen-g', 'azerty', 'Olsen', 'Gabriel', 'usr');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`idAdresse`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`noCommande`),
  ADD KEY `FK_ETRE` (`idStatut`),
  ADD KEY `FK_PASSER` (`idClient`);

--
-- Index pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD PRIMARY KEY (`noCommande`,`numProduit`),
  ADD KEY `FK_CONTENIR` (`numProduit`);

--
-- Index pour la table `habiter`
--
ALTER TABLE `habiter`
  ADD PRIMARY KEY (`idAdresse`,`idClient`),
  ADD KEY `FK_HABITER` (`idClient`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`numProduit`),
  ADD KEY `FK_APPARTENIR` (`idCategorie`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`idStatut`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`codeUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `idAdresse` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `idClient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `noCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `numProduit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `codeUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_ETRE` FOREIGN KEY (`idStatut`) REFERENCES `statut` (`idStatut`),
  ADD CONSTRAINT `FK_PASSER` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`);

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `FK_CONTENIR` FOREIGN KEY (`numProduit`) REFERENCES `produit` (`numProduit`),
  ADD CONSTRAINT `FK_CONTENIR2` FOREIGN KEY (`noCommande`) REFERENCES `commande` (`noCommande`);

--
-- Contraintes pour la table `habiter`
--
ALTER TABLE `habiter`
  ADD CONSTRAINT `FK_HABITER` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`),
  ADD CONSTRAINT `FK_HABITER2` FOREIGN KEY (`idAdresse`) REFERENCES `adresse` (`idAdresse`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_APPARTENIR` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`idCategorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
