-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 24 avr. 2025 à 00:21
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `transcotb`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

DROP TABLE IF EXISTS `affectations`;
CREATE TABLE IF NOT EXISTS `affectations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lignes_id_ligne` int NOT NULL,
  `services_id_service` int NOT NULL,
  `fr` int NOT NULL,
  `series` varchar(50) NOT NULL,
  `num_tickets_donner` int NOT NULL,
  `num_tickets_retour` int NOT NULL,
  `total_tickets` int NOT NULL,
  `tr` int NOT NULL,
  `tv` int NOT NULL,
  `ddate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `services_id_service` (`services_id_service`),
  KEY `lignes_id_ligne` (`lignes_id_ligne`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `affectations`
--

INSERT INTO `affectations` (`id`, `lignes_id_ligne`, `services_id_service`, `fr`, `series`, `num_tickets_donner`, `num_tickets_retour`, `total_tickets`, `tr`, `tv`, `ddate`) VALUES
(1, 1, 1, 123456, 'A12FE4', 9467909, 9468000, 92, 2, 90, '2025-04-22'),
(2, 31, 249, 123456, 'A12FE4', 9467909, 9468999, 1091, 50, 1041, '2025-04-23'),
(3, 31, 219, 123456, 'A12FE4', 9467909, 9468999, 1091, 50, 1041, '2025-04-23');

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

DROP TABLE IF EXISTS `connexion`;
CREATE TABLE IF NOT EXISTS `connexion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(255) NOT NULL,
  `compte_utilisateur` varchar(255) NOT NULL,
  `role_id` varchar(20) DEFAULT NULL,
  `date_connexion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('En ligne','Hors ligne') DEFAULT 'Hors ligne',
  `date_deconnexion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`id`, `matricule`, `compte_utilisateur`, `role_id`, `date_connexion`, `statut`, `date_deconnexion`) VALUES
