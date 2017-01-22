-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 22 Janvier 2017 à 14:11
-- Version du serveur :  10.1.19-MariaDB
-- Version de PHP :  5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bismilah`
--

-- --------------------------------------------------------

--
-- Structure de la table `connectlog`
--

CREATE TABLE `connectlog` (
  `id` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('RB', 'Remboursée'),
('VA', 'Validée et mise en paiement');

-- --------------------------------------------------------

--
-- Structure de la table `etatfraisforfait`
--

CREATE TABLE `etatfraisforfait` (
  `idUtilisateur` int(11) NOT NULL,
  `mois` char(6) NOT NULL,
  `idEtat` char(2) NOT NULL,
  `dateModif` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `etatfraisforfait`
--

INSERT INTO `etatfraisforfait` (`idUtilisateur`, `mois`, `idEtat`, `dateModif`) VALUES
(2, '201701', 'CR', '2017-01-22');

-- --------------------------------------------------------

--
-- Structure de la table `fichefrais`
--

CREATE TABLE `fichefrais` (
  `idUtilisateur` int(11) NOT NULL,
  `mois` char(6) NOT NULL,
  `nbJustificatifs` int(11) DEFAULT NULL,
  `montantValide` decimal(10,2) DEFAULT NULL,
  `dateModif` date DEFAULT NULL,
  `idEtat` char(2) DEFAULT 'CR',
  `idValideur` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `fichefrais`
--

INSERT INTO `fichefrais` (`idUtilisateur`, `mois`, `nbJustificatifs`, `montantValide`, `dateModif`, `idEtat`, `idValideur`) VALUES
(2, '201701', 0, '0.00', '2017-01-22', 'CR', '');

-- --------------------------------------------------------

--
-- Structure de la table `fraisforfait`
--

CREATE TABLE `fraisforfait` (
  `id` char(3) NOT NULL,
  `libelle` char(20) DEFAULT NULL,
  `montant` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `fraisforfait`
--

INSERT INTO `fraisforfait` (`id`, `libelle`, `montant`) VALUES
('ETP', 'Forfait Etape', '110.00'),
('KM', 'Frais Kilométrique', '0.62'),
('NUI', 'Nuitée Hôtel', '80.00'),
('REP', 'Repas Restaurant', '25.00');

-- --------------------------------------------------------

--
-- Structure de la table `lignefraisforfait`
--

CREATE TABLE `lignefraisforfait` (
  `idUtilisateur` int(11) NOT NULL,
  `mois` char(6) NOT NULL,
  `idFraisForfait` char(3) NOT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `lignefraisforfait`
--

INSERT INTO `lignefraisforfait` (`idUtilisateur`, `mois`, `idFraisForfait`, `quantite`) VALUES
(2, '201701', 'ETP', 0),
(2, '201701', 'KM', 0),
(2, '201701', 'NUI', 0),
(2, '201701', 'REP', 0);

-- --------------------------------------------------------

--
-- Structure de la table `lignefraishorsforfait`
--

CREATE TABLE `lignefraishorsforfait` (
  `id` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `mois` char(6) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `dateModif` date NOT NULL,
  `idEtat` char(2) DEFAULT 'CR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `lignefraishorsforfait`
--

INSERT INTO `lignefraishorsforfait` (`id`, `idUtilisateur`, `mois`, `libelle`, `date`, `montant`, `dateModif`, `idEtat`) VALUES
(1, 2, '201701', 'SNCF²', '2017-01-01', '222.00', '2017-01-22', 'CR');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id`, `nom`, `libelle`) VALUES
(1, 'ROLE_USER', 'visiteur'),
(2, 'ROLE_ADMIN', 'administrateur'),
(3, 'ROLE_SUPER_ADMIN', 'Super admin'),
(4, 'ROLE_COMPTABLE', 'comptable');

-- --------------------------------------------------------

--
-- Structure de la table `situation`
--

CREATE TABLE `situation` (
  `id` char(2) NOT NULL,
  `libelle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `situation`
--

INSERT INTO `situation` (`id`, `libelle`) VALUES
('CL', 'Cloturée'),
('CR', 'En saisie'),
('RF', 'Refusé'),
('VA', 'Validée');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `commune_id` int(11) NOT NULL,
  `date_embauche` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `login`, `mdp`, `role_id`, `email`, `token`, `nom`, `prenom`, `adresse`, `commune_id`, `date_embauche`) VALUES
(1, 'bruno', '$2y$10$.2jIJW6KRzGeu/mSIdhO2e4e2KisqKpOpVESyk./Yvk5Qdyleabk6', 3, 'avinint@hotmail.com', '', 'Avinint', 'Bruno', '189 rue du Nord', 32691, '2015-09-01'),
(2, 'shinta', '$2a$07$NTTuTeA0gbJ7UaPEG3qCoe6Ry5Y8YfbnOKALtFmazH6yqYYCktyZ2', 1, 'shinta42@hotmail.fr', '', 'ZEGHOUANI', 'Haitem', '8 rue du 11 Décembre', 32252, '2015-09-01'),
(3, 'tom', '$2y$10$NKxmmdCxqs03JfI3a3oZ2u6/ULGBlUqg6e0V5Dh3dhr.QiGWbwASu', 3, 'thomas.duport1@gmail.com', '', 'Duport', 'Thomas', 'les Sauvages', 32532, '2015-09-08');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `connectlog`
--
ALTER TABLE `connectlog`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etatfraisforfait`
--
ALTER TABLE `etatfraisforfait`
  ADD PRIMARY KEY (`idUtilisateur`,`mois`);

--
-- Index pour la table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD PRIMARY KEY (`idUtilisateur`,`mois`),
  ADD KEY `idEtat` (`idEtat`);

--
-- Index pour la table `fraisforfait`
--
ALTER TABLE `fraisforfait`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lignefraisforfait`
--
ALTER TABLE `lignefraisforfait`
  ADD PRIMARY KEY (`idUtilisateur`,`mois`,`idFraisForfait`),
  ADD KEY `idFraisForfait` (`idFraisForfait`);

--
-- Index pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUtilisateur` (`idUtilisateur`,`mois`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `situation`
--
ALTER TABLE `situation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uti_role` (`role_id`),
  ADD KEY `uti_pays` (`commune_id`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `connectlog`
--
ALTER TABLE `connectlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD CONSTRAINT `ficheFrais_ibfk_1` FOREIGN KEY (`idEtat`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `ficheFrais_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `lignefraisforfait`
--
ALTER TABLE `lignefraisforfait`
  ADD CONSTRAINT `ligneFraisForfait_ibfk_1` FOREIGN KEY (`idUtilisateur`,`mois`) REFERENCES `fichefrais` (`idUtilisateur`, `mois`),
  ADD CONSTRAINT `ligneFraisForfait_ibfk_2` FOREIGN KEY (`idFraisForfait`) REFERENCES `fraisforfait` (`id`);

--
-- Contraintes pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD CONSTRAINT `ligneFraisHorsForfait_ibfk_1` FOREIGN KEY (`idUtilisateur`,`mois`) REFERENCES `fichefrais` (`idUtilisateur`, `mois`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
