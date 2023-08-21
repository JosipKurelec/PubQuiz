-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2023 at 02:41 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `1436214`
--

-- --------------------------------------------------------

--
-- Table structure for table `editionofquiz`
--

CREATE TABLE `editionofquiz` (
  `id` int(11) NOT NULL,
  `NameOfEdition` varchar(255) NOT NULL,
  `QuizId` int(11) NOT NULL,
  `TimeOfQuiz` time NOT NULL,
  `DateOfQuiz` date NOT NULL,
  `ThemeOfQuiz` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Elo` int(11) NOT NULL,
  `RegFee` float NOT NULL,
  `Awards` varchar(255) NOT NULL,
  `MaxPlayer` int(11) NOT NULL,
  `MaxTeams` int(11) NOT NULL,
  `QuestionShare` int(11) NOT NULL,
  `nQuestions` int(11) NOT NULL,
  `Rated` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `editionofquiz`
--

INSERT INTO `editionofquiz` (`id`, `NameOfEdition`, `QuizId`, `TimeOfQuiz`, `DateOfQuiz`, `ThemeOfQuiz`, `Location`, `Elo`, `RegFee`, `Awards`, `MaxPlayer`, `MaxTeams`, `QuestionShare`, `nQuestions`, `Rated`) VALUES
(36, '2 #1', 10, '00:00:00', '2023-08-12', '1', 'das', 1000, 1, 'das|asd|asd', 1, 1, 3, 0, 0),
(187, 'Prvi pubquiz #1', 9, '20:00:00', '2023-08-16', '1', 'Savska 81,Sesvete', 1333, 2.5, 'Pelin|6-Pack|Runda', 3, 15, 2, 0, 0),
(37, '3 #1', 11, '20:18:46', '2023-08-11', '3', '3213', 1000, 1, 'dsa|asd|asd', 2, 1, 1, 0, 0),
(39, '4 #1', 18, '20:00:00', '2023-08-30', '1', 'asdass', 1000, 2, 'asd|dsa|sad', 3, 8, 2, 0, 0),
(188, 'Prvi pubquiz #2', 9, '20:00:00', '2023-08-17', '1', 'Savska 81,Sesvete', 1090, 3.5, 'Pelin|6-pack|Runda', 4, 20, 2, 0, 0),
(189, 'Prvi pubquiz #3', 9, '20:00:00', '2023-08-17', '1', 'Savska 81,Sesvete', 1000, 3.5, 'Pelin|6-pack|Runda', 4, 20, 2, 0, 0),
(190, 'Prvi pubquiz #4', 9, '20:00:00', '2023-08-19', '1', 'Savska 81,Sesvete', 1000, 3.5, 'Pelin|6-pack|Runda', 4, 20, 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `Id` int(11) NOT NULL,
  `TeamId` int(11) NOT NULL,
  `EditionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `joinrequests`
--

CREATE TABLE `joinrequests` (
  `id` int(11) NOT NULL,
  `FromId` int(11) NOT NULL,
  `ToId` varchar(255) NOT NULL,
  `EditionId` int(11) NOT NULL,
  `ReplyResult` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `joinrequests`
--

INSERT INTO `joinrequests` (`id`, `FromId`, `ToId`, `EditionId`, `ReplyResult`) VALUES
(8, 6, 'kuki4', 187, 1),
(7, 6, 'kuki3', 187, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `EditionId` int(11) NOT NULL,
  `Round` int(11) NOT NULL,
  `Question` text NOT NULL,
  `Answer` text NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Elo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `EditionId`, `Round`, `Question`, `Answer`, `Image`, `Elo`) VALUES
(12, 187, 3, 'asdas', 'asdas', '1691611362_c9bb9b55c1637d62.jpg', 1335),
(11, 187, 2, 'asdas', 'asdas', '', 1338),
(10, 187, 2, 'asdas', 'sdas', '1691611362_ebe585dcb9be0738.png', 1339),
(9, 187, 1, 'asdas', 'asda', '', 1353),
(8, 187, 1, 'asdas', 'asda', '1691611362_ac014e110f4cf609.png', 1354),
(13, 187, 3, 'sadasd', 'asdasd', '', 1334),
(14, 187, 1, 'sadasd', 'sdasd', '1691745835_bd80c431f06db582.png', 1352),
(15, 187, 1, 'dsasda', 'dasddasdas', '1691745907_fbfe1d367f038d63.png', 1351),
(16, 187, 1, 'dsddas', 'asdsad', '', 1350),
(17, 187, 1, 'asdas', 'asdasd', '1691746328_cf158d046c0eb0b2.png', 1349),
(18, 187, 1, 'dsadasd', 'asdasd', '1691746504_e8c0264e0dd500aa.ico', 1348),
(19, 187, 3, 'sdad', 'asdas', '', 1333),
(20, 187, 2, 'dasd', 'asdasd', '1691746702_a2bd77965786e4ae.jpg', 1337),
(21, 187, 1, 'dsad', 'asdas', '', 1347),
(22, 187, 2, 'sadas', 'asdas', '1691746890_11d093a91d1dfa16.png', 1336),
(23, 187, 1, 'dasd', 'asdas', '1691746929_d4aa4b9fc917dd91.png', 1346),
(24, 187, 1, 'das', 'sad', '', 1345),
(25, 187, 1, 'sAS', 'DASD', '', 1344),
(26, 187, 1, 'dasdasd', 'asdasd', '', 1343),
(27, 187, 1, 'FDS', 'FA', '', 1342),
(28, 187, 1, 'das', 'asd', '', 1341),
(29, 188, 1, 'dsadasd', 'asdasd', '1692356271_8f953ba8aeff986c.png', 1030),
(30, 188, 1, 'dsad', 'dasda', '1692356271_3cc86c089cdf8e2c.jpg', 1030),
(31, 188, 2, 'dsadsas', 'dsada', '', 1030),
(32, 189, 2, 'adsdas', 'asdasd', '', 1000),
(33, 189, 3, 'adsda', 'dsadas', '', 1000),
(34, 190, 1, 'sdas', 'dasda', '', 1000),
(35, 190, 3, 'adas', 'asdas', '', 1000),
(36, 190, 1, 'dsa', 'kaj', '', 1000),
(37, 190, 1, 'kaj', 'zmajj', '1692381404_3f205d831fed756d.png', 1000),
(38, 190, 3, 'zmaj', 'kaj', '', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `questionmeta`
--

CREATE TABLE `questionmeta` (
  `username` varchar(255) NOT NULL,
  `questionId` int(11) NOT NULL,
  `result` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questionmeta`
--

INSERT INTO `questionmeta` (`username`, `questionId`, `result`) VALUES
('kuki3', 8, 1),
('kuki3', 9, 1),
('kuki3', 14, 1),
('kuki3', 15, 1),
('kuki3', 16, 1),
('kuki3', 17, 1),
('kuki3', 18, 1),
('kuki3', 21, 1),
('kuki3', 23, 1),
('kuki3', 24, 1),
('kuki3', 25, 1),
('kuki3', 26, 1),
('kuki3', 27, 1),
('kuki3', 28, 1),
('kuki3', 10, 0),
('kuki3', 11, 1),
('kuki3', 20, 1),
('kuki3', 22, 1),
('kuki3', 12, 1),
('kuki3', 13, 0),
('kuki3', 19, 0),
('kuki4', 8, 1),
('kuki4', 9, 1),
('kuki4', 14, 1),
('kuki4', 15, 1),
('kuki4', 16, 1),
('kuki4', 17, 1),
('kuki4', 18, 1),
('kuki4', 21, 1),
('kuki4', 23, 1),
('kuki4', 24, 1),
('kuki4', 25, 1),
('kuki4', 26, 1),
('kuki4', 27, 1),
('kuki4', 28, 1),
('kuki4', 10, 0),
('kuki4', 11, 1),
('kuki4', 20, 1),
('kuki4', 22, 1),
('kuki4', 12, 1),
('kuki4', 13, 0),
('kuki4', 19, 0),
('kuki2', 8, 1),
('kuki2', 9, 1),
('kuki2', 14, 1),
('kuki2', 15, 1),
('kuki2', 16, 1),
('kuki2', 17, 1),
('kuki2', 18, 1),
('kuki2', 21, 1),
('kuki2', 23, 1),
('kuki2', 24, 1),
('kuki2', 25, 1),
('kuki2', 26, 1),
('kuki2', 27, 1),
('kuki2', 28, 1),
('kuki2', 10, 1),
('kuki2', 11, 1),
('kuki2', 20, 1),
('kuki2', 22, 1),
('kuki2', 12, 1),
('kuki2', 13, 1),
('kuki2', 19, 1),
('kuki1', 8, 1),
('kuki1', 9, 1),
('kuki1', 14, 1),
('kuki1', 15, 1),
('kuki1', 16, 1),
('kuki1', 17, 1),
('kuki1', 18, 1),
('kuki1', 21, 1),
('kuki1', 23, 1),
('kuki1', 24, 1),
('kuki1', 25, 1),
('kuki1', 26, 1),
('kuki1', 27, 1),
('kuki1', 28, 1),
('kuki1', 10, 1),
('kuki1', 11, 1),
('kuki1', 20, 1),
('kuki1', 22, 1),
('kuki1', 12, 1),
('kuki1', 13, 1),
('kuki1', 19, 1),
('kuki', 8, 1),
('kuki', 9, 1),
('kuki', 14, 1),
('kuki', 15, 1),
('kuki', 16, 1),
('kuki', 17, 1),
('kuki', 18, 1),
('kuki', 21, 1),
('kuki', 23, 1),
('kuki', 24, 1),
('kuki', 25, 1),
('kuki', 26, 1),
('kuki', 27, 1),
('kuki', 28, 1),
('kuki', 10, 1),
('kuki', 11, 1),
('kuki', 20, 1),
('kuki', 22, 1),
('kuki', 12, 1),
('kuki', 13, 1),
('kuki', 19, 1),
('kuki3', 8, 1),
('kuki3', 9, 1),
('kuki3', 14, 1),
('kuki3', 15, 1),
('kuki3', 16, 1),
('kuki3', 17, 1),
('kuki3', 18, 1),
('kuki3', 21, 1),
('kuki3', 23, 1),
('kuki3', 24, 1),
('kuki3', 25, 1),
('kuki3', 26, 1),
('kuki3', 27, 1),
('kuki3', 28, 1),
('kuki3', 10, 1),
('kuki3', 11, 1),
('kuki3', 20, 1),
('kuki3', 22, 1),
('kuki3', 12, 1),
('kuki3', 13, 1),
('kuki3', 19, 1),
('kuki4', 8, 1),
('kuki4', 9, 1),
('kuki4', 14, 1),
('kuki4', 15, 1),
('kuki4', 16, 1),
('kuki4', 17, 1),
('kuki4', 18, 1),
('kuki4', 21, 1),
('kuki4', 23, 1),
('kuki4', 24, 1),
('kuki4', 25, 1),
('kuki4', 26, 1),
('kuki4', 27, 1),
('kuki4', 28, 1),
('kuki4', 10, 1),
('kuki4', 11, 1),
('kuki4', 20, 1),
('kuki4', 22, 1),
('kuki4', 12, 1),
('kuki4', 13, 1),
('kuki4', 19, 1),
('kuki2', 8, 1),
('kuki2', 9, 1),
('kuki2', 14, 1),
('kuki2', 15, 1),
('kuki2', 16, 1),
('kuki2', 17, 1),
('kuki2', 18, 1),
('kuki2', 21, 1),
('kuki2', 23, 1),
('kuki2', 24, 1),
('kuki2', 25, 1),
('kuki2', 26, 1),
('kuki2', 27, 1),
('kuki2', 28, 1),
('kuki2', 10, 1),
('kuki2', 11, 1),
('kuki2', 20, 1),
('kuki2', 22, 1),
('kuki2', 12, 1),
('kuki2', 13, 1),
('kuki2', 19, 1),
('kuki', 29, 0),
('kuki', 30, 0),
('kuki', 31, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `NameOfQuiz` varchar(255) NOT NULL,
  `ThemeOfQuiz` int(11) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Elo` int(11) NOT NULL,
  `RegFee` float NOT NULL,
  `TimeOfQuiz` time NOT NULL,
  `Awards` varchar(255) NOT NULL,
  `MaxPlayer` int(11) NOT NULL,
  `MaxTeams` int(11) NOT NULL,
  `QuestionShare` int(11) NOT NULL,
  `Picture` varchar(255) NOT NULL,
  `Color` varchar(255) NOT NULL,
  `finished` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `NameOfQuiz`, `ThemeOfQuiz`, `Location`, `Elo`, `RegFee`, `TimeOfQuiz`, `Awards`, `MaxPlayer`, `MaxTeams`, `QuestionShare`, `Picture`, `Color`, `finished`) VALUES
(9, 'Prvi pubquiz', 1, 'Savska 81,Sesvete', 1423, 3.5, '20:00:00', 'Pelin|6-pack|Runda', 4, 20, 2, 'pPictures/qlogo.jpg', '#4b3daf', 1),
(10, '2', 1, 'das', 1000, 1, '00:00:00', 'das|asd|asd', 1, 1, 3, 'pPictures/defaultpicture.jpg', '#1dcb81', 1),
(11, '3', 3, '3213', 1000, 1, '00:00:00', 'dsa|asd|asd', 1, 1, 1, 'pPictures/defaultpicture.jpg', '#a76d27', 1),
(18, '4', 1, 'asdass', 1000, 3, '20:00:00', 'asd|dsa|sad', 3, 8, 2, 'pPictures/1691850907_91042f98c199.jpg', '#e93ae9', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teamonquiz`
--

CREATE TABLE `teamonquiz` (
  `id` int(11) NOT NULL,
  `quizId` varchar(255) NOT NULL,
  `teamId` varchar(255) NOT NULL,
  `answers` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teamonquiz`
--

INSERT INTO `teamonquiz` (`id`, `quizId`, `teamId`, `answers`) VALUES
(1, '187', '6', '111101111011110111111'),
(10, '187', '18', '111111111111111111111'),
(11, '187', '21', '101100011011000001110'),
(12, '188', '6', '000'),
(13, '190', '6', '');

-- --------------------------------------------------------

--
-- Table structure for table `teamplayers`
--

CREATE TABLE `teamplayers` (
  `id` int(11) NOT NULL,
  `one` varchar(255) NOT NULL,
  `two` varchar(255) DEFAULT NULL,
  `three` varchar(255) DEFAULT NULL,
  `four` varchar(255) DEFAULT NULL,
  `five` varchar(255) DEFAULT NULL,
  `Elo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teamplayers`
--

INSERT INTO `teamplayers` (`id`, `one`, `two`, `three`, `four`, `five`, `Elo`) VALUES
(1, 'kuki', 'kuki3', 'kuki4', NULL, NULL, 1830),
(10, 'kuki1', NULL, NULL, NULL, NULL, 1815),
(11, 'kuki2', NULL, NULL, NULL, NULL, 1825),
(12, 'kuki', NULL, NULL, NULL, NULL, 1769),
(13, 'kuki', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `NameOfTeam` varchar(255) NOT NULL,
  `Elo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `NameOfTeam`, `Elo`) VALUES
(18, 'Zadnji ako Bog da', 1815),
(6, 'Predzadnji ako Bog da', 1785),
(25, 'baba3', 1000),
(21, 'baba', 1825),
(24, 'baba2', 1000),
(23, 'baba1', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`id`, `Name`) VALUES
(1, 'General knowledge'),
(2, 'Sports'),
(3, 'Music'),
(4, 'Movies'),
(5, 'Mixed');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Level` int(11) NOT NULL,
  `OwnerOf` int(11) NOT NULL,
  `Elo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `username`, `Password`, `Level`, `OwnerOf`, `Elo`) VALUES
('jkurelec1@tvz.hr', 'kuki1', '$2y$10$rXQInyauTjjZ2NEVo7X8yeZvpHRcYS4xMMX7luVd9gjOirg.j7ZZC', 0, 18, 1815),
('jkurelec2@tvz.hr', 'kuki2', '$2y$10$fvAJkLAPK0oeei92nsU.XePGP1JahMqC2y7/QyY9T3HZT.GWbwLRS', 0, 21, 1825),
('jkurelec3@tvz.hr', 'kuki3', '$2y$10$8hgQuykOFSqa1Hym5hPlUOWlqNT9Cdl/c2IJbxUcioH54ecdq7YbG', 0, 23, 1814),
('jkurelec4@tvz.hr', 'kuki4', '$2y$10$3kUB.dSTj/P6fsAdWUTwXOZG2CIAxaBD.WLPI9ONHJEOvMLQi0OwG', 0, 24, 1814),
('jkurelec5@tvz.hr', 'kuki5', '$2y$10$oimO6ILrLu0vc4RPwfcO8eyc1aKxIH4QC0p7XckYUzFzAGPFYU4CG', 0, 25, 1000),
('jkurelec@tvz.hr', 'kuki', '$2y$10$aL54sOA5PrqIXzExYVaSzuoXlOOw6smm0u9Lajycetbqx449ZwRay', 0, 6, 1769),
('Kurelec81@gmail.com', 'das', '$2y$10$uIsxAz0NGTQWqR5GKp7iIeaWTKaDzyg0MFvJviIGfo0/vTFoN0vDW', 1, 9, 0),
('kurelec82@gmail.com', 'sad', '$2y$10$LpZjFOoHr1YJUGmmT8Yp3epkZGXBcO6SaZHRTy38jWH4tbl/dYQW2', 1, 10, 0),
('kurelec83@gmail.com', 'asd', '$2y$10$ezNIQ9qM7HK3g.iBy9siDea143nB2ZUxPG6h2MzYkMPTr8RSUJTbu', 1, 11, 0),
('kurelec84@gmail.com', 'nah', '$2y$10$BgddBklUdeLEe/YqyuNxTe58rYq/tm69CAoLDodTgQuw/9OF/.gzK', 1, 18, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `editionofquiz`
--
ALTER TABLE `editionofquiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `joinrequests`
--
ALTER TABLE `joinrequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teamonquiz`
--
ALTER TABLE `teamonquiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teamplayers`
--
ALTER TABLE `teamplayers`
  ADD KEY `fk_teamonquiz` (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `editionofquiz`
--
ALTER TABLE `editionofquiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `invites`
--
ALTER TABLE `invites`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `joinrequests`
--
ALTER TABLE `joinrequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `teamonquiz`
--
ALTER TABLE `teamonquiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teamplayers`
--
ALTER TABLE `teamplayers`
  ADD CONSTRAINT `fk_teamonquiz` FOREIGN KEY (`id`) REFERENCES `teamonquiz` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
