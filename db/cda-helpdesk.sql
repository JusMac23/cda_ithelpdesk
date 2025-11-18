-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Nov 14, 2025 at 07:54 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cda-helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_directory`
--

CREATE TABLE `active_directory` (
  `id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middle_initial` varchar(10) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `division_section` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `ad_username` varchar(10) DEFAULT NULL,
  `ad_password` varchar(255) DEFAULT NULL,
  `date_registered` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `active_directory`
--

INSERT INTO `active_directory` (`id`, `firstname`, `middle_initial`, `lastname`, `email`, `division_section`, `status`, `ad_username`, `ad_password`, `date_registered`, `date_updated`) VALUES
(1, 'Johaira', 'S.', 'Limbao', 'j_limbao@cda.gov.ph', 'BOD-ASEC.DISIMBAN', 'Active', NULL, '$2y$12$42VBaWyx5XpFAV7jZ0tUYOkJ094MASZfw6gc33jnaQkkMsPhFlmiC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Abdulsalam', 'A.', 'Guinomla', 'a_guinomla@cda.gov.ph', 'BOD-ASEC.GUINOMLA', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Arghanaim', 'U.', 'Amboludto', 'a_amboludto@cda.gov.ph', 'BOD-ASEC.GUINOMLA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Ansano', 'M.', 'Ali', '', 'BOD-ASEC.GUINOMLA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Mia Ron', 'R.', 'Reyes', 'm_reyes@cda.gov.ph', 'BOD-ASEC.GUINOMLA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Virgilio', 'R.', 'Lazaga', 'v_lazaga@cda.gov.ph', 'BOD-ASEC.LAZAGA', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Elvira', 'A.', 'Pasagui', 'e_pasagui@cda.gov.ph', 'BOD-ASEC.LAZAGA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Marianne Rose', 'D.', 'Lazaga', 'm_lazaga@cda.gov.ph', 'BOD-ASEC.LAZAGA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Marilou', 'M.', 'Sanchez', 'm_sanchez@cda.gov.ph', 'BOD-ASEC.LAZAGA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Myrla', 'B.', 'Paradillo', 'm_paradillo@cda.gov.ph', 'BOD-ASEC.PARADILLO', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Benjie', 'R.', 'Magdayo', 'b_magdayo@cda.gov.ph', 'BOD-ASEC.PARADILLO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Liv Yedda Mari', 'R.', 'Pajaron', 'l_pajaron@cda.gov.ph', 'BOD-ASEC.PARADILLO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Richard', '', 'Patricio', 'r_patricio@cda.gov.ph', 'BOD-ASEC.PARADILLO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Luz', 'H.', 'Yringco', 'l_yringco@cda.gov.ph', 'BOD-ASEC.YRINCO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Ma. Diezy Louise', 'M.', 'Moreno', 'm_moreno@cda.gov.ph', 'BOD-ASEC.YRINCO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Salvacion', 'M.', 'Guston', 's_guston@cda.gov.ph', 'BOD-ASEC.YRINCO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Vergel', 'M.', 'Hilario', 'v_hilario@cda.gov.ph', 'BOD-ASEC.HILARIO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Mary Ann', 'M.', 'Alday', 'm_alday@cda.gov.ph', 'BOD-ASEC.HILARIO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Felicidad', '', 'Dayupay', 'f_dayupay@cda.gov.ph', 'BOD-ASEC.HILARIO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Anabelle', 'D.', 'Tuy', 'a_tuy@cda.gov.ph', 'BOD-ASEC.HILARIO', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Julio', 'E.', 'Casilan', 'j_casilan@cda.gov.ph', 'GASS-ADMIN-GSS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Lovely', 'B.', 'Abuyuan', '', 'BOD-BOARDSEC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Marilyn', 'D.', 'Eso', 'm_eso@cda.gov.ph', 'BOD-OC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Joseph', 'B.', 'Encabo', 'j_encabo@cda.gov.ph', 'BOD-OC', 'Registered', 'jencabo', 'Jbe@1432', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Louise Ann', 'B.', 'Sabido', 'l_sabido@cda.gov.ph', 'BOD-OC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Ronaldo', 'A.', 'Caya', '', 'BOD-OC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Raymund', 'P.', 'Sangalang', 'r_sangalang@cda.gov.ph', 'BOD-OC', 'Registered', 'rsangalang', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Liam Jay', 'C.', 'Atienza', 'lj_atienza@cda.gov.ph', 'BOD-OC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Jo-Anne', 'G.', 'Mirabueno', 'j_mirabueno@cda.gov.ph', 'BOD-OC', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Mhervy', 'N.', 'Registrado', '', 'COA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Estela', '', 'Salandanan', '', 'COA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Irene', '', 'Lim', '', 'COA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Richard', 'M.', 'Putong', '', 'COA', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Ma. Lourdes', 'P.', 'Pacao', 'm_pacao@cda.gov.ph', 'CSFS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Jesusa Jamila Joy', 'C.', 'Autea', 'j_autea@cda.gov.ph', 'CSFS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Jo Ann', 'C.', 'Gamboa', 'j_gamboa@cda.gov.ph', 'CSFS-IED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Ian Dave', 'U.', 'Alindajao', 'i_alindajao@cda.gov.ph', 'CSFS-IED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'John Erick', 'R.', 'Zepeda', 'j_zepeda@cda.gov.ph', 'CSFS-IED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Leonila', 'T.', 'Memoracion', 'l_memoracion@cda.gov.ph', 'CSFS-IED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Rodrigo', 'I.', 'Rebello', 'r_rebello@cda.gov.ph', 'CSFS-IED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Recto', 'E.', 'Transfiguracion', 'r_tranfiguracion@cda.gov.ph', 'CSFS-TAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'Mary Grace', 'I.', 'Cinco', 'm_cinco@cda.gov.ph', 'CSFS-TAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'Marygrace', 'C.', 'Jaquilmac', 'm_jaquilmac@cda.gov.ph', 'CSFS-TAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Rean', 'C.', 'Escandor', 'r_escandor@cda.gov.ph', 'CSFS-TAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'Edmon', 'J.', 'Yaco', 'e_yaco@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Melchor', 'P.', 'Cariño', 'm_cariÃ±o@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Sheryl', 'P.', 'Soriano', 's_soriano@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Joshua Jade', 'G.', 'Corpus', 'jj_corpuz@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Ma. Corazon Armela', 'A.', 'Brilliantes', 'm_brillantes@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Joselito', 'O.', 'Hallazgo', 'j_hallazgo@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Liezl', 'R.', 'Nieva', 'l_nieva@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Christine Joy', '', 'Gumapac', 'cj_gumapac@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Jhonree Paul', 'S.', 'Cristino', 'jp_cristino@cda.gov.ph', 'FINANCE', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Mona Liza', 'A.', 'Juarez', 'm_juarez@cda.gov.ph', 'GASS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Jonalyn', 'D.', 'Morante', '', 'GASS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Angelita', 'S.', 'Udan', '', 'GASS-ADMIN-CASH', 'For-Deletion', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Charmaine', 'D.', 'Eco', '', 'GASS-ADMIN-CASH', 'For-Deletion', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Eddie', 'D.', 'Damaso', 'e_damaso@cda.gov.ph', 'GASS-ADMIN-CASH', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Jonal', 'A.', 'De Los Reyes', 'j_delosreyes@cda.gov.ph', 'GASS-ADMIN-GSS', 'Registered', 'jdreyes', 'Jadr@123', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Elmer', 'A.', 'Bajado', 'e_bajado@cda.gov.ph', 'GASS-ADMIN-GSS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Ma. Antonette', 'A.', 'Pimentel', 'a_pimentel@cda.gov.ph', 'GASS-ADMIN-PROCUREMENT', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Christopher', 'D.', 'Villanueva', '', 'GASS-ADMIN-PROCUREMENT', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Keith Russel', 'P.', 'Berondo', '', 'GASS-ADMIN-PROCUREMENT', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Sherwin', '', 'Dela Cruz', '', 'GASS-ADMIN-PROCUREMENT', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Sulficio', 'V.', 'Rubico', '', 'GASS-ADMIN-PROCUREMENT', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Ronaldo', 'G.', 'Minlay', 'r_minlay@cda.gov.ph', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Guillermo', 'A.', 'Jose', 'g_jose@cda.gov.ph', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Jasper', 'A.', 'Tamayo', '', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Michael', 'C.', 'Cabulay', '', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Arnulfo', 'B.', 'Aspero', '', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Erwin', 'M.', 'Caluag', '', 'GASS-ADMIN-PROPERTY', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Noel', 'A.', 'Buna', '', 'GASS-ADMIN-PROPERTY-(MAINTENANCE)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Noel', '', 'Magtibay', '', 'GASS-ADMIN-PROPERTY-(MAINTENANCE)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Edmundo', 'C.', 'Pelagio', '', 'GASS-ADMIN-PROPERTY-(MAINTENANCE)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Fidel', 'V.', 'Oaman', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'Abdul Latip', 'U.', 'Garsi', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Roderick', 'G.', 'Mosinde', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Faizal', 'A.', 'Manapa-At', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Danilo', 'C.', 'Macunat', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'Alberto', 'A.', 'Disamburun', 'a_disimban@cda.gov.ph', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Mienrado', 'B.', 'Calla', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Hernane', 'P.', 'Daganio', '', 'GASS-ADMIN-PROPERTY-(DRIVER)', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Abigail', 'A.', 'Pizarras', 'a_pizarras@cda.gov.ph', 'GASS-ADMIN-RECORDS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Cesar', 'O.', 'Deuna', '', 'GASS-ADMIN-RECORDS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Josie', 'L.', 'Villaver', 'j_vallaver@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Rechel', 'B.', 'San Jose', 'r_sanjose@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Angelo', 'S.', 'Bugarin', 'a_bugarin@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Maria Elizabeth', 'B.', 'Panopio', 'm_panopio@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Ma. Clarissa', 'L.', 'Salonga', 'm_salonga@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Robie Jay', 'A.', 'Victoria', 'r_victoria@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Beatriz Isabelle', 'P.', 'Calugay', '', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'Hannah Joyce', 'A.', 'Gervacio', 'h_gervacio@cda.gov.ph', 'PPDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'Naomi', 'J.', 'Estabillo', 'n_estabillo@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'Jonsie', 'D.', 'Baysa', 'j_baysa@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'John Zernan', 'B.', 'Luna', 'jz_luna@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'Fe', 'D.', 'Del Rosario', 'f_delrosario@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Rafael', 'C.', 'Oxales', 'r_oxales@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'Booker', 'A.', 'Salumbides', '', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'Dexter', '', 'Goyala', 'd_goyala@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'Doroteo', 'M.', 'Dorozan', 'd_dorozan@cda.gov.ph', 'HRDD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'Ryan', 'F.', 'Barcelo', 'r_barcelo@cda.gov.ph', 'IAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'Jenny Vi', 'L.', 'Alinday', 'jv_alinday@cda.gov.ph', 'IAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'Aisha', 'J.', 'Macud', 'a_macud@cda.gov.ph', 'IAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'Jean Patrice', 'P.', 'Panopio', 'j_panopio@cda.gov.ph', 'IAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'Ronaldo', 'G.', 'Rivera', 'r_rivera@cda.gov.ph', 'ICTD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'Bonifacio', 'D.', 'Garcia', 'b_garcia@cda.gov.ph', 'ICTD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'Carlito', 'V.', 'Buan', 'c_buan@cda.gov.ph', 'ICTD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'Justine', 'C.', 'Macarayan', 'j_macarayan@cda.gov.ph', 'ICTD', 'Registered', 'jmacarayan', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'Joseph Rainer', 'C.', 'Rosarial', 'jr_rosarial@cda.gov.ph', 'ICTD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'Romaine Niño', 'P.', 'Talucod', 'r_talucod@cda.gov.ph', 'ICTD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'Jess', '', 'Agnes', 'j_agnes@cda.gov.ph', 'ICTD', 'Registered', 'JAgnes', 'Cd@it1124', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'Ray', 'R.', 'Elevazo', 'r_elevazo@cda.gov.ph', 'IDS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'Nemia', 'B.', 'Reyes', 'n_reyes@cda.gov.ph', 'IDS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'Melissa', 'C.', 'Santos', 'm_santos@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'Dolores', 'B.', 'Lacaba', 'd_lacaba@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'Cherry', 'C.', 'Reyes', 'c_reyes@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'Samuel', 'M.', 'Gimpayan', 's_gimpayan@cda.gov.ph', 'IDS-CPDAD', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'Lester John', 'B.', 'Molina', 'l_molina@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'Eileen', 'L.', 'Doroja', 'e_doroja@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'Valentin', 'G.', 'Diola', 'v_diola@cda.gov.ph', 'IDS-CPDAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'Teresita Leighlany', 'T.', 'Cariaso', 't_cariaso@cda.gov.ph', 'IDS-CRITD', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'Cherryl Catrina', 'B.', 'Marders', 'c_marders@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'Emme Grace', 'P.', 'Alverne', 'e_alverne@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'Khamima', 'M.', 'Mama', 'k_mama@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Mary Rose', '', 'Pradil-Vinuya', 'mr_vinuya@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Beatriz Samanta', 'M.', 'Castro', 'b_castro@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'Maria Cielo', 'P.', 'Garcia', 'm_garcia@cda.gov.ph', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'Jay-R', 'P.', 'Narvaja', '', 'IDS-CRITD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'Leah', 'B.', 'Banagui-Han', 'l_b_han@cda.gov.ph', 'LAS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'Ma. Elizabeth', 'F.', 'Espanol', 'm_espanol@cda.gov.ph', 'LAS', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'Ma. Caridad', 'D.', 'Graza', 'm_graza@cda.gov.ph', 'LAS-AJUDICATION', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'James', 'B.', 'Fernandez', 'j_fernandez@cda.gov.ph', 'LAS-AJUDICATION', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'Somiranao', 'M.', 'Dimnatang', 's_dimnatang@cda.gov.ph', 'LAS-AJUDICATION', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'Joselito', 'L.', 'Jao', 'j_jao@cda.gov.ph', 'LAS-AJUDICATION', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'Sheena', 'T.', 'Rima', 's_rima@cda.gov.ph', 'LAS-LEGAL', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'Theresa Marie', 'I.', 'Montales-Dolom', 'tm_dolon@cda.gov.ph', 'LAS-LEGAL', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'Pedro', 'K.', 'Linga', '', 'LAS-LEGAL', 'For-Deletion', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'Jerome', 'N.', 'Gatus', 'j_gatus@cda.gov.ph', 'LAS-LEGAL', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'Phoebe Rose', 'D.', 'Tolete', 'p_tolete@cda.gov.ph', 'LAS-LEGAL', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'Edmund Chris', 'S.', 'Acosido', 'ec_acosido@cda.gov.ph', 'OFAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'Rosemarie', 'J.', 'Beltran', 'r_beltran@cda.gov.ph', 'OFAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'Mae Joi Pia', 'S.', 'Santos', 'jp_santos@cda.gov.ph', 'OFAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'Santiago', 'S.', 'Lim', 's_lim@cda.gov.ph', 'OFAD', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'Elizabeth', 'O.', 'Batonan', 'e_batonan@cda.gov.ph', 'RSES', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'Mildred', 'S.', 'Esguerra', 'm_esguerra@cda.gov.ph', 'RSES', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'Ronel', 'T.', 'Marmol', '', 'RSES', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'Wilmour Kenn', 'P.', 'Ballesteros', '', 'RSES', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'Inocencio', 'M.', 'Malapit', 'i_malapit@cda.gov.ph', 'RSES-REG', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'Augusto Salvador', 'P.', 'Balles', 'a_balles@cda.gov.ph', 'RSES-REG', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'Nympha', 'D.', 'Olegario', 'n_olegario@cda.gov.ph', 'RSES-REG', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'Edna', 'P.', 'Cara', 'e_cara@cda.gov.ph', 'RSES-REG', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'Jeffrey', 'U.', 'Francisco', 'j_francisco@cda.gov.ph', 'RSES-REG', 'For-Deletion', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'Jerce', 'B.', 'Cabuso', '', 'RSES-REG', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'Doris', 'D.', 'Teodoro', 'd_teodoro@cda.gov.ph', 'RSES-SED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'Sally', 'S.', 'Trinanes', 's_trinanes@cda.gov.ph', 'RSES-SED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'Rowena', 'M.', 'Gumapac', 'r_gumapac@cda.gov.ph', 'RSES-SED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'Arnel', 'R.', 'Abenojar', 'a_abenojar@cda.gov.ph', 'RSES-SED', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'Pendatun', 'B.', 'Disimban', 'p_disimban@cda.gov.ph', 'BOD-ASEC.DISIMBAN', 'Registered', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('cda_ithelpdesk_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:67:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"view_dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"view_profile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"edit_profile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"update_password\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"delete_profile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"view_all_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"create_ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"reassign_ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:20:\"update_status_ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:13:\"delete_ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:13:\"search_ticket\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"generate_tsar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"generate_report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:24:\"view_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:26:\"create_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:28:\"reassign_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:33:\"update_status_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:26:\"delete_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:26:\"search_myrequested_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:25:\"view_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:27:\"create_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:29:\"reassign_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:34:\"update_status_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:27:\"delete_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:27:\"search_assignedtome_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:23:\"view_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:25:\"create_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:27:\"reassign_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:32:\"update_status_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:25:\"delete_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:25:\"search_reassigned_tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:19:\"view_all_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:24:\"view_overview_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:17:\"create_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:15:\"view_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"edit_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:36;a:3:{s:1:\"a\";i:37;s:1:\"b\";s:17:\"delete_databreach\";s:1:\"c\";s:3:\"web\";}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:17:\"search_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:17:\"filter_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:24:\"generate_docs_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:16:\"print_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:9:\"view_dbrt\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:11:\"create_dbrt\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:9:\"edit_dbrt\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:11:\"delete_dbrt\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:24:\"view_technical_personnel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:26:\"create_technical_personnel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:24:\"edit_technical_personnel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:26:\"delete_technical_personnel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:26:\"search_technical_personnel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:23:\"view_technical_services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:25:\"create_technical_services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:23:\"edit_technical_services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:25:\"delete_technical_services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:25:\"search_technical_services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:15:\"view_tech_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:17:\"create_tech_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:15:\"edit_tech_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:17:\"delete_tech_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:17:\"search_tech_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:10:\"view_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:12:\"create_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:10:\"edit_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:12:\"delete_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:12:\"search_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:17:\"assess_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:19:\"evaluate_databreach\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:3:\"DPO\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"DBRT\";s:1:\"c\";s:3:\"web\";}}}', 1763184084);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `databreach_dbrt_team`
--

CREATE TABLE `databreach_dbrt_team` (
  `dbrt_id` int(11) NOT NULL,
  `firstname` varchar(155) NOT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `lastname` varchar(155) NOT NULL,
  `email` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `databreach_dbrt_team`
--

INSERT INTO `databreach_dbrt_team` (`dbrt_id`, `firstname`, `middle_initial`, `lastname`, `email`, `region`, `created_at`, `updated_at`) VALUES
(1, 'John', 'A.', 'Doe', 'jmacarayan17@gmail.com', 'CDA Region X', '2025-11-06 07:19:26', '2025-11-12 06:38:27'),
(2, 'John', 'A.', 'Doe', 'j_macarayan@cda.gov.ph', 'CDA NCR', '2025-11-07 03:14:22', '2025-11-11 03:36:06'),
(4, 'Joseph', 'A.', 'Rosarial', 'jr_rosarial@cda.gov.ph', 'CDA NIR', '2025-11-07 08:03:00', '2025-11-07 09:20:12'),
(5, 'Jesse', 'A.', 'Agnes', 'j_agnes@cda.gov.ph', 'CDA Region XI', '2025-11-07 08:53:35', '2025-11-07 08:53:35'),
(6, 'Jhlord', NULL, 'Pascual', 'j_pascual@cda.gov.ph', 'CDA HO', '2025-11-12 06:47:17', '2025-11-12 06:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `databreach_for_assessment`
--

CREATE TABLE `databreach_for_assessment` (
  `dbn_id` int(11) NOT NULL,
  `dbn_number` varchar(155) NOT NULL,
  `sender_fullname` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `date_occurrence` datetime NOT NULL,
  `date_discovery` datetime NOT NULL,
  `date_notification` datetime NOT NULL,
  `pic` varchar(155) NOT NULL,
  `brief_summary` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `databreach_for_assessment`
--

INSERT INTO `databreach_for_assessment` (`dbn_id`, `dbn_number`, `sender_fullname`, `sender_email`, `date_occurrence`, `date_discovery`, `date_notification`, `pic`, `brief_summary`, `created_at`, `updated_at`) VALUES
(1, 'CDA-DBN-2025-01', 'John A. Doe', 'jmacarayan17@gmail.com', '2025-11-03 16:26:00', '2025-11-04 16:26:00', '2025-11-05 16:26:00', 'CDA CAR', 'Asdfghkjklzxvbnm', '2025-11-05 08:27:10', '2025-11-05 08:27:10'),
(2, 'CDA-DBN-2025-02', 'Joseph Smith', 'j_macarayan@gmail.com', '2025-11-03 16:35:00', '2025-11-04 16:35:00', '2025-11-05 16:35:00', 'CDA CAR', 'Asfsdgsdfgdfhfgddhfg', '2025-11-05 08:35:38', '2025-11-05 08:35:38'),
(3, 'CDA-DBN-2025-03', 'John Smith', 'jmacarayan17@gmail.com', '2025-11-03 16:40:00', '2025-11-04 16:40:00', '2025-11-05 16:40:00', 'CDA Region I', 'Asfdsafsdgsdfhdfg.', '2025-11-05 08:40:37', '2025-11-05 08:40:37'),
(4, 'CDA-DBN-2025-04', 'Joseph Doe', 'j_macarayan@gmail.com', '2025-11-03 16:49:00', '2025-11-04 16:49:00', '2025-11-05 16:49:00', 'CDA Region II', 'Asdfghjklzxcvbnm.', '2025-11-05 08:49:51', '2025-11-05 08:49:51'),
(5, 'CDA-DBN-2025-05', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:04:00', '2025-11-04 17:04:00', '2025-11-05 17:04:00', 'CDA Region III', 'Asdfdghfgfkhgfghfghgfgjghfj.', '2025-11-05 09:04:31', '2025-11-05 09:04:31'),
(6, 'CDA-DBN-2025-06', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:06:00', '2025-11-04 17:07:00', '2025-11-05 17:07:00', 'CDA Region III', 'Asdfdfdjgsjhgjdhsgrgg.', '2025-11-05 09:07:16', '2025-11-05 09:07:16'),
(7, 'CDA-DBN-2025-07', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:10:00', '2025-11-04 17:10:00', '2025-11-05 17:10:00', 'CDA Region IV-A', 'Afdgfdhfghf', '2025-11-05 09:10:42', '2025-11-05 09:10:42'),
(8, 'CDA-DBN-2025-08', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:14:00', '2025-11-04 17:14:00', '2025-11-05 17:14:00', 'CDA Region IV-A', 'Adfdsfsddgdfhfghdfgdfgdfgdfgdsfs.', '2025-11-05 09:14:25', '2025-11-05 09:14:25'),
(9, 'CDA-DBN-2025-09', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:22:00', '2025-11-04 17:22:00', '2025-11-05 17:22:00', 'CDA Region IV-A', 'Adfsdgdfhfghfgjfghfghdfsgsdfg.', '2025-11-05 09:23:04', '2025-11-05 09:23:04'),
(10, 'CDA-DBN-2025-10', 'Jess Doe', 'jmacarayan17@gmail.com', '2025-11-03 17:24:00', '2025-11-04 17:24:00', '2025-11-05 17:24:00', 'CDA Region IV-A', 'Aasdfsdgdfhfgjgfkgfhjkgjfhjgh.', '2025-11-05 09:24:57', '2025-11-05 09:24:57'),
(11, 'CDA-DBN-2025-11', 'Juan A. Luna', 'jmacarayan17@gmail.com', '2025-11-04 14:55:00', '2025-11-05 14:55:00', '2025-11-06 14:55:00', 'CDA Region X', 'On November 6, 2025, at approximately 10:30 AM, an issue occurred involving a network connectivity outage affecting multiple users in the IT Division, CDA Central Office. The incident was reported by Juan Dela Cruz through the ICTD Helpdesk System. Initial assessment revealed that the cause was a faulty network switch in the server room. Immediate action was taken by IT personnel Justine Macarayan, who replaced the defective hardware and restored connectivity by 11:15 AM. Operations resumed normally thereafter.', '2025-11-06 06:56:06', '2025-11-06 06:56:06'),
(12, 'CDA-DBN-2025-12', 'San Pedro', 'jmacarayan17@gmail.com', '2025-11-05 16:03:00', '2025-11-06 16:03:00', '2025-11-07 16:04:00', 'CDA Region XIII', 'On November 7, 2025, at approximately 10:30 AM, an incident occurred involving unauthorized access to a shared network folder within the organization. The issue was discovered by IT staff during routine system monitoring. Immediate action was taken to restrict access, secure the affected files, and initiate an internal investigation.\r\n\r\nPreliminary findings indicate that the incident was caused by misconfigured user permissions, which inadvertently allowed access to sensitive information. No evidence of data theft or malicious intent has been found as of this report. Further assessment and preventive measures, including permission audits and user awareness training, are currently underway.', '2025-11-07 08:04:51', '2025-11-07 08:04:51'),
(13, 'CDA-DBN-2025-13', 'Sam Joseph', 'jmacarayan17@gmail.com', '2025-11-05 17:25:00', '2025-11-06 17:25:00', '2025-11-07 17:25:00', 'CDA Region V', 'On November 7, 2025, a potential data breach was identified involving unauthorized access to a shared network folder containing employee records. Immediate action was taken to restrict access, investigate the source of the breach, and notify affected parties. The incident was contained within two hours, and further preventive measures are being implemented to enhance system security.', '2025-11-07 09:26:34', '2025-11-07 09:26:34'),
(14, 'CDA-DBN-2025-14', 'Sam Joseph', 'jmacarayan17@gmail.com', '2025-11-04 17:28:00', '2025-11-06 17:28:00', '2025-11-07 17:28:00', 'CDA Region V', 'On November 7, 2025, a potential data breach was identified involving unauthorized access to a shared network folder containing employee records. Immediate action was taken to restrict access, investigate the source of the breach, and notify affected parties. The incident was contained within two hours, and further preventive measures are being implemented to enhance system security.', '2025-11-07 09:28:21', '2025-11-07 09:28:21'),
(15, 'CDA-DBN-2025-15', 'Mark Anthony', 'mark@gmail.com', '2025-11-08 11:28:00', '2025-11-10 11:28:00', '2025-11-11 11:28:00', 'CDA Region VI', 'Asfsdghbsnssjlbvvjsdjdc', '2025-11-11 03:28:39', '2025-11-11 03:28:39'),
(16, 'CDA-DBN-2025-16', 'Joseh Mario', 'jmario@gmail.com', '2025-11-09 11:37:00', '2025-11-10 11:37:00', '2025-11-11 11:37:00', 'CDA Region VIII', 'Asfsdgsjbvjbjjvbbvsvbvbsdhusdff', '2025-11-11 03:38:17', '2025-11-11 03:38:17'),
(17, 'CDA-DBN-2025-17', 'Mark Doe', 'marktahimik@gmail.com', '2025-11-09 11:42:00', '2025-11-10 11:42:00', '2025-11-11 11:42:00', 'CDA Region XII', 'Nahackasfjgsgbsjgbsbgsdjvsfdb dffvsjghdfiogbsgs', '2025-11-11 03:43:28', '2025-11-11 03:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `databreach_notifications`
--

CREATE TABLE `databreach_notifications` (
  `dbn_id` int(11) NOT NULL,
  `dbn_number` varchar(100) NOT NULL,
  `sender_fullname` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `representative` varchar(255) DEFAULT NULL,
  `representative_email_address` varchar(255) DEFAULT NULL,
  `date_occurrence` datetime NOT NULL,
  `date_discovery` datetime NOT NULL,
  `date_notification` datetime NOT NULL,
  `brief_summary` text NOT NULL,
  `notification_type_description` text DEFAULT NULL,
  `sector_name` varchar(255) DEFAULT NULL,
  `subsector_name` varchar(255) DEFAULT NULL,
  `notification_type` varchar(255) DEFAULT NULL,
  `timeliness` varchar(255) DEFAULT NULL,
  `general_cause` varchar(255) DEFAULT NULL,
  `specific_cause` varchar(255) DEFAULT NULL,
  `with_request` enum('Yes','No') DEFAULT NULL,
  `how_breach_occured` text DEFAULT NULL,
  `chronology` text DEFAULT NULL,
  `num_records` int(11) DEFAULT NULL,
  `hundred_plus` tinyint(1) DEFAULT 0,
  `num_records_provide_details` text DEFAULT NULL,
  `description_nature` text DEFAULT NULL,
  `likely_consequences` text DEFAULT NULL,
  `dpo` text DEFAULT NULL,
  `spi` text DEFAULT NULL,
  `other_info` text DEFAULT NULL,
  `measures_to_address` text DEFAULT NULL,
  `measures_to_secure` text DEFAULT NULL,
  `actions_to_mitigate` text DEFAULT NULL,
  `actions_to_inform` text DEFAULT NULL,
  `actions_to_prevent` text DEFAULT NULL,
  `record_type` varchar(255) DEFAULT NULL,
  `data_subjects` varchar(255) DEFAULT NULL,
  `status` varchar(155) NOT NULL DEFAULT 'For Assessment',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `databreach_notifications`
--

INSERT INTO `databreach_notifications` (`dbn_id`, `dbn_number`, `sender_fullname`, `sender_email`, `pic`, `email`, `representative`, `representative_email_address`, `date_occurrence`, `date_discovery`, `date_notification`, `brief_summary`, `notification_type_description`, `sector_name`, `subsector_name`, `notification_type`, `timeliness`, `general_cause`, `specific_cause`, `with_request`, `how_breach_occured`, `chronology`, `num_records`, `hundred_plus`, `num_records_provide_details`, `description_nature`, `likely_consequences`, `dpo`, `spi`, `other_info`, `measures_to_address`, `measures_to_secure`, `actions_to_mitigate`, `actions_to_inform`, `actions_to_prevent`, `record_type`, `data_subjects`, `status`, `created_at`, `updated_at`) VALUES
(9, 'CDA-DBN-2025-01', 'Mark Anthony', 'marktahimik@gmail.com', 'CDA NCR', 'dpo@cda.gov.ph', 'Juan DelaCruz', 'j_macarayan@cda.gov.ph', '2025-11-01 08:39:00', '2025-11-02 08:39:00', '2025-11-03 08:39:00', 'Website suddenly loading and later on it shutdown.', '\"[\\\"Involves SPI or Data that may enable identity fraud\\\",\\\"Likely to give rise to harm to data subjects\\\"]\"', 'Government', 'Executive Department', 'Mandatory', 'Asdfghijkl', 'Malicious Attack/System Glitch', 'Software Failure', 'Yes', 'Asdfghijkl', 'Asdfghijkl', 10000, 0, 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Asdfghijkl', 'Digital Records in Electronic Systems', 'Own Employees', 'For Evaluation', '2025-11-03 00:42:12', '2025-11-11 05:47:09'),
(15, 'CDA-DBN-2025-02', 'Ako Natoy', 'akonatoy@gmail.com', 'CDA Region I', 'dpo@cda.gov.ph', 'John Doe', 'j_macarayan@cda.gov.ph', '2025-11-02 15:14:00', '2025-11-03 15:14:00', '2025-11-04 15:14:00', 'Website suddenly loading and later on bad gateway.', '\"[\\\"Involves SPI or Data that may enable identity fraud\\\",\\\"Likely to give rise to harm to data subjects\\\"]\"', 'Government', 'Executive Department', 'Mandatory', 'Asdfghijkl', 'System Glitch/Human Error', 'Software Maintenance Error', 'No', 'asdfzxcv', 'asdfzxcv', 1000, 0, 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'Juan Luna', 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'asdfzxcv', 'Digital Records in Electronic Systems', 'Own Employees', 'Reported', '2025-11-04 07:16:50', '2025-11-11 05:47:43'),
(16, 'CDA-DBN-2025-10', 'Jess Doe', 'jmacarayan17@gmail.com', 'CDA NCR', 'ncr@cda.gov.ph', 'Juan Dela Cruz', 'j_macarayan@cda.gov.ph', '2025-11-03 17:24:00', '2025-11-04 17:24:00', '2025-11-05 17:24:00', 'System Bad gateway', '\"[\\\"Involves SPI or Data that may enable identity fraud\\\",\\\"Likely to give rise to harm to data subjects\\\"]\"', 'Government', 'Executive Department', 'Mandatory', 'sdfsadfs', 'Malicious Attack', 'Hacking-Website', 'Yes', 'dsgsd', 'sdfsd', 10000, 0, 'sdfsdfs', 'sdfsdfs', 'sdfsdf', 'dfsdfsd', 'fsdfsd', 'dfsdfs', 'fsddfsdfsd', 'sdfsdfs', 'ffsdfddsd', 'sdgdsg', 'gsdgsdgsd', 'Digital Records in Electronic Systems', 'Customers', 'For Evaluation', '2025-11-05 09:24:57', '2025-11-07 05:51:37'),
(17, 'CDA-DBN-2025-11', 'Juan A. Luna', 'jmacarayan17@gmail.com', 'CDA Region X', 'jmacarayan17@gmail.com', NULL, NULL, '2025-11-04 14:55:00', '2025-11-05 14:55:00', '2025-11-06 14:55:00', 'On November 6, 2025, at approximately 10:30 AM, an issue occurred involving a network connectivity outage affecting multiple users in the IT Division, CDA Central Office. The incident was reported by Juan Dela Cruz through the ICTD Helpdesk System. Initial assessment revealed that the cause was a faulty network switch in the server room. Immediate action was taken by IT personnel Justine Macarayan, who replaced the defective hardware and restored connectivity by 11:15 AM. Operations resumed normally thereafter.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'For Assessment', '2025-11-06 06:56:06', '2025-11-12 08:15:52'),
(22, 'CDA-DBN-2025-16', 'Joseh Mario', 'jmario@gmail.com', 'CDA Region VIII', NULL, NULL, NULL, '2025-11-09 11:37:00', '2025-11-10 11:37:00', '2025-11-11 11:37:00', 'Asfsdgsjbvjbjjvbbvsvbvbsdhusdff', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'For Assessment', '2025-11-11 03:38:17', '2025-11-11 03:38:17'),
(23, 'CDA-DBN-2025-17', 'Mark Doe', 'marktahimik@gmail.com', 'CDA Region XII', NULL, NULL, NULL, '2025-11-09 11:42:00', '2025-11-10 11:42:00', '2025-11-11 11:42:00', 'Nahackasfjgsgbsjgbsbgsdjvsfdb dffvsjghdfiogbsgs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'For Assessment', '2025-11-11 03:43:28', '2025-11-11 03:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `div_id` int(11) NOT NULL,
  `sections_divisions` varchar(255) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`div_id`, `sections_divisions`, `added_at`) VALUES
(1, 'BOD-OC', '0000-00-00 00:00:00'),
(2, 'OFAD', '0000-00-00 00:00:00'),
(3, 'BOD-ASEC.PARADILLO', '0000-00-00 00:00:00'),
(4, 'BOD-ASEC.HILARIO', '0000-00-00 00:00:00'),
(5, 'BOD-ASEC.YRINCO', '0000-00-00 00:00:00'),
(6, 'BOD-ASEC.LAZAGA', '0000-00-00 00:00:00'),
(7, 'BOD-ASEC.GUINOMLA', '0000-00-00 00:00:00'),
(8, 'BOD-ASEC.DISIMBAN', '0000-00-00 00:00:00'),
(9, 'BOD-BOARDSEC', '0000-00-00 00:00:00'),
(10, 'IAD', '0000-00-00 00:00:00'),
(11, 'FINANCE', '0000-00-00 00:00:00'),
(12, 'GASS', '0000-00-00 00:00:00'),
(13, 'PPDD', '0000-00-00 00:00:00'),
(14, 'COA', '0000-00-00 00:00:00'),
(15, 'ICTD/ICTS', '0000-00-00 00:00:00'),
(16, 'RSES', '0000-00-00 00:00:00'),
(17, 'RSES-REG', '0000-00-00 00:00:00'),
(18, 'RSES-SED', '0000-00-00 00:00:00'),
(19, 'LAS-LEGAL', '0000-00-00 00:00:00'),
(20, 'LAS-AJUDICATION', '0000-00-00 00:00:00'),
(21, 'CSFS', '0000-00-00 00:00:00'),
(22, 'CSFS-TAD', '0000-00-00 00:00:00'),
(23, 'CSFS-IED', '0000-00-00 00:00:00'),
(24, 'IDS', '0000-00-00 00:00:00'),
(25, 'IDS-CPDAD', '0000-00-00 00:00:00'),
(26, 'PDRD', '0000-00-00 00:00:00'),
(27, 'IDS-CRITD/CRITS', '0000-00-00 00:00:00'),
(28, 'HRDD', '0000-00-00 00:00:00'),
(29, 'GASS-ADMIN-RECORDS', '0000-00-00 00:00:00'),
(30, 'GASS-ADMIN-CASH', '0000-00-00 00:00:00'),
(31, 'GASS-ADMIN-GSS', '0000-00-00 00:00:00'),
(32, 'GASS-ADMIN-PROCUREMENT', '0000-00-00 00:00:00'),
(33, 'GASS-ADMIN-PROPERTY', '0000-00-00 00:00:00'),
(34, 'GASS-ADMIN-PROPERTY-(MAINTENANCE)', '0000-00-00 00:00:00'),
(35, 'GASS-ADMIN-PROPERTY-(DRIVER)', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `it_personnel`
--

CREATE TABLE `it_personnel` (
  `id` int(11) NOT NULL,
  `firstname` varchar(155) NOT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `lastname` varchar(155) NOT NULL,
  `it_area` varchar(100) NOT NULL,
  `it_email` varchar(155) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `it_personnel`
--

INSERT INTO `it_personnel` (`id`, `firstname`, `middle_initial`, `lastname`, `it_area`, `it_email`, `date_added`, `date_updated`) VALUES
(1, 'RONALDO', ' G.', ' RIVERA', 'CDA HO - ICTD', 'r_rivera@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'BONIFACIO', '', 'GARCIA', 'CDA HO - ICTD', 'b_garcia@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'ROMAINE NINO', '', 'TALUCOD', 'CDA HO - ICTD', 'r_talucod@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'CARLITO', '', 'BUAN', 'CDA HO - ICTD', 'c_buan@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'JOSEPH RAINER ', '', 'ROSARIAL', 'CDA HO - ICTD', 'jr_rosarial@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'JESSE', '', 'AGNES', 'CDA HO - ICTD', 'j_agnes@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'JUSTINE', 'C.', 'MACARAYAN', 'CDA HO - ICTD', 'j_macarayan@cda.gov.ph', '0000-00-00 00:00:00', '2025-08-18 10:59:02'),
(8, 'PERLITA', '', 'SOLIS', 'CDA NCR', 'ncr@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'DENZEL', '', 'COLLADO', 'CDA NCR', 'ncr@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'NOEL', '', 'ROYUPA', 'CDA R1', 'r1@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'MANFRED', '', 'ZULUETA', 'CDA R1', 'r1@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'JUANA MARIE', '', 'TENORIO', 'CDA R2', 'r2@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'RALPH RENDELL', '', 'TOLEDO', 'CDA R3', 'r3@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'LORIELIE', '', 'PAPA', 'CDA R4A', 'r4a@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'CRISTIAN', '', 'DE ADE', 'CDA R4B', 'r4b@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'RONEL', '', 'KAW', 'CDA R4B', 'r4b@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'MAY CELESTINE', '', 'NERY', 'CDA R5', 'r5@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'MARICEL', '', 'REGALADO', 'CDA R5', 'r5@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'JONILYN', '', 'VESTIDAS', 'CDA R6', 'r6@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'JOHN KYLE JOSEPH', '', 'REYES', 'CDA R6', 'r6@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'MARIEFEL', '', 'TAGHOY', 'CDA R7', 'r7@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'VERYAN', '', 'ARTATES', 'CDA R8', 'r8@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'LYNDON BROZ', '', 'TONELETE', 'CDA R8', 'r8@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'RAUL', '', 'ALCORAN, JR.', 'CDA R9', 'r9@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'ESNIBON ', '', 'DASDAS', 'CDA R9', 'r9@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'MA. ISADEL ', '', 'ATRIGENIO', 'CDA R10', 'r10@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'GIAN ERCO', NULL, 'CAYANONG', 'CDA R10', 'r10@cda.gov.ph', '0000-00-00 00:00:00', '2025-08-18 10:52:21'),
(29, 'PARALUMAN ', '', 'SEPULVEDA', 'CDA R11', 'r11@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'MAE ANGELIE ', '', 'ABADIEZ', 'CDA R11', 'r11@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'RICEL MAE ', '', 'MARTE', 'CDA R12', 'r12@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'MICHAEL ', '', 'DIMAPUNONG, JR.', 'CDA R12', 'r12@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'SHEMA MICHAL ', '', 'LUBRIO', 'CDA R13', 'r13@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'JOHN CEDRICK ', '', 'BERMEJO', 'CDA R14', 'r13@cda.gov.ph', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'MARK ERICK', NULL, 'MENZI', 'CDA CAR', 'car@cda.gov.ph', '0000-00-00 00:00:00', '2025-08-18 12:12:51'),
(38, 'HR ', NULL, 'Division', 'CDA HO - HRDD', 'hr@cda.gov.ph', '2025-07-02 10:35:42', '2025-07-02 10:35:42'),
(40, 'CRITD ', NULL, 'Division', 'CDA HO - CRITD', 'critd@cda.gov.ph', '2025-07-02 10:38:56', '2025-07-02 10:38:56'),
(41, 'CPDAD ', NULL, 'Division', 'CDA HO - CPDAD', 'cpdad@cda.gov.ph', '2025-07-02 10:38:56', '2025-07-02 10:38:56'),
(42, 'Registration ', NULL, 'Division', 'CDA HO - Registration', 'registration@cda.gov.ph', '2025-07-02 10:38:56', '2025-07-02 10:38:56'),
(43, 'SED ', NULL, 'Division', 'CDA HO - SED', 'sed@cda.gov.ph', '2025-07-02 10:38:56', '2025-07-02 10:38:56'),
(44, 'Legal ', NULL, 'Division', 'CDA HO - Legal', 'legal@cda.gov.ph', '2025-07-02 10:38:56', '2025-07-02 10:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(39, '0001_01_01_000000_create_users_table', 1),
(68, '0001_01_01_000001_create_cache_table', 2),
(69, '0001_01_01_000002_create_jobs_table', 2),
(70, '2025_05_28_081948_create_active_directory_table', 2),
(71, '2025_05_28_081948_create_divisions_table', 2),
(72, '2025_05_28_081948_create_it_personnel_table', 2),
(73, '2025_05_28_081948_create_region_email_table', 2),
(74, '2025_05_28_081948_create_technical_services_table', 2),
(75, '2025_05_28_081948_create_tickets_assignment_table', 2),
(76, '2025_05_28_081948_create_tickets_table', 2),
(77, '2025_08_20_015824_create_permission_tables', 2),
(78, '2025_09_11_073535_add_google_id_to_users_table', 2),
(79, '2025_10_21_084658_create_security_incident_reports_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_dashboard', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(2, 'view_profile', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(3, 'edit_profile', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(4, 'update_password', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(5, 'delete_profile', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(6, 'view_all_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(7, 'create_ticket', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(8, 'reassign_ticket', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(9, 'update_status_ticket', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(10, 'delete_ticket', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(11, 'search_ticket', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(12, 'generate_tsar', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(13, 'generate_report', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(14, 'view_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(15, 'create_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(16, 'reassign_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(17, 'update_status_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(18, 'delete_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(19, 'search_myrequested_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(20, 'view_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(21, 'create_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(22, 'reassign_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(23, 'update_status_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(24, 'delete_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(25, 'search_assignedtome_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(26, 'view_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(27, 'create_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(28, 'reassign_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(29, 'update_status_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(30, 'delete_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(31, 'search_reassigned_tickets', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(32, 'view_all_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(33, 'view_overview_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(34, 'create_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(35, 'view_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(36, 'edit_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(37, 'delete_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(38, 'search_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(39, 'filter_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(40, 'generate_docs_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(41, 'print_databreach', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(42, 'view_dbrt', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(43, 'create_dbrt', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(44, 'edit_dbrt', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(45, 'delete_dbrt', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(46, 'view_technical_personnel', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(47, 'create_technical_personnel', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(48, 'edit_technical_personnel', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(49, 'delete_technical_personnel', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(50, 'search_technical_personnel', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(51, 'view_technical_services', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(52, 'create_technical_services', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(53, 'edit_technical_services', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(54, 'delete_technical_services', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(55, 'search_technical_services', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(56, 'view_tech_users', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(57, 'create_tech_users', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(58, 'edit_tech_users', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(59, 'delete_tech_users', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(60, 'search_tech_users', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(61, 'view_roles', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(62, 'create_roles', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(63, 'edit_roles', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(64, 'delete_roles', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(65, 'search_roles', 'web', '2025-11-13 11:18:30', '2025-11-13 11:18:30'),
(66, 'assess_databreach', 'web', '2025-11-13 16:10:29', '2025-11-13 16:10:29'),
(67, 'evaluate_databreach', 'web', '2025-11-13 16:10:29', '2025-11-13 16:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `region_email`
--

CREATE TABLE `region_email` (
  `area_id` int(11) NOT NULL,
  `region` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `region_email`
--

INSERT INTO `region_email` (`area_id`, `region`, `email`, `added_at`, `date_updated`) VALUES
(1, 'CDA HO', 'ictd@cda.gov.ph', '2024-12-12 09:09:40', '2024-12-17 20:27:21'),
(2, 'CDA NCR', 'ncr@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(3, 'CDA R1', 'r1@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(4, 'CDA R2', 'r2@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(5, 'CDA R3', 'r3@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(6, 'CDA R4A', 'r4a@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(7, 'CDA R4B', 'r4b@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(8, 'CDA R5', 'r5@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(9, 'CDA R6', 'r6@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(10, 'CDA R7', 'r7@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(11, 'CDA R8', 'r8@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(12, 'CDA R9', 'r9@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(13, 'CDA R10', 'r10@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(14, 'CDA R11', 'r11@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(15, 'CDA R12', 'r12@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(16, 'CDA R13', 'r13@cda.gov.ph', '2024-12-17 09:09:40', '2024-12-17 20:27:21'),
(17, 'CDA CAR', 'car@cda.gov.ph', '2024-12-19 08:18:00', '2024-12-19 08:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-08-26 19:40:36', '2025-08-26 19:40:36'),
(2, 'Admin', 'web', '2025-11-13 14:46:07', '2025-11-13 14:46:07'),
(3, 'DPO', 'web', '2025-11-13 14:48:55', '2025-11-13 14:48:55'),
(4, 'DBRT', 'web', '2025-11-13 14:52:38', '2025-11-13 14:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 9),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 9),
(3, 1),
(3, 9),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 9),
(5, 1),
(5, 9),
(6, 1),
(6, 9),
(7, 1),
(7, 9),
(8, 1),
(8, 9),
(9, 1),
(9, 9),
(10, 1),
(10, 9),
(11, 1),
(11, 9),
(12, 1),
(12, 2),
(12, 9),
(13, 1),
(13, 2),
(13, 9),
(14, 1),
(14, 2),
(14, 9),
(15, 1),
(15, 2),
(15, 9),
(16, 1),
(16, 2),
(16, 9),
(17, 1),
(17, 2),
(17, 9),
(18, 1),
(18, 9),
(19, 1),
(19, 2),
(19, 9),
(20, 1),
(20, 2),
(20, 9),
(21, 1),
(21, 2),
(21, 9),
(22, 1),
(22, 2),
(22, 9),
(23, 1),
(23, 2),
(23, 9),
(24, 1),
(24, 9),
(25, 1),
(25, 2),
(25, 9),
(26, 1),
(26, 9),
(27, 1),
(27, 9),
(28, 1),
(28, 9),
(29, 1),
(29, 9),
(30, 1),
(30, 9),
(31, 1),
(31, 9),
(32, 1),
(32, 3),
(32, 4),
(32, 9),
(33, 1),
(33, 3),
(33, 4),
(33, 9),
(34, 1),
(34, 3),
(34, 4),
(34, 9),
(35, 1),
(35, 3),
(35, 4),
(35, 9),
(36, 1),
(36, 4),
(36, 9),
(37, 9),
(38, 1),
(38, 3),
(38, 4),
(38, 9),
(39, 1),
(39, 3),
(39, 4),
(39, 9),
(40, 1),
(40, 3),
(40, 9),
(41, 1),
(41, 3),
(41, 9),
(42, 1),
(42, 3),
(42, 4),
(42, 9),
(43, 1),
(43, 3),
(43, 4),
(43, 9),
(44, 1),
(44, 4),
(44, 9),
(45, 1),
(45, 9),
(46, 1),
(46, 2),
(46, 9),
(47, 1),
(47, 9),
(48, 1),
(48, 9),
(49, 1),
(49, 9),
(50, 1),
(50, 2),
(50, 9),
(51, 1),
(51, 2),
(51, 9),
(52, 1),
(52, 9),
(53, 1),
(53, 9),
(54, 1),
(54, 9),
(55, 1),
(55, 2),
(55, 9),
(56, 1),
(56, 2),
(56, 9),
(57, 1),
(57, 9),
(58, 1),
(58, 9),
(59, 1),
(59, 9),
(60, 1),
(60, 2),
(60, 9),
(61, 1),
(61, 9),
(62, 1),
(62, 9),
(63, 1),
(63, 9),
(64, 1),
(64, 9),
(65, 1),
(65, 9),
(66, 4),
(67, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('88AaAt1TerWalWvCAcxsoJRt5iO9AejIUxXwuoLO', 4, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnkwSDFOUDZXUDZBRUNqNHZNOFhzdkhnMm15STFuZkdacEJOcHBTTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ub3RpZmljYXRpb25zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1763106822),
('plap9wzTwXubYm5JjzfQkSnwi2ArCq2J9dDzvQCs', 3, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSUJIQ2lrazN6dHJOcjlhOEVidEFVeHVqMEdwaVh0SGR3YlpYMkZwVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ub3RpZmljYXRpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1763106822),
('qykiY6P9zg5wMFoY7VeSO3NntSanyjTQtRIdGJ8y', 1, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT3Bwb1Jla2lMNzRlbUhxV0NRc2ZtWTRSOFlsMVdzNm40b0ZuYk44aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ub3RpZmljYXRpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1763106822);

-- --------------------------------------------------------

--
-- Table structure for table `technical_services`
--

CREATE TABLE `technical_services` (
  `id` int(11) NOT NULL,
  `technical_services` varchar(255) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `technical_services`
--

INSERT INTO `technical_services` (`id`, `technical_services`, `added_at`, `updated_at`) VALUES
(1, 'Hardware Troubleshooting', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(2, 'Software Installation/ Repair', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(3, 'Virus and Malware Removals', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(4, 'Network Internet Connectivity', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(5, 'Online Meeting Assistance', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(6, 'Issues with Email', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(7, 'Issues with Database', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(8, 'Issue with website', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(9, 'Active Directory Registration', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(10, 'Domain Account Reset Password', '0000-00-00 00:00:00', '2025-08-18 14:31:32'),
(11, 'Not listed', '0000-00-00 00:00:00', '2025-08-18 15:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `firstname` varchar(150) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `division` varchar(100) NOT NULL,
  `it_area` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `device` varchar(50) NOT NULL,
  `service` longtext NOT NULL,
  `request` longtext NOT NULL,
  `action_taken` longtext DEFAULT NULL,
  `photo` blob DEFAULT NULL,
  `it_personnel` varchar(155) NOT NULL,
  `it_email` varchar(50) NOT NULL,
  `date_resolved` datetime DEFAULT current_timestamp(),
  `client_signature` blob DEFAULT NULL,
  `personnel_signature` blob DEFAULT NULL,
  `assigned_to` varchar(100) DEFAULT NULL,
  `assigned_it_email` varchar(100) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `ticket_number`, `firstname`, `lastname`, `status`, `date_created`, `division`, `it_area`, `email`, `device`, `service`, `request`, `action_taken`, `photo`, `it_personnel`, `it_email`, `date_resolved`, `client_signature`, `personnel_signature`, `assigned_to`, `assigned_it_email`, `notes`, `is_read`) VALUES
(1, '2ZXV3M', 'John', 'Doe', 'Resolved', '2025-09-05 16:32:42', 'BOD-OC', 'CDA HO - ICTD', 'jmacarayan17@gmail.com', 'Laptop/Netbook PC', 'Software Installation/ Repair', 'Install Sophos Antivirus', 'Successfully installed the antivirus software.', NULL, 'JUSTINE C. MACARAYAN', 'j_macarayan@cda.gov.ph', '2025-09-05 16:57:18', NULL, 0x706572736f6e6e656c5f7369676e61747572652f5252734655416d6c464b7178554e514b32373155773371536e6a78705749736c32796853584435512e6a7067, NULL, NULL, NULL, 0),
(2, 'YWS7OG', 'John', 'Doe', 'Resolved', '2025-09-09 15:02:12', 'BOD-OC', 'CDA HO - ICTD', 'jmacarayan17@gmail.com', 'Printer Only', 'Hardware Troubleshooting', 'Printer connection error', 'This is done.', NULL, 'JUSTINE C. MACARAYAN', 'j_macarayan@cda.gov.ph', '2025-09-09 16:03:36', NULL, NULL, NULL, NULL, NULL, 0),
(3, 'TP3NCN', 'John', 'Doe', 'Resolved', '2025-09-09 16:51:04', 'BOD-OC', 'CDA HO - ICTD', 'jmacarayan17@gmail.com', 'Scanner Only', 'Hardware Troubleshooting', 'Scanner connection error', 'Configure connection via IP address', NULL, 'JESSE  AGNES', 'j_agnes@cda.gov.ph', '2025-09-10 10:05:13', 0x636c69656e745f7369676e61747572652f7a6d5a6d3663566848304e7a52553254556a7738386c326f504745737566757662457852696647672e706e67, NULL, 'JESSE  AGNES', 'j_agnes@cda.gov.ph', NULL, 0),
(4, 'A7YZMI', 'Juan', 'Dela Cruz', 'Pending', '2025-09-10 11:13:32', 'BOD-BOARDSEC', 'CDA HO - ICTD', 'j_delacruz@cda.gov.ph', 'Scanner Only', 'Hardware Troubleshooting', 'Vertical line on scanned document', NULL, NULL, 'JUSTINE C. MACARAYAN', 'j_macarayan@cda.gov.ph', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 'HBCPC4', 'Juan', 'Dela Cruz', 'Pending', '2025-09-10 11:17:40', 'BOD-BOARDSEC', 'CDA HO - ICTD', 'j_delacruz@cda.gov.ph', 'Scanner Only', 'Hardware Troubleshooting', 'Vertical line on scanned document', NULL, NULL, 'JUSTINE C. MACARAYAN', 'j_macarayan@cda.gov.ph', NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tickets_assignment`
--