(1, '100010', 'test1', 'Admin', '2025-04-01 10:38:17', 'Hors ligne', '2025-04-01 12:38:40'),
(2, '100012', 'tata', 'Admin', '2025-04-01 10:39:19', 'Hors ligne', '2025-04-01 12:40:11'),
(3, '100012', 'tata', 'Admin', '2025-04-01 10:39:59', 'Hors ligne', '2025-04-01 12:40:11'),
(4, '100010', 'test1', 'Admin', '2025-04-01 10:40:49', 'Hors ligne', '2025-04-01 12:51:44'),
(5, '100010', 'test1', 'Admin', '2025-04-01 10:51:40', 'Hors ligne', '2025-04-01 12:51:44'),
(6, '100010', 'test1', 'Admin', '2025-04-01 09:26:45', 'Hors ligne', '2025-04-01 11:51:52'),
(7, '100012', 'tata', 'Admin', '2025-04-02 12:05:28', 'Hors ligne', NULL),
(8, '100010', 'test1', 'Admin', '2025-04-03 06:21:11', 'Hors ligne', '2025-04-03 08:33:12'),
(9, '100035', 'gest', 'Rapro', '2025-04-03 06:33:18', 'Hors ligne', '2025-04-11 18:28:17'),
(10, '100010', 'test1', 'Admin', '2025-04-03 06:33:37', 'Hors ligne', '2025-04-03 08:34:15'),
(11, '100010', 'test1', 'Admin', '2025-04-03 06:34:52', 'Hors ligne', '2025-04-03 08:35:22'),
(12, '100035', 'gest', 'GS', '2025-04-03 06:35:28', 'Hors ligne', '2025-04-11 18:28:17'),
(13, '100035', 'gest', 'GS', '2025-04-03 06:36:42', 'Hors ligne', '2025-04-11 18:28:17'),
(14, '100010', 'test1', 'Admin', '2025-04-03 22:42:38', 'Hors ligne', '2025-04-04 03:47:59'),
(15, '100010', 'test1', 'Admin', '2025-04-04 00:22:41', 'Hors ligne', '2025-04-04 03:47:59'),
(16, '100010', 'test1', 'Admin', '2025-04-04 01:50:30', 'Hors ligne', '2025-04-07 08:46:49'),
(17, '100010', 'test1', 'Admin', '2025-04-04 21:22:41', 'Hors ligne', '2025-04-07 08:46:49'),
(18, '100010', 'test1', 'Admin', '2025-04-05 09:34:17', 'Hors ligne', '2025-04-07 08:46:49'),
(19, '100010', 'test1', 'Admin', '2025-04-05 13:12:03', 'Hors ligne', '2025-04-07 08:46:49'),
(20, '100010', 'test1', 'Admin', '2025-04-07 00:03:12', 'Hors ligne', '2025-04-07 08:46:49'),
(21, '100010', 'test1', 'Admin', '2025-04-07 06:43:47', 'Hors ligne', '2025-04-07 08:46:49'),
(22, '100035', 'gest', 'GS', '2025-04-07 06:55:33', 'Hors ligne', '2025-04-11 18:28:17'),
(23, '100010', 'test1', 'Admin', '2025-04-09 07:41:41', 'Hors ligne', '2025-04-18 02:02:01'),
(24, '100035', 'gest', 'GS', '2025-04-09 07:48:14', 'Hors ligne', '2025-04-11 18:28:17'),
(25, '100035', 'gest', 'GS', '2025-04-09 09:00:49', 'Hors ligne', '2025-04-11 18:28:17'),
(26, '100010', 'test1', 'Admin', '2025-04-09 14:31:44', 'Hors ligne', '2025-04-18 02:02:01'),
(27, '100035', 'gest', 'GS', '2025-04-10 00:19:34', 'Hors ligne', '2025-04-11 18:28:17'),
(28, '100035', 'gest', 'GS', '2025-04-09 23:08:21', 'Hors ligne', '2025-04-11 18:28:17'),
(29, '100035', 'gest', 'GS', '2025-04-11 13:01:42', 'Hors ligne', '2025-04-11 18:28:17'),
(30, '100035', 'gest', 'GS', '2025-04-11 13:43:36', 'Hors ligne', '2025-04-11 18:28:17'),
(31, '100010', 'test1', 'Admin', '2025-04-11 16:28:28', 'Hors ligne', '2025-04-18 02:02:01'),
(32, '100010', 'test1', 'Admin', '2025-04-13 09:06:01', 'Hors ligne', '2025-04-18 02:02:01'),
(33, '100035', 'gest', 'GS', '2025-04-13 09:08:55', 'Hors ligne', '2025-04-23 04:47:05'),
(34, '100010', 'test1', 'Admin', '2025-04-17 22:32:39', 'Hors ligne', '2025-04-18 02:02:01'),
(35, '776544', 'manga', 'GS', '2025-04-18 00:02:07', 'Hors ligne', '2025-04-18 02:02:41'),
(36, '100010', 'test1', 'Admin', '2025-04-18 00:02:50', 'Hors ligne', '2025-04-23 04:20:50'),
(37, '100010', 'test1', 'Admin', '2025-04-18 05:14:43', 'Hors ligne', '2025-04-23 04:20:50'),
(38, '100035', 'gest', 'GS', '2025-04-18 12:22:46', 'Hors ligne', '2025-04-23 04:47:05'),
(39, '100035', 'gest', 'GS', '2025-04-23 01:18:15', 'Hors ligne', '2025-04-23 04:47:05'),
(40, '100010', 'test1', 'Admin', '2025-04-22 22:44:27', 'Hors ligne', '2025-04-23 04:20:50'),
(41, '100010', 'test1', 'Admin', '2025-04-22 22:50:07', 'Hors ligne', '2025-04-23 04:20:50'),
(42, '100035', 'gest', 'GS', '2025-04-23 02:21:01', 'Hors ligne', '2025-04-23 04:47:05'),
(43, '100010', 'test1', 'Admin', '2025-04-23 02:47:16', 'Hors ligne', '2025-04-23 22:30:54'),
(44, '100035', 'gest', 'GS', '2025-04-23 03:02:40', 'Hors ligne', '2025-04-23 22:46:16'),
(45, '100035', 'gest', 'GS', '2025-04-23 10:10:54', 'Hors ligne', '2025-04-23 22:46:16'),
(46, '100035', 'gest', 'GS', '2025-04-23 15:38:13', 'Hors ligne', '2025-04-23 22:46:16'),
(47, '100035', 'gest', 'GS', '2025-04-23 20:01:22', 'Hors ligne', '2025-04-23 22:46:16'),
(48, '100010', 'test1', 'Admin', '2025-04-23 20:46:23', 'Hors ligne', '2025-04-23 22:30:54'),
(49, '100035', 'gest', 'GS', '2025-04-23 20:31:04', 'Hors ligne', NULL),
(50, '100010', 'test1', 'Admin', '2025-04-24 00:19:57', 'Hors ligne', NULL),
(51, '100035', 'gest', 'GS', '2025-04-24 00:20:09', 'Hors ligne', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

DROP TABLE IF EXISTS `fonction`;
CREATE TABLE IF NOT EXISTS `fonction` (
  `id` varchar(50) NOT NULL,
  `fonction` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fonction`
--

INSERT INTO `fonction` (`id`, `fonction`) VALUES
('CSI', 'chef de service informatique'),
('CSIA', 'chef de service informatique adjoint ');

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `id` varchar(50) NOT NULL,
  `grade` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `grade`
--

INSERT INTO `grade` (`id`, `grade`) VALUES
('C7', 'Classifier 7'),
('CBSB', 'Chef de Bureau Suivi Billetterie'),
('CC4', 'CC4'),
('CSSB', 'Cheffe de Service Suivi Billetterie');

-- --------------------------------------------------------

--
-- Structure de la table `lignes`
--

DROP TABLE IF EXISTS `lignes`;
CREATE TABLE IF NOT EXISTS `lignes` (
  `id_ligne` int NOT NULL AUTO_INCREMENT,
  `nom_ligne` varchar(50) NOT NULL,
  `code_ligne` varchar(10) NOT NULL,
  PRIMARY KEY (`id_ligne`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `lignes`
--

INSERT INTO `lignes` (`id_ligne`, `nom_ligne`, `code_ligne`) VALUES
(1, 'KINGASANI-COMMERCE', '01'),
(2, 'MASINA-ROYAL', '02'),
(3, 'NGIRINGIRI-GARE CENTRALE', '03'),
(4, 'UPN-GARE CENTRALE', '04'),
(5, 'LEMBA TER-HOTEL DE VILLE', '05'),
(6, 'MOKALI-ZANDO', '06'),
(7, 'PASCAL-FONCTION PUBLIQUE', '07'),
(8, 'KINGASANI-GAMBELA', '08'),
(9, 'UPN-CAMPUS', '09'),
(10, 'N\'DJILI-CAMPUS', '10'),
(11, 'KAPELA-CLINIQUE NGALIEMA', '11'),
(12, 'RPT NGABA-FONCTION PUBLIQUE', '12'),
(13, 'MAMAN YEMO-MBUDI', '13'),
(14, 'MATETE-UPN', '14'),
(15, 'KINKOLE-Q1 MASINA', '15'),
(16, 'VICTOIRE-UPN', '16'),
(17, 'GAMBELA-MBUDI', '17'),
(18, 'KIMBASEKE-ZANDO', '18'),
(19, 'CAMPUS-VICTOIRE', '19'),
(20, 'SELEMBAO-ZANDO', '20'),
(21, 'MITENDI-UPN', '21'),
(22, 'PETRO CONGO-FONCTION PUBLIQUE', '22'),
(23, 'PETRO CONGO-ZANDO', '23'),
(24, 'INTENDANCE-ZANDO', '24'),
(25, 'MIKONDO-SONAS', '25'),
(26, 'MATETE-ZANDO', '26'),
(27, 'KINGASANI-INTENDANCE', '27'),
(28, 'CITE VERT-HGK MAMAN YEMO', '28'),
(29, 'CIMETIERE KITAMBO-ZANDO', '29'),
(30, 'MALUKU-PASCAL', '31'),
(31, 'SERVICE NUIT', 'SN'),
(32, '32', '32'),
(33, '33', '33'),
(34, '34', '34');

-- --------------------------------------------------------

--
-- Structure de la table `mouvements`
--

DROP TABLE IF EXISTS `mouvements`;
CREATE TABLE IF NOT EXISTS `mouvements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_document_id` int NOT NULL,
  `type_mouvement` enum('entree','sortie') NOT NULL,
  `quantite` int NOT NULL,
  `date_mouvement` datetime NOT NULL,
  `responsable` varchar(100) NOT NULL,
  `destinataire` varchar(100) DEFAULT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `type_document_id` (`type_document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `mouvements`
--

INSERT INTO `mouvements` (`id`, `type_document_id`, `type_mouvement`, `quantite`, `date_mouvement`, `responsable`, `destinataire`, `commentaire`) VALUES
(15, 3, 'entree', 50, '2025-04-23 04:26:42', 'gest', 'STOCK', 'Nouveau stock il provient de dépôt limete'),
(16, 1, 'entree', 45, '2025-04-23 04:27:49', 'gest', 'STOCK', 'Nouveau stock de dépôt 2'),
(17, 4, 'entree', 5, '2025-04-23 04:32:18', 'gest', 'STOCK', 'PROV DEPOT 2'),
(18, 4, 'sortie', 2, '2025-04-23 04:34:47', 'gest', 'INTER URBAIN', 'PAR LA DEMANDE DU CHEF DE SERVICE INTER URBAIN'),
(19, 2, 'entree', 10, '2025-04-23 05:35:20', 'gest', 'STOCK', ''),
(20, 2, 'entree', 7, '2025-04-23 05:38:37', 'gest', 'STOCK', ''),
(21, 1, 'sortie', 1, '2025-04-23 12:14:41', 'gest', 'STOC', 'CAVA BIEN');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` varchar(50) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
('Admin', 'administrateur'),
('Autor', 'autorité'),
('CAV', 'Contrôle après-vente'),
('CSSB', 'Chef de service Suivi billetterie '),
('GS', 'Gestion stock'),
('Rapro', 'Rapprochement'),
('Vue', 'Visiteur');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id_service` int NOT NULL AUTO_INCREMENT,
  `id_ligne` int NOT NULL,
  `code_service` varchar(10) NOT NULL,
  `nom_service` varchar(50) NOT NULL,
  PRIMARY KEY (`id_service`),
  KEY `id_ligne` (`id_ligne`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id_service`, `id_ligne`, `code_service`, `nom_service`) VALUES
(1, 1, '01', '101'),
(2, 1, '02', '102'),
(3, 1, '03', '103'),
(4, 1, '04', '104'),
(5, 1, '05', '105'),
(6, 1, '06', '106'),
(7, 1, '07', '107'),
(8, 1, '08', '108'),
(9, 1, '09', '109'),
(10, 1, '10', '110'),
(11, 2, '01', '201'),
(12, 2, '02', '202'),
(13, 2, '03', '203'),
(14, 2, '04', '204'),
(15, 2, '05', '205'),
(16, 2, '06', '206'),
(17, 3, '01', '301'),
(18, 3, '02', '302'),
(19, 3, '03', '303'),
(20, 3, '04', '304'),
(21, 3, '05', '305'),
(22, 3, '06', '306'),
(23, 4, '01', '401'),
(24, 4, '02', '402'),
(25, 4, '03', '403'),
(26, 4, '04', '404'),
(27, 4, '05', '405'),
(28, 4, '06', '406'),
(35, 4, '07', '407'),
(36, 4, '08', '408'),
(37, 4, '09', '409'),
(38, 4, '10', '410'),
(39, 5, '01', '501'),
(40, 5, '02', '502'),
(41, 5, '03', '503'),
(42, 5, '04', '504'),
(43, 5, '05', '505'),
(44, 5, '06', '506'),
(45, 6, '01', '601'),
(46, 6, '02', '602'),
(47, 6, '03', '603'),
(48, 6, '04', '604'),
(49, 6, '05', '605'),
(50, 6, '06', '606'),
(51, 7, '01', '701'),
(52, 7, '02', '702'),
(53, 7, '03', '703'),
(54, 7, '04', '704'),
(55, 7, '05', '705'),
(56, 7, '06', '706'),
(57, 8, '01', '801'),
(58, 8, '02', '802'),
(59, 8, '03', '803'),
(60, 8, '04', '804'),
(61, 8, '05', '805'),
(62, 8, '06', '806'),
(63, 9, '01', '901'),
(64, 9, '02', '902'),
(65, 9, '03', '903'),
(66, 9, '04', '904'),
(67, 9, '05', '905'),
(68, 9, '06', '906'),
(69, 10, '01', '1001'),
(70, 10, '02', '1002'),
(71, 10, '03', '1003'),
(72, 10, '04', '1004'),
(73, 10, '05', '1005'),
(74, 10, '06', '1006'),
(75, 11, '01', '1101'),
(76, 11, '02', '1102'),
(77, 11, '03', '1103'),
(78, 11, '04', '1104'),
(79, 11, '05', '1105'),
(80, 11, '06', '1106'),
(81, 12, '01', '1201'),
(82, 12, '02', '1202'),
(83, 12, '03', '1203'),
(84, 12, '04', '1204'),
(85, 12, '05', '1205'),
(86, 12, '06', '1206'),
(87, 13, '01', '1301'),
(88, 13, '02', '1302'),
(89, 13, '03', '1303'),
(90, 13, '04', '1304'),
(91, 13, '05', '1305'),
(92, 13, '06', '1306'),
(93, 14, '01', '1401'),
(94, 14, '02', '1402'),
(95, 14, '03', '1403'),
(96, 14, '04', '1404'),
(97, 14, '05', '1405'),
(98, 14, '06', '1406'),
(99, 15, '01', '1501'),
(100, 15, '02', '1502'),
(101, 15, '03', '1503'),
(102, 15, '04', '1504'),
(103, 15, '05', '1505'),
(104, 15, '06', '1506'),
(105, 15, '07', '1507'),
(106, 15, '08', '1508'),
(107, 15, '09', '1509'),
(108, 15, '10', '1510'),
(109, 16, '01', '1601'),
(110, 16, '02', '1602'),
(111, 16, '03', '1603'),
(112, 16, '04', '1604'),
(113, 16, '05', '1605'),
(114, 16, '06', '1606'),
(115, 17, '01', '1701'),
(116, 17, '02', '1702'),
(117, 17, '03', '1703'),
(118, 17, '04', '1704'),
(119, 17, '05', '1705'),
(120, 17, '06', '1706'),
(121, 18, '01', '1801'),
(122, 18, '02', '1802'),
(123, 18, '03', '1803'),
(124, 18, '04', '1804'),
(125, 18, '05', '1805'),
(126, 18, '06', '1806'),
(127, 19, '01', '1901'),
(128, 19, '02', '1902'),
(129, 19, '03', '1903'),
(130, 19, '04', '1904'),
(131, 19, '05', '1905'),
(132, 19, '06', '1906'),
(133, 20, '01', '2001'),
(134, 20, '02', '2002'),
(135, 20, '03', '2003'),
(136, 20, '04', '2004'),
(137, 20, '05', '2005'),
(138, 20, '06', '2006'),
(139, 21, '01', '2101'),
(140, 21, '02', '2102'),
(141, 21, '03', '2103'),
(142, 21, '04', '2104'),
(143, 21, '05', '2105'),
(144, 21, '06', '2106'),
(145, 22, '01', '2201'),
(146, 22, '02', '2202'),
(147, 22, '03', '2203'),
(148, 22, '04', '2204'),
(149, 22, '05', '2205'),
(150, 22, '06', '2206'),
(151, 23, '01', '2301'),
(152, 23, '02', '2302'),
(153, 23, '03', '2303'),
(154, 23, '04', '2304'),
(155, 23, '05', '2305'),
(156, 23, '06', '2306'),
(157, 24, '01', '2401'),
(158, 24, '02', '2402'),
(159, 24, '03', '2403'),
(160, 24, '04', '2404'),
(161, 24, '05', '2405'),
(162, 24, '06', '2406'),
(163, 25, '01', '2501'),
(164, 25, '02', '2502'),
(165, 25, '03', '2503'),
(166, 25, '04', '2504'),
(167, 25, '05', '2505'),
(168, 25, '06', '2506'),
(169, 26, '01', '2601'),
(170, 26, '02', '2602'),
(171, 26, '03', '2603'),
(172, 26, '04', '2604'),
(173, 26, '05', '2605'),
(174, 26, '06', '2606'),
(175, 27, '01', '2701'),
(176, 27, '02', '2702'),
(177, 27, '03', '2703'),
(178, 27, '04', '2704'),
(179, 27, '05', '2705'),
(180, 27, '06', '2706'),
(181, 28, '01', '2801'),
(182, 28, '02', '2802'),
(183, 28, '03', '2803'),
(184, 28, '04', '2804'),
(185, 28, '05', '2805'),
(186, 28, '06', '2806'),
(187, 29, '01', '2901'),
(188, 29, '02', '2902'),
(189, 29, '03', '2903'),
(190, 29, '04', '2904'),
(191, 29, '05', '2905'),
(192, 29, '06', '2906'),
(193, 30, '01', '3101'),
(195, 30, '02', '3102'),
(196, 30, '03', '3103'),
(197, 30, '04', '3104'),
(198, 30, '05', '3105'),
(199, 30, '06', '3106'),
(200, 30, '07', '3107'),
(201, 30, '08', '3108'),
(202, 30, '09', '3109'),
(203, 30, '10', '3110'),
(204, 30, '11', '3111'),
(205, 30, '12', '3112'),
(206, 30, '13', '3113'),
(207, 30, '14', '3114'),
(208, 30, '15', '3115'),
(209, 30, '16', '3116'),
(210, 30, '17', '3117'),
(211, 30, '18', '3118'),
(212, 30, '19', '3119'),
(213, 31, '01', 'SN01'),
(214, 31, '02', 'SN02'),
(215, 31, '03', 'SN03'),
(216, 31, '04', 'SN04'),
(217, 31, '05', 'SN05'),
(218, 31, '06', 'SN06'),
(219, 31, '07', 'SN07'),
(220, 31, '08', 'SN08'),
(221, 31, '09', 'SN09'),
(222, 31, '10', 'SN10'),
(223, 31, '11', 'SN11'),
(224, 31, '12', 'SN12'),
(225, 31, '13', 'SN13'),
(226, 31, '14', 'SN14'),
(227, 31, '15', 'SN15'),
(228, 31, '16', 'SN16'),
(229, 31, '17', 'SN17'),
(230, 31, '18', 'SN18'),
(231, 31, '19', 'SN19'),
(232, 31, '20', 'SN20'),
(233, 31, '21', 'SN21'),
(234, 31, '22', 'SN22'),
(235, 31, '23', 'SN23'),
(236, 31, '24', 'SN24'),
(237, 31, '25', 'SN25'),
(238, 31, '26', 'SN26'),
(239, 31, '27', 'SN27'),
(240, 31, '28', 'SN28'),
(241, 31, '29', 'SN29'),
(242, 31, '30', 'SN30'),
(243, 31, '31', 'SN31'),
(244, 31, '32', 'SN32'),
(245, 31, '33', 'SN33'),
(246, 31, '34', 'SN34'),
(247, 31, '35', 'SN35'),
(248, 31, '36', 'SN36'),
(249, 31, '37', 'SN37'),
(250, 31, '38', 'SN38'),
(251, 31, '39', 'SN39'),
(252, 31, '40', 'SN40');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_document_id` int NOT NULL,
  `quantite` int NOT NULL,
  `date_entree` datetime NOT NULL,
  `reference` varchar(100) NOT NULL,
  `emplacement` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_document_id` (`type_document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `type_document_id`, `quantite`, `date_entree`, `reference`, `emplacement`) VALUES
(2, 1, 45, '2025-04-23 04:27:49', 'NORMAL', 'Entrée par gest'),
(6, 2, 7, '2025-04-23 05:38:37', 'NORMAL', 'Entrée par gest');

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id_ticket` int NOT NULL AUTO_INCREMENT,
  `nom_service` varchar(20) NOT NULL,
  `total_tickets` int NOT NULL,
  `tickets_rendus` int NOT NULL,
  `date_operation` date NOT NULL,
  PRIMARY KEY (`id_ticket`),
  KEY `id_service` (`nom_service`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `nom_service`, `total_tickets`, `tickets_rendus`, `date_operation`) VALUES
(1, '1', 2012, 498, '2025-03-30'),
(2, '1001', 2012, 498, '2025-03-30'),
(3, '1501', 4444, 1263, '2025-03-23'),
(6, '1001', 555, 55, '2025-03-30'),
(7, '1', 100, 10, '2025-03-30'),
(8, '1', 100, 10, '2025-03-23'),
(9, '3', 255, 55, '2025-03-30'),
(10, '1503', 1000, 323, '2025-03-30'),
(11, '104', 1000, 345, '2025-03-30'),
(12, 'SN01', 4000, 5, '2025-03-30'),
(13, 'SN02', 5555, 453, '2025-03-31'),
(14, '1501', 5000, 1324, '2025-03-31'),
(15, 'SN02', 1000, 50, '2025-03-31'),
(16, 'SN01', 1000, 200, '2025-03-31'),
(17, '102', 7, 2, '2025-04-01'),
(18, '1503', 23, 10, '2025-04-01');

-- --------------------------------------------------------

--
-- Structure de la table `types_documents`
--

DROP TABLE IF EXISTS `types_documents`;
CREATE TABLE IF NOT EXISTS `types_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `types_documents`
--

INSERT INTO `types_documents` (`id`, `nom`, `description`) VALUES
(1, 'Registre d\'affectation', 'Document pour l\'affectation des billets'),
(2, 'Fiche de réception et de retour tickets', 'Document pour la réception et le retour des tickets'),
(3, 'Carnet', 'Carnet de billets'),
(4, 'Feuille de route', 'Document pour le suivi des déplacements');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `num` int NOT NULL AUTO_INCREMENT,
  `matricule` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `postnom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `compte_utilisateur` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `fonction_id` varchar(50) NOT NULL,
  `grade_id` varchar(50) NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`num`),
  UNIQUE KEY `matricule` (`matricule`),
  UNIQUE KEY `compte_utilisateur` (`compte_utilisateur`),
  KEY `fonction_id` (`fonction_id`),
  KEY `grade_id` (`grade_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`num`, `matricule`, `nom`, `postnom`, `prenom`, `compte_utilisateur`, `mot_de_passe`, `fonction_id`, `grade_id`, `role_id`, `date`) VALUES
(10, 100010, 'test1', 'test1', 'test1', 'test1', '$2y$10$.8//hp9maf/YKWYssJEXVu.FUO3sf6Ll3ZpSegB6IkuDpURFK3JFq', 'CSI', 'C7', 'Admin', '2025-03-23 15:10:35'),
(15, 100012, 'tata', 'tata', 'tata', 'tata', '$2y$10$BPqgDRyFlgS/vbKnDTtF9e5PXnBov.4DZM7GzHo6PNg7rEKuDMYF6', 'CSI', 'CBSB', 'Admin', '2025-04-01 10:10:53'),
(16, 100035, 'GEST', 'GEST', 'GEST', 'gest', '$2y$10$GWB2jcBuG2Nze7NmNKVLguLQr/NFYmijg0FiRbh1IQcgBO6KdplFu', 'CSI', 'C7', 'GS', '2025-04-03 06:32:49'),
(17, 776544, 'MANGA', 'MANGA', 'MANGA', 'manga', '$2y$10$o/A9GYiXABH4.SF7mwOkxeKoFSigNDyrUicioWsyKNQREEqqDgN5K', 'CSIA', 'CSSB', 'GS', '2025-04-18 00:01:40');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD CONSTRAINT `affectations_ibfk_1` FOREIGN KEY (`services_id_service`) REFERENCES `services` (`id_service`),
  ADD CONSTRAINT `affectations_ibfk_2` FOREIGN KEY (`lignes_id_ligne`) REFERENCES `lignes` (`id_ligne`);

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_ligne`) REFERENCES `lignes` (`id_ligne`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`fonction_id`) REFERENCES `fonction` (`id`),
  ADD CONSTRAINT `utilisateur_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`id`),
  ADD CONSTRAINT `utilisateur_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
