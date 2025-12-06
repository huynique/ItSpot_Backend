-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Nov 2017 um 17:01
-- Server-Version: 10.1.16-MariaDB
-- PHP-Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `user` (
  `id` varchar(36) NOT NULL,
  `username` varchar(8) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `task` (
  `id` varchar(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `projectId` varchar(36) NOT NULL,
  `priorityId` tinyint(2) NOT NULL,
  `title` varchar(300) NOT NULL,
  `expense` decimal(4,2) NULL,
  `done` boolean NOT NULL DEFAULT 0,
  `dueDate` DATE,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `priority` (
  `id` tinyint(2) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `project` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `team` (
  `userId` varchar(36) NOT NULL,
  `projectId` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `priority`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD CONSTRAINT FK_task_priority FOREIGN KEY (`priorityId`) REFERENCES priority(`id`),
  ADD CONSTRAINT FK_taks_project FOREIGN KEY (`projectId`) REFERENCES project(`id`),
  ADD CONSTRAINT FK_task_user FOREIGN KEY (`userId`) REFERENCES user(`id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`userId`, `projectId`),
  ADD CONSTRAINT FK_team_project FOREIGN KEY (`projectId`) REFERENCES project(`id`),
  ADD CONSTRAINT FK_team_user FOREIGN KEY (`userId`) REFERENCES user(`id`);

INSERT INTO `priority` VALUES (1, 'Hoch')
, (2, 'Mittel')
, (3, 'Niedrig');

INSERT INTO `project` VALUES 
  ('8f8fc90d-3715-11eb-add7-2c4d544f8fe0', 'B&uuml;roarbeit')
  , ('72f7cc6e-3717-11eb-add7-2c4d544f8fe0', 'Meeting')
  , ('9712b5b9-3715-11eb-add7-2c4d544f8fe0', 'Antrieb')
  , ('3ab64434-3717-11eb-add7-2c4d544f8fe0', 'Lebenserhaltung')
  , ('a10b8c84-3715-11eb-add7-2c4d544f8fe0', 'Boardsystem');

INSERT INTO `user` (`id`, `username`, `firstname`, `lastname`, `pw`, `created`) VALUES
  ('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', 'stefan', 'Stefan', 'Stift', '$2y$10$IeMpvUuMIrnxFHN.j94tEe.T1rjsTga1yYoyt5JAAXYUwbbjh1km6', '2020-11-26 09:13:20')
  , ('4f141df7-3c0a-11e8-b046-2c4d544f8fe0', 'fiona', 'Fiona', 'Fleissig', '$2y$10$IeMpvUuMIrnxFHN.j94tEe.T1rjsTga1yYoyt5JAAXYUwbbjh1km6', '2019-01-12 11:11:51');

INSERT INTO `team` VALUES
  ('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '8f8fc90d-3715-11eb-add7-2c4d544f8fe0')
  , ('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '72f7cc6e-3717-11eb-add7-2c4d544f8fe0')
  , ('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '9712b5b9-3715-11eb-add7-2c4d544f8fe0')
  , ('4f141df7-3c0a-11e8-b046-2c4d544f8fe0', '8f8fc90d-3715-11eb-add7-2c4d544f8fe0')
  , ('4f141df7-3c0a-11e8-b046-2c4d544f8fe0', '72f7cc6e-3717-11eb-add7-2c4d544f8fe0')
  , ('4f141df7-3c0a-11e8-b046-2c4d544f8fe0', 'a10b8c84-3715-11eb-add7-2c4d544f8fe0');

INSERT INTO `task` (`id`, `userId`, `projectId`, `priorityId`, `title`, `expense`, `done`, `dueDate`, `created`) VALUES
  ('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '4f141df7-3c0a-11e8-b046-2c4d544f8fe0', '8f8fc90d-3715-11eb-add7-2c4d544f8fe0'
  , 1, 'Mail an Vorstand schreiben Fi', 0.5, 0, '2022-06-29', '2022-06-17 09:13:20')
  , ('hbdb169f-f4ek-11e7-a056-2c4d544f8fe0', '4f141df7-3c0a-11e8-b046-2c4d544f8fe0', '8f8fc90d-3715-11eb-add7-2c4d544f8fe0'
  , 2, 'Pl', 3, 0, '2022-06-28', '2022-06-12 11:25:31')
  , ('cbdb169f-e0da-11e7-a056-2c4d544f8fe0', '4f141df7-3c0a-11e8-b046-2c4d544f8fe0', '72f7cc6e-3717-11eb-add7-2c4d544f8fe0', 1, 'Abstimmung der Anforderungen', 3.5, 1, '2022-06-16', '2022-06-13 16:51:28')
  , ('01b169aa-3718-11eb-add7-2c4d544f8fe0', '4f141df7-3c0a-11e8-b046-2c4d544f8fe0', 'a10b8c84-3715-11eb-add7-2c4d544f8fe0', 2, 'Erster Entwurf GUI', 40, 0, '2022-06-17', '2022-06-12 11:25:31')
  , ('0babb17d-3718-11eb-add7-2c4d544f8fe0', '4f141df7-3c0a-11e8-b046-2c4d544f8fe0', 'a10b8c84-3715-11eb-add7-2c4d544f8fe0', 1, 'MMI-Paper lesen', 2.5 , 0, '2022-06-20', '2022-06-13 16:51:28')
  , ('15da3db7-3718-11eb-add7-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '72f7cc6e-3717-11eb-add7-2c4d544f8fe0', 2, 'Abstimmung der Anforderungen', 3.5, 1, '2022-06-16', '2022-06-12 11:25:31')
  , ('1b2d4564-3718-11eb-add7-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0', 'a10b8c84-3715-11eb-add7-2c4d544f8fe0', 1, 'Mike anrufen', 0.5, 0, '2022-06-17', '2022-06-13 16:51:28')
  , ('209004a9-3718-11eb-add7-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0', '72f7cc6e-3717-11eb-add7-2c4d544f8fe0', 2, 'Reflexion des Sprints', 3, 0, '2022-06-28', '2022-06-12 11:25:31')
  , ('25d512a0-3718-11eb-add7-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0', 'a10b8c84-3715-11eb-add7-2c4d544f8fe0', 1, 'Schaltung Laderampe entwickeln', 40, 0, '2022-06-28', '2022-06-13 16:51:28');


--
-- Indizes für die Tabelle `user`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
