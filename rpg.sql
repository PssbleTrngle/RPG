-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 29, 2019 at 10:43 PM
-- Server version: 10.1.40-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rpg`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password_hash` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password_hash`, `status`, `birth`, `selected`) VALUES
(1, 'admin', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', 100, '2019-07-10 16:46:30', 4),
(2, 'user', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', 1, '2019-05-20 08:21:49', NULL),
(4, 'Luis', '$2y$10$wP5OEhJ.NjUZBOSpZ5KKbejZgxELrcuM/0NwA//JQrwTO0jYgntXu', 50, '2019-05-29 11:44:25', 2),
(5, 'DerDruide', '$2y$10$I9UzxQjPAfDPNqODmh7y/embo.A8YqWMK91Xq2zHXkCV.U3avgAWq', 50, '2019-05-29 11:44:28', 3),
(6, 'GoodThor', '$2y$10$OZHCNrEud4cL99M.Nvs4V.yue5iq6fm.WIeeTvhCb8OsD30Ck6Q1m', 1, '2019-07-10 18:53:18', NULL),
(7, 'tester', '$2y$10$UT.5BWsZYGejUm2EDn94yemp1WL6S6qDZB1WWzGF5v66HM8bHGr.y', 1, '2019-07-28 22:22:24', 4),
(8, 'xxx_darkoverlord_xxx', '$2y$10$jnClTKPXbjNTtDBDyuu7oObN/9jfLBfK0Z1s0sOWwSDYwfRg1wmRK', 100, '2019-07-29 17:23:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `name`, `level`, `mapX`, `mapY`) VALUES
(1, 'Plain Fields', 1, 0, 0),
(2, 'Beach', 5, 4, -7),
(3, 'Forest', 10, -10, 2),
(4, 'Everlasting Battlefields', 50, 8, 12);

-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE `battle` (
  `id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '1',
  `message` text NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `battle`
--

