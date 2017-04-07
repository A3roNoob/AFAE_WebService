-- phpMyAdmin SQL Dump
-- version 4.6.5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Jeu 06 Avril 2017 à 15:39
-- Version du serveur :  5.5.53-MariaDB
-- Version de PHP :  5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `afae`
--

-- --------------------------------------------------------

--
-- Structure de la table `association`
--

CREATE TABLE `association` (
  `idassociation` int(11) NOT NULL,
  `nomassociation` varchar(255) NOT NULL,
  `sigleassociation` varchar(25) DEFAULT NULL,
  `villeassociation` varchar(64) NOT NULL,
  `idadministrateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `association`
--

INSERT INTO `association` (`idassociation`, `nomassociation`, `sigleassociation`, `villeassociation`, `idadministrateur`) VALUES
(1, 'Association des Familles d\'Arpajon et de ses Environs', 'AFAE', 'Arpajon', 2);

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

CREATE TABLE `banque` (
  `idbanque` int(11) NOT NULL,
  `nombanque` varchar(50) DEFAULT NULL,
  `codebanque` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `banque`
--

INSERT INTO `banque` (`idbanque`, `nombanque`, `codebanque`) VALUES
(1, 'BNP', 'BNP'),
(2, 'Banque populaire', 'BP'),
(3, 'BRED', 'BRED'),
(4, 'Crédit Agricole', 'CA'),
(5, 'CCF', 'CCF'),
(6, 'Caisse des Dépots', 'CD'),
(7, 'Caisse Epargne', 'CE'),
(8, 'Caixa Geral de Depositos', 'CGD'),
(9, 'CIC', 'CIC'),
(10, 'Crédit Industriel de l\'Ouest', 'CIO'),
(11, 'Crédit Lyonnais', 'CL'),
(12, 'Crédit Mutuel', 'CM'),
(13, 'Crédit du Nord', 'CN'),
(14, 'Divers', 'D'),
(15, 'HSBC', 'HSBC'),
(16, 'La Poste', 'LP'),
(17, 'Paribas', 'P'),
(18, 'Groupe Banque Populaire', 'SBE'),
(19, 'Société Générale', 'SG'),
(20, 'Trésors Public', 'TP'),
(21, 'Union des Banques de Paris', 'UBP');

-- --------------------------------------------------------

--
-- Structure de la table `foire`
--

CREATE TABLE `foire` (
  `idfoire` int(11) NOT NULL,
  `nomfoire` varchar(255) NOT NULL,
  `idassociation` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `datedebut` date DEFAULT NULL,
  `datefin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `foire`
--

INSERT INTO `foire` (`idfoire`, `nomfoire`, `idassociation`, `idadmin`, `datedebut`, `datefin`) VALUES
(1, 'Foire d\'automne', 1, 2, '2017-09-20', '2017-09-25');

-- --------------------------------------------------------

--
-- Structure de la table `objet`
--

CREATE TABLE `objet` (
  `idobjet` int(11) NOT NULL,
  `numitem` int(11) NOT NULL DEFAULT '0',
  `idfoire` int(11) NOT NULL DEFAULT '0',
  `idutilisateur` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `baisse` tinyint(1) NOT NULL DEFAULT '0',
  `prix` decimal(5,2) NOT NULL,
  `vendu` tinyint(1) NOT NULL DEFAULT '0',
  `taille` varchar(32) DEFAULT NULL,
  `nbitem` int(11) NOT NULL,
  `verrou` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `idpaiement` int(11) NOT NULL,
  `nompaiement` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `paiement`
--

INSERT INTO `paiement` (`idpaiement`, `nompaiement`) VALUES
(1, 'Liquide'),
(2, 'Chèque');

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `idfoire` int(11) NOT NULL DEFAULT '0',
  `idutilisateur` int(11) NOT NULL DEFAULT '0',
  `valide` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `participant`
--

INSERT INTO `participant` (`idfoire`, `idutilisateur`, `valide`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rang`
--

CREATE TABLE `rang` (
  `idrang` int(11) NOT NULL,
  `nomrang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `rang`
--

INSERT INTO `rang` (`idrang`, `nomrang`) VALUES
(1, 'Vendeur'),
(2, 'Opérateur'),
(3, 'Administrateur de foire'),
(4, 'Super Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `idtransaction` int(11) NOT NULL,
  `idfoire` int(11) NOT NULL,
  `idutilisateur` int(11) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `idpaiement` int(11) DEFAULT NULL,
  `idbanque` int(11) DEFAULT NULL,
  `datetransaction` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idutilisateur` int(11) NOT NULL,
  `nomutilisateur` varchar(64) NOT NULL,
  `prenomutilisateur` varchar(64) DEFAULT NULL,
  `adresse` varchar(255) NOT NULL,
  `codepostal` decimal(5,0) NOT NULL,
  `ville` varchar(64) NOT NULL,
  `telephone` decimal(10,0) DEFAULT NULL,
  `baisse` tinyint(1) NOT NULL,
  `changelock` tinyint(1) DEFAULT NULL,
  `login` varchar(64) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idutilisateur`, `nomutilisateur`, `prenomutilisateur`, `adresse`, `codepostal`, `ville`, `telephone`, `baisse`, `changelock`, `login`, `motdepasse`, `email`, `rang`) VALUES
(1, 'SUPER', 'ADMIN', '127.0.0.1', '404', 'Internet', '8', 1, 0, 'superadmin', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0', 'superadmin@localhost', 4),
(2, 'AFAE', NULL, '16 Boulevard AbelCornaton', '91290', 'Arpajon', NULL, 0, 0, 'afae', 'b272dbd564ebd3898c2ecd69fcd94b67', 'afae@free.fr', 3);

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `idvente` int(11) NOT NULL,
  `idtransaction` int(11) NOT NULL,
  `idvendeur` int(11) NOT NULL,
  `idobjet` int(11) NOT NULL,
  `prixvente` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `association`
--
ALTER TABLE `association`
  ADD PRIMARY KEY (`idassociation`),
  ADD KEY `fk_association` (`idadministrateur`);

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`idbanque`);

--
-- Index pour la table `foire`
--
ALTER TABLE `foire`
  ADD PRIMARY KEY (`idfoire`),
  ADD KEY `fk_foire` (`idassociation`),
  ADD KEY `fk_foire_admin` (`idadmin`);

--
-- Index pour la table `objet`
--
ALTER TABLE `objet`
  ADD PRIMARY KEY (`numitem`,`idutilisateur`,`idfoire`),
  ADD UNIQUE KEY `idobjet` (`idobjet`),
  ADD KEY `fk_objet` (`idutilisateur`),
  ADD KEY `fk_objFoire` (`idfoire`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`idpaiement`);

--
-- Index pour la table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`idfoire`,`idutilisateur`),
  ADD KEY `fk_part_user` (`idutilisateur`);

--
-- Index pour la table `rang`
--
ALTER TABLE `rang`
  ADD PRIMARY KEY (`idrang`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`idtransaction`),
  ADD KEY `fk_transaction_foire` (`idfoire`),
  ADD KEY `fk_transaction_utilisateur` (`idutilisateur`),
  ADD KEY `fk_transaction_type_paiement` (`idpaiement`),
  ADD KEY `fk_transaction_banque` (`idbanque`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idutilisateur`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_utilisateur` (`rang`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`idvente`),
  ADD KEY `fk_vente_transaction` (`idtransaction`),
  ADD KEY `fk_vente_vendeur` (`idvendeur`),
  ADD KEY `fk_vente_objet` (`idobjet`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `association`
--
ALTER TABLE `association`
  MODIFY `idassociation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `idbanque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `objet`
--
ALTER TABLE `objet`
  MODIFY `idobjet` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `idpaiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `rang`
--
ALTER TABLE `rang`
  MODIFY `idrang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idtransaction` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idutilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `idvente` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `association`
--
ALTER TABLE `association`
  ADD CONSTRAINT `fk_association` FOREIGN KEY (`idadministrateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `foire`
--
ALTER TABLE `foire`
  ADD CONSTRAINT `fk_foire` FOREIGN KEY (`idassociation`) REFERENCES `association` (`idassociation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_foire_admin` FOREIGN KEY (`idadmin`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `objet`
--
ALTER TABLE `objet`
  ADD CONSTRAINT `fk_objet` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_objFoire` FOREIGN KEY (`idfoire`) REFERENCES `foire` (`idfoire`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `fk_part_user` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_part_foire` FOREIGN KEY (`idfoire`) REFERENCES `foire` (`idfoire`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_foire` FOREIGN KEY (`idfoire`) REFERENCES `foire` (`idfoire`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_utilisateur` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_transaction_type_paiement` FOREIGN KEY (`idpaiement`) REFERENCES `paiement` (`idpaiement`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_banque` FOREIGN KEY (`idbanque`) REFERENCES `banque` (`idbanque`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`rang`) REFERENCES `rang` (`idrang`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `fk_vente_transaction` FOREIGN KEY (`idtransaction`) REFERENCES `transaction` (`idtransaction`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vente_vendeur` FOREIGN KEY (`idvendeur`) REFERENCES `objet` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vente_objet` FOREIGN KEY (`idobjet`) REFERENCES `objet` (`idobjet`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
