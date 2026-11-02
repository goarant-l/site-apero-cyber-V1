CREATE TABLE IF NOT EXISTS `client` (
  `idClient` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `client`
  ADD UNIQUE KEY `pseudo` (`pseudo`);


CREATE TABLE IF NOT EXISTS `users` (
  `codeUser` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(15) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `role` varchar(3) NOT NULL DEFAULT 'usr',
  PRIMARY KEY (`codeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `users`
  ADD UNIQUE KEY `login` (`login`);