CREATE TABLE `tickets_assignment` (
  `id` int(11) NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `request` longtext NOT NULL,
  `assigned_by` varchar(100) NOT NULL,
  `assigned_to` varchar(100) NOT NULL,
  `notes` longtext DEFAULT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ICTD Admin', 'ictd@cda.gov.ph', NULL, '$2y$12$XM1IpWfpUwdcLIY8NyQs5eHtn2vQNfSrH4krEUZJ6Jdp97Lsrr51G', '1', 'enZr2GlxfpC5V8yrWbkiabO2l0wO0I8FMgmSmxPWMN7NYn2x7UT0ctunb5BV', '2025-08-27 04:22:48', '2025-11-13 21:31:11'),
(2, 'Justine C. Macarayan', 'j_macarayan@cda.gov.ph', NULL, '$2y$12$jWLIVfTQfzGIHB8gzIQT4O.HfkKCtqWzes6.Q9KTIi15e3n8E1Ska', '2', NULL, '2025-11-13 22:57:49', '2025-11-13 14:57:49'),
(3, 'Legal Affairs Service CDA', 'legal@cda.gov.ph', NULL, '$2y$12$29RNXaNDJiXhyiRyW5Mid.TaaK94FPyTRBZi9k0Niy6FlXCAN586S', '3', NULL, '2025-11-13 23:01:27', '2025-11-13 15:01:27'),
(4, 'John A. Doe', 'jmacarayan17@gmail.com', NULL, '$2y$12$hGLTy9PV8gGFOGwTmkqUs.5GdHgY0WKaWm10KfZAgRS0ZlN.M.FQi', '4', 'cUKfdxiOQflvl1TxUhQBfEQd4SfhKjJ3wYlD7NiL1C2d92fZi172RuK3XVCR', '2025-11-13 23:03:10', '2025-11-14 01:13:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_directory`
--
ALTER TABLE `active_directory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `databreach_dbrt_team`
--
ALTER TABLE `databreach_dbrt_team`
  ADD PRIMARY KEY (`dbrt_id`);

--
-- Indexes for table `databreach_for_assessment`
--
ALTER TABLE `databreach_for_assessment`
  ADD PRIMARY KEY (`dbn_id`);

--
-- Indexes for table `databreach_notifications`
--
ALTER TABLE `databreach_notifications`
  ADD PRIMARY KEY (`dbn_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`div_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `it_personnel`
--
ALTER TABLE `it_personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `region_email`
--
ALTER TABLE `region_email`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `technical_services`
--
ALTER TABLE `technical_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tickets_assignment`
--
ALTER TABLE `tickets_assignment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_directory`
--
ALTER TABLE `active_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `databreach_dbrt_team`
--
ALTER TABLE `databreach_dbrt_team`
  MODIFY `dbrt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `databreach_for_assessment`
--
ALTER TABLE `databreach_for_assessment`
  MODIFY `dbn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `databreach_notifications`
--
ALTER TABLE `databreach_notifications`
  MODIFY `dbn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `div_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `it_personnel`
--
ALTER TABLE `it_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `region_email`
--
ALTER TABLE `region_email`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `technical_services`
--
ALTER TABLE `technical_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets_assignment`
--
ALTER TABLE `tickets_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