INSERT INTO `battle` (`id`, `active`, `round`, `message`, `position`) VALUES
(83, 3, 1, 'Groooover skipped\\n', 3),
(102, 4, 1, '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `character`
--

CREATE TABLE `character` (
  `id` int(11) NOT NULL,
  `race` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `health` int(11) NOT NULL,
  `xp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `account` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `battle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character`
--

INSERT INTO `character` (`id`, `race`, `class`, `name`, `health`, `xp`, `account`, `birth`, `battle`) VALUES
(1, 1, 21, 'Tom', 600, 0, 1, '2019-05-03 15:47:17', NULL),
(2, 2, 2, 'The Rock', 100, 0, 4, '2019-05-13 15:48:01', NULL),
(3, 3, 111, 'Groooover', 80, 805, 5, '2019-05-20 08:25:24', NULL),
(4, 4, 1, 'Kurt', 1000, 73003, 7, '2019-05-20 08:27:06', NULL),
(5, 1, 23, 'Test Subject 24', 90, 0, 7, '2019-05-20 11:57:21', NULL),
(6, 1, 3, 'HoyHoy', 1000, 0, 8, '2019-07-29 20:24:43', NULL),
(7, 1, 2, 'Peter', 1000, 0, 8, '2019-07-29 20:31:40', NULL),
(8, 1, 1, 'Kim', 1000, 0, 8, '2019-07-29 20:32:42', NULL),
(9, 1, 2, 'Troy', 1000, 0, 8, '2019-07-29 20:42:17', NULL),
(10, 1, 4, 'Timo', 1000, 0, 8, '2019-07-29 20:42:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `character_skills`
--

CREATE TABLE `character_skills` (
  `character` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `nextUse` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character_skills`
--

INSERT INTO `character_skills` (`character`, `skill`, `nextUse`) VALUES
(4, 101, 0),
(3, 101, 0);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `stats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `stats`) VALUES
(1, 'Apprentice', 12),
(2, 'Warrior', 13),
(3, 'Rogue', 14),
(4, 'Wild', 15),
(21, 'Knight', 16),
(22, 'Shaman', 17),
(23, 'Ranger', 18),
(24, 'Mage', 19),
(25, 'Alchemist', 20),
(26, 'Tamer', 1),
(27, 'Berserk', 1),
(28, 'Assassin', 1),
(29, 'Traveller', 1),
(30, 'Fighter', 1),
(100, 'Reaper', 1),
(101, 'Swift', 1),
(102, 'Focused', 1),
(103, 'Guardian', 1),
(104, 'Fallen', 1),
(105, 'Kamikaze', 1),
(106, 'Hunter', 1),
(107, 'Beast', 1),
(108, 'Infused', 1),
(109, 'Elementalist', 1),
(110, 'Sage', 1),
(111, 'Druid', 1),
(112, 'Necromancer', 1),
(113, 'Driven', 1),
(114, 'Bard', 1),
(400, 'Wizard', 1),
(401, 'Touched', 1),
(402, 'Spirit', 1),
(403, 'Gate', 1),
(404, 'Narrator', 1),
(405, 'Fate', 1),
(406, 'Death', 1),
(407, 'Wind', 1),
(408, 'Seal', 1),
(409, 'Truth', 1),
(410, 'Pain', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_skills`
--

CREATE TABLE `class_skills` (
  `class` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `level` int(10) UNSIGNED NOT NULL,
  `teacher` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_skills`
--

INSERT INTO `class_skills` (`class`, `skill`, `level`, `teacher`) VALUES
(1, 3, 0, NULL),
(1, 500, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dungeon`
--

CREATE TABLE `dungeon` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `level` int(11) NOT NULL,
  `location` int(11) NOT NULL,
  `floors` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dungeon`
--

INSERT INTO `dungeon` (`id`, `name`, `level`, `location`, `floors`) VALUES
(1, 'Some Crypt', 1, 0, 5),
(2, 'A big hole', 10, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_npc`
--

CREATE TABLE `dungeon_npc` (
  `dungeon` int(11) NOT NULL,
  `npc` int(11) NOT NULL,
  `minFloor` int(11) NOT NULL,
  `maxFloor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dungeon_npc`
--

INSERT INTO `dungeon_npc` (`dungeon`, `npc`, `minFloor`, `maxFloor`) VALUES
(1, 1, 1, 2),
(2, 2, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `effect`
--

CREATE TABLE `effect` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `effect`
--

INSERT INTO `effect` (`id`, `name`) VALUES
(1, 'Poison');

-- --------------------------------------------------------

--
-- Table structure for table `enchantment`
--

CREATE TABLE `enchantment` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enemy`
--

CREATE TABLE `enemy` (
  `id` int(11) NOT NULL,
  `health` int(11) NOT NULL,
  `battle` int(11) DEFAULT NULL,
  `npc` int(11) NOT NULL,
  `suffix` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enemy`
--

INSERT INTO `enemy` (`id`, `health`, `battle`, `npc`, `suffix`) VALUES
(56, 20, 83, 1, 'A'),
(76, 20, 102, 1, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `enemy_effects`
--

CREATE TABLE `enemy_effects` (
  `enemy` int(11) NOT NULL,
  `effect` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `evolution`
--

CREATE TABLE `evolution` (
  `level` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `evolution`
--

INSERT INTO `evolution` (`level`, `from`, `to`) VALUES
(10, 1, 25),
(10, 1, 24),
(10, 1, 22),
(10, 4, 23),
(10, 4, 26),
(10, 4, 27),
(10, 2, 30),
(10, 2, 27),
(10, 2, 21),
(10, 3, 28),
(10, 3, 29),
(20, 23, 106),
(20, 27, 105),
(20, 21, 104),
(20, 21, 103),
(20, 30, 102),
(20, 30, 101),
(20, 28, 101),
(20, 28, 100),
(20, 29, 114),
(20, 29, 113),
(20, 22, 112),
(20, 22, 111),
(20, 24, 110),
(20, 25, 109),
(20, 25, 108),
(20, 26, 107),
(30, 105, 410),
(30, 104, 409),
(30, 103, 408),
(30, 101, 407),
(30, 100, 406),
(30, 100, 405),
(30, 114, 404),
(30, 113, 403),
(30, 112, 403),
(30, 111, 402),
(30, 110, 401),
(30, 109, 400),
(30, 110, 400);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `character` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `slot` int(11) NOT NULL,
  `enchantment` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `character`, `item`, `slot`, `enchantment`, `amount`) VALUES
(1, 4, 1, 1, NULL, 51),
(2, 4, 2, 1, NULL, 1),
(3, 4, 2, 1, NULL, 1),
(19, 3, 1, 1, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `type` int(11) NOT NULL,
  `stackable` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `type`, `stackable`) VALUES
(1, 'Health Potion', 2, 1),
(2, 'Rusty Blade', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `itemtype`
--

CREATE TABLE `itemtype` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemtype`
--

INSERT INTO `itemtype` (`id`, `name`) VALUES
(1, 'Object'),
(2, 'Potion'),
(3, 'Weapon'),
(4, 'Shield'),
(5, 'Armor');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `area` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `area`, `mapX`, `mapY`) VALUES
(1, 'Starter Town', 1, 0, 0),
(2, 'Big City', 1, 1, 13),
(11, 'Pirate Bar', 2, 0, 0),
(41, 'The Origin', 4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `npc`
--

CREATE TABLE `npc` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `maxHealth` int(11) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `npc`
--

INSERT INTO `npc` (`id`, `level`, `name`, `maxHealth`, `rank`) VALUES
(1, 1, 'Angry Bees', 20, 1),
(2, 10, 'Drake', 180, 1),
(3, 5, 'Eye', 8, 1),
(4, 15, 'Skull', 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `npc_loot`
--

CREATE TABLE `npc_loot` (
  `npc` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `chance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `npc_loot`
--

INSERT INTO `npc_loot` (`npc`, `item`, `chance`) VALUES
(1, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `location` int(11) NOT NULL DEFAULT '1',
  `dungeon` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `foundStairs` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `location`, `dungeon`, `floor`, `attempts`, `foundStairs`) VALUES
(1, 1, NULL, NULL, NULL, 0),
(2, 41, NULL, NULL, NULL, 0),
(3, 1, 1, 1, 7, 1),
(4, 1, 1, 3, 2, 0),
(5, 1, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `race`
--

CREATE TABLE `race` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `strength` int(11) NOT NULL,
  `resistance` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `skin` varchar(6) NOT NULL,
  `stats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `race`
--

INSERT INTO `race` (`id`, `name`, `strength`, `resistance`, `agility`, `skin`, `stats`) VALUES
(1, 'Human', 10, 10, 10, 'ffcfa3', 1),
(2, 'Troll', 12, 15, 3, '', 1),
(3, 'Nymph', 5, 8, 17, '669619', 1),
(4, 'Shade', 4, 8, 18, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`id`, `name`) VALUES
(1, 'Monster'),
(2, 'Rare'),
(3, 'Boss'),
(4, 'God');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `timeout` int(10) UNSIGNED NOT NULL,
  `cost` int(10) UNSIGNED NOT NULL,
  `group` tinyint(1) NOT NULL,
  `affectDead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`id`, `name`, `timeout`, `cost`, `group`, `affectDead`) VALUES
(1, 'Slash', 0, 1, 0, 0),
(2, 'Backstab', 0, 1, 0, 0),
(3, 'Pulse', 3, 1, 0, 0),
(51, 'Heal', 0, 2, 0, 0),
(52, 'Cleansing Rain', 0, 2, 1, 0),
(101, 'Pulse', 0, 1, 0, 0),
(102, 'Rumble', 0, 1, 1, 0),
(500, 'Glow', 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `slot`
--

CREATE TABLE `slot` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slot`
--

INSERT INTO `slot` (`id`, `name`) VALUES
(1, 'Inventory'),
(2, 'Left Hand'),
(3, 'Right Hand'),
(4, 'Loot');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `wisdom` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  `resistance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `wisdom`, `strength`, `agility`, `luck`, `resistance`) VALUES
(1, 0, 0, 0, 0, 0),
(12, 35, 10, 20, 25, 10),
(13, 10, 25, 25, 15, 25),
(14, 10, 22, 28, 30, 10),
(15, 10, 27, 23, 20, 20),
(16, 20, 30, 10, 15, 35),
(17, 35, 10, 23, 27, 15),
(18, 20, 15, 30, 30, 15),
(19, 40, 15, 15, 25, 15),
(20, 30, 28, 20, 18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'user'),
(50, 'betatester'),
(100, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `selected` (`selected`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `battle`
--
ALTER TABLE `battle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `position` (`position`);

--
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`),
  ADD KEY `race` (`race`),
  ADD KEY `class` (`class`),
  ADD KEY `battle` (`battle`),
  ADD KEY `character_ibfk_3` (`account`);

--
-- Indexes for table `character_skills`
--
ALTER TABLE `character_skills`
  ADD KEY `character` (`character`),
  ADD KEY `skill` (`skill`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `stats` (`stats`);

--
-- Indexes for table `class_skills`
--
ALTER TABLE `class_skills`
  ADD KEY `class` (`class`),
  ADD KEY `skill` (`skill`),
  ADD KEY `teacher` (`teacher`);

--
-- Indexes for table `dungeon`
--
ALTER TABLE `dungeon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `dungeon_npc`
--
ALTER TABLE `dungeon_npc`
  ADD KEY `dungeon` (`dungeon`),
  ADD KEY `npc` (`npc`);

--
-- Indexes for table `effect`
--
ALTER TABLE `effect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enchantment`
--
ALTER TABLE `enchantment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enemy`
--
ALTER TABLE `enemy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battle` (`battle`),
  ADD KEY `npc` (`npc`);

--
-- Indexes for table `enemy_effects`
--
ALTER TABLE `enemy_effects`
  ADD KEY `effect` (`effect`),
  ADD KEY `enemy` (`enemy`);

--
-- Indexes for table `evolution`
--
ALTER TABLE `evolution`
  ADD KEY `from` (`from`),
  ADD KEY `to` (`to`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item` (`item`),
  ADD KEY `slot` (`slot`),
  ADD KEY `enchantment` (`enchantment`),
  ADD KEY `inventory_ibfk_1` (`character`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `itemtype`
--
ALTER TABLE `itemtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area` (`area`);

--
-- Indexes for table `npc`
--
ALTER TABLE `npc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rank` (`rank`);

--
-- Indexes for table `npc_loot`
--
ALTER TABLE `npc_loot`
  ADD KEY `npc` (`npc`),
  ADD KEY `loot` (`item`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location`),
  ADD KEY `dungeon` (`dungeon`);

--
-- Indexes for table `race`
--
ALTER TABLE `race`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stats` (`stats`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slot`
--
ALTER TABLE `slot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `battle`
--
ALTER TABLE `battle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enemy`
--
ALTER TABLE `enemy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected`) REFERENCES `character` (`id`);

--
-- Constraints for table `battle`
--
ALTER TABLE `battle`
  ADD CONSTRAINT `battle_ibfk_1` FOREIGN KEY (`active`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `battle_ibfk_2` FOREIGN KEY (`position`) REFERENCES `position` (`id`);

--
-- Constraints for table `character`
--
ALTER TABLE `character`
  ADD CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race`) REFERENCES `race` (`id`),
  ADD CONSTRAINT `character_ibfk_2` FOREIGN KEY (`class`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `character_ibfk_4` FOREIGN KEY (`battle`) REFERENCES `battle` (`id`);

--
-- Constraints for table `character_skills`
--
ALTER TABLE `character_skills`
  ADD CONSTRAINT `character_skills_ibfk_1` FOREIGN KEY (`character`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `character_skills_ibfk_2` FOREIGN KEY (`skill`) REFERENCES `skill` (`id`);

--
-- Constraints for table `class_skills`
--
ALTER TABLE `class_skills`
  ADD CONSTRAINT `class_skills_ibfk_1` FOREIGN KEY (`class`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `class_skills_ibfk_2` FOREIGN KEY (`skill`) REFERENCES `skill` (`id`),
  ADD CONSTRAINT `class_skills_ibfk_3` FOREIGN KEY (`teacher`) REFERENCES `npc` (`id`);

--
-- Constraints for table `dungeon_npc`
--
ALTER TABLE `dungeon_npc`
  ADD CONSTRAINT `dungeon_npc_ibfk_1` FOREIGN KEY (`npc`) REFERENCES `npc` (`id`),
  ADD CONSTRAINT `dungeon_npc_ibfk_2` FOREIGN KEY (`dungeon`) REFERENCES `dungeon` (`id`);

--
-- Constraints for table `enemy`
--
ALTER TABLE `enemy`
  ADD CONSTRAINT `enemy_ibfk_1` FOREIGN KEY (`npc`) REFERENCES `npc` (`id`),
  ADD CONSTRAINT `enemy_ibfk_2` FOREIGN KEY (`battle`) REFERENCES `battle` (`id`);

--
-- Constraints for table `enemy_effects`
--
ALTER TABLE `enemy_effects`
  ADD CONSTRAINT `enemy_effects_ibfk_1` FOREIGN KEY (`effect`) REFERENCES `effect` (`id`),
  ADD CONSTRAINT `enemy_effects_ibfk_2` FOREIGN KEY (`enemy`) REFERENCES `enemy` (`id`);

--
-- Constraints for table `evolution`
--
ALTER TABLE `evolution`
  ADD CONSTRAINT `evolution_ibfk_1` FOREIGN KEY (`from`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `evolution_ibfk_2` FOREIGN KEY (`to`) REFERENCES `class` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`character`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`slot`) REFERENCES `slot` (`id`),
  ADD CONSTRAINT `inventory_ibfk_4` FOREIGN KEY (`enchantment`) REFERENCES `enchantment` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`type`) REFERENCES `itemtype` (`id`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`area`) REFERENCES `area` (`id`);

--
-- Constraints for table `npc`
--
ALTER TABLE `npc`
  ADD CONSTRAINT `npc_ibfk_1` FOREIGN KEY (`rank`) REFERENCES `rank` (`id`);

--
-- Constraints for table `npc_loot`
--
ALTER TABLE `npc_loot`
  ADD CONSTRAINT `npc_loot_ibfk_1` FOREIGN KEY (`item`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `npc_loot_ibfk_2` FOREIGN KEY (`npc`) REFERENCES `npc` (`id`);

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id`) REFERENCES `character` (`id`);

--
-- Constraints for table `race`
--
ALTER TABLE `race`
  ADD CONSTRAINT `race_ibfk_1` FOREIGN KEY (`stats`) REFERENCES `stats` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
