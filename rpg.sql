-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 12, 2019 at 08:05 PM
-- Server version: 10.1.41-MariaDB-0ubuntu0.18.04.1
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
  `status_id` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password_hash`, `status_id`, `birth`, `selected_id`) VALUES
(1, 'admin', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', 100, '2019-07-10 16:46:30', 4),
(2, 'user', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', 1, '2019-05-20 08:21:49', NULL),
(4, 'Luis', '$2y$10$wP5OEhJ.NjUZBOSpZ5KKbejZgxELrcuM/0NwA//JQrwTO0jYgntXu', 50, '2019-05-29 11:44:25', 2),
(5, 'DerDruide', '$2y$10$I9UzxQjPAfDPNqODmh7y/embo.A8YqWMK91Xq2zHXkCV.U3avgAWq', 50, '2019-05-29 11:44:28', 3),
(6, 'GoodThor', '$2y$10$OZHCNrEud4cL99M.Nvs4V.yue5iq6fm.WIeeTvhCb8OsD30Ck6Q1m', 1, '2019-07-10 18:53:18', NULL),
(7, 'tester', '$2y$10$UT.5BWsZYGejUm2EDn94yemp1WL6S6qDZB1WWzGF5v66HM8bHGr.y', 50, '2019-09-12 06:41:40', 4),
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
  `active_id` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '1',
  `message` text NOT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `battle`
--

INSERT INTO `battle` (`id`, `active_id`, `round`, `message`, `position_id`) VALUES
(83, 3, 1, 'Groooover skipped\\n', 3),
(102, 4, 1, '', 4),
(105, 4, 1, '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `character`
--

CREATE TABLE `character` (
  `id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `xp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `participant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character`
--

INSERT INTO `character` (`id`, `race_id`, `class_id`, `name`, `xp`, `account_id`, `birth`, `participant_id`) VALUES
(1, 1, 21, 'Tom', 0, 1, '2019-05-03 15:47:17', 0),
(2, 2, 2, 'The Rock', 0, 4, '2019-05-13 15:48:01', 0),
(3, 3, 111, 'Groooover', 807, 5, '2019-05-20 08:25:24', 0),
(4, 4, 1, 'Kurt', 73005, 7, '2019-05-20 08:27:06', 0),
(5, 1, 23, 'Test Subject 24', 0, 7, '2019-05-20 11:57:21', 0),
(6, 1, 3, 'HoyHoy', 0, 8, '2019-07-29 20:24:43', 0),
(7, 1, 2, 'Peter', 0, 8, '2019-07-29 20:31:40', 0),
(8, 1, 1, 'Kim', 0, 8, '2019-07-29 20:32:42', 0),
(9, 1, 2, 'Troy', 0, 8, '2019-07-29 20:42:17', 0),
(10, 1, 4, 'Timo', 0, 8, '2019-07-29 20:42:36', 0);

-- --------------------------------------------------------

--
-- Table structure for table `character_skills`
--

CREATE TABLE `character_skills` (
  `character_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `nextUse` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character_skills`
--

INSERT INTO `character_skills` (`character_id`, `skill_id`, `nextUse`) VALUES
(4, 101, 0),
(3, 101, 0);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `stats_id` int(11) DEFAULT NULL,
  `start_weapon_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `stats_id`, `start_weapon_id`) VALUES
(1, 'Apprentice', 12, 106),
(2, 'Warrior', 13, 100),
(3, 'Rogue', 14, 110),
(4, 'Wild', 15, 109),
(21, 'Knight', 16, NULL),
(22, 'Shaman', 17, NULL),
(23, 'Ranger', 18, NULL),
(24, 'Mage', 19, NULL),
(25, 'Alchemist', 20, NULL),
(26, 'Tamer', 1, NULL),
(27, 'Berserk', 1, NULL),
(28, 'Assassin', 1, NULL),
(29, 'Traveller', 1, NULL),
(30, 'Fighter', 1, NULL),
(100, 'Reaper', 1, NULL),
(101, 'Swift', 1, NULL),
(102, 'Focused', 1, NULL),
(103, 'Guardian', 1, NULL),
(104, 'Fallen', 1, NULL),
(105, 'Kamikaze', 1, NULL),
(106, 'Hunter', 1, NULL),
(107, 'Beast', 1, NULL),
(108, 'Infused', 1, NULL),
(109, 'Elementalist', 1, NULL),
(110, 'Sage', 1, NULL),
(111, 'Druid', 1, NULL),
(112, 'Necromancer', 1, NULL),
(113, 'Driven', 1, NULL),
(114, 'Bard', 1, NULL),
(400, 'Wizard', 1, NULL),
(401, 'Touched', 1, NULL),
(402, 'Spirit', 1, NULL),
(403, 'Gate', 1, NULL),
(404, 'Narrator', 1, NULL),
(405, 'Fate', 1, NULL),
(406, 'Death', 1, NULL),
(407, 'Wind', 1, NULL),
(408, 'Seal', 1, NULL),
(409, 'Truth', 1, NULL),
(410, 'Pain', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class_skills`
--

CREATE TABLE `class_skills` (
  `class_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_skills`
--

INSERT INTO `class_skills` (`class_id`, `skill_id`, `level`, `teacher_id`) VALUES
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
  `location_id` int(11) NOT NULL,
  `floors` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dungeon`
--

INSERT INTO `dungeon` (`id`, `name`, `level`, `location_id`, `floors`) VALUES
(1, 'Some Crypt', 1, 0, 5),
(2, 'A big hole', 10, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_npc`
--

CREATE TABLE `dungeon_npc` (
  `dungeon_id` int(11) NOT NULL,
  `npc_id` int(11) NOT NULL,
  `minFloor` int(11) NOT NULL,
  `maxFloor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dungeon_npc`
--

INSERT INTO `dungeon_npc` (`dungeon_id`, `npc_id`, `minFloor`, `maxFloor`) VALUES
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
  `participant_id` int(11) DEFAULT NULL,
  `npc_id` int(11) NOT NULL,
  `suffix` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enemy`
--

INSERT INTO `enemy` (`id`, `participant_id`, `npc_id`, `suffix`) VALUES
(56, 83, 1, 'A'),
(76, 102, 1, 'A'),
(79, 105, 1, 'A');

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
  `character_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `enchantment_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `character_id`, `item_id`, `slot_id`, `enchantment_id`, `amount`) VALUES
(1, 4, 103, 1, NULL, 1),
(19, 4, 1, 1, NULL, 9),
(45, 4, 210, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `type_id` int(11) NOT NULL,
  `stackable` tinyint(1) NOT NULL DEFAULT '1',
  `rarity_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `type_id`, `stackable`, `rarity_id`) VALUES
(1, 'health_potion', 2, 1, 1),
(2, 'honey', 1, 1, 1),
(3, 'poison', 2, 1, 1),
(100, 'blade', 7, 0, 2),
(101, 'bow', 16, 0, 2),
(102, 'florett', 17, 0, 2),
(103, 'maze', 12, 0, 2),
(104, 'nunchuck', 15, 0, 2),
(105, 'sceptre', 8, 0, 2),
(106, 'wand', 11, 0, 2),
(107, 'battlestaff', 9, 0, 2),
(108, 'club', 14, 0, 2),
(109, 'dagger', 10, 0, 2),
(110, 'hammer', 13, 0, 2),
(200, 'blade', 7, 0, 3),
(201, 'bow', 16, 0, 3),
(202, 'florett', 17, 0, 3),
(203, 'maze', 12, 0, 3),
(204, 'nunchuck', 15, 0, 3),
(205, 'sceptre', 8, 0, 3),
(206, 'wand', 11, 0, 3),
(207, 'battlestaff', 9, 0, 3),
(208, 'club', 14, 0, 3),
(209, 'dagger', 10, 0, 3),
(210, 'hammer', 13, 0, 3),
(300, 'blade', 7, 0, 6),
(301, 'bow', 16, 0, 6),
(302, 'florett', 17, 0, 6),
(303, 'maze', 12, 0, 6),
(304, 'nunchuck', 15, 0, 6),
(305, 'sceptre', 8, 0, 6),
(306, 'wand', 11, 0, 6),
(307, 'battlestaff', 9, 0, 6),
(308, 'club', 14, 0, 6),
(309, 'dagger', 10, 0, 6),
(310, 'hammer', 13, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `itemtype`
--

CREATE TABLE `itemtype` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `icon` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemtype`
--

INSERT INTO `itemtype` (`id`, `name`, `icon`) VALUES
(1, 'Object', 0),
(2, 'Potion', 1),
(3, 'Weapon', 1),
(4, 'Shield', 0),
(5, 'Armor', 1),
(6, 'Two-Handed', 0),
(7, 'Blade', 0),
(8, 'Sceptre', 0),
(9, 'Battlestaff', 0),
(10, 'Dagger', 0),
(11, 'Wand', 0),
(12, 'Maze', 0),
(13, 'Hammer', 0),
(14, 'Club', 0),
(15, 'Nunchuck', 0),
(16, 'Bow', 0),
(17, 'Florett', 0);

-- --------------------------------------------------------

--
-- Table structure for table `itemtype_relations`
--

CREATE TABLE `itemtype_relations` (
  `child` int(11) NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemtype_relations`
--

INSERT INTO `itemtype_relations` (`child`, `parent`) VALUES
(6, 3),
(9, 6),
(14, 6),
(10, 3),
(13, 6),
(12, 3),
(8, 3),
(7, 3),
(11, 6),
(16, 6),
(15, 3),
(17, 3);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `area_id` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `area_id`, `mapX`, `mapY`) VALUES
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
  `rank_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `npc`
--

INSERT INTO `npc` (`id`, `level`, `name`, `maxHealth`, `rank_id`) VALUES
(1, 1, 'angry_bees', 20, 1),
(2, 10, 'drake', 180, 1),
(3, 5, 'eye', 8, 1),
(4, 15, 'skull', 16, 1),
(5, 1, 'slime', 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `npc_loot`
--

CREATE TABLE `npc_loot` (
  `npc_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `chance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `npc_loot`
--

INSERT INTO `npc_loot` (`npc_id`, `item_id`, `chance`) VALUES
(1, 2, 100);

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `died` tinyint(1) NOT NULL DEFAULT '0',
  `joined` tinyint(1) NOT NULL DEFAULT '0',
  `health` int(11) NOT NULL,
  `battle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `participant_effects`
--

CREATE TABLE `participant_effects` (
  `participant_id` int(11) NOT NULL,
  `effect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL DEFAULT '1',
  `dungeon_id` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `foundStairs` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `location_id`, `dungeon_id`, `floor`, `attempts`, `foundStairs`) VALUES
(1, 1, NULL, NULL, NULL, 0),
(2, 41, NULL, NULL, NULL, 0),
(3, 1, 1, 1, 0, 0),
(4, 1, 1, 3, 5, 0),
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
  `stats_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `race`
--

INSERT INTO `race` (`id`, `name`, `strength`, `resistance`, `agility`, `skin`, `stats_id`) VALUES
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
-- Table structure for table `rarity`
--

CREATE TABLE `rarity` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rarity`
--

INSERT INTO `rarity` (`id`, `name`, `color`) VALUES
(1, 'None', NULL),
(2, 'Rusty', '8a4722'),
(3, 'Sharp', 'a18f85'),
(6, 'Royal', 'ba7713');

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
  `name` varchar(10) NOT NULL,
  `space` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slot`
--

INSERT INTO `slot` (`id`, `name`, `space`) VALUES
(1, 'Inventory', 20),
(2, 'Left Hand', 1),
(3, 'Right Hand', 1),
(4, 'Loot', 20);

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
  ADD KEY `status` (`status_id`),
  ADD KEY `selected` (`selected_id`);

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
  ADD KEY `active` (`active_id`),
  ADD KEY `position` (`position_id`);

--
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`),
  ADD KEY `race` (`race_id`),
  ADD KEY `class` (`class_id`),
  ADD KEY `character_ibfk_3` (`account_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indexes for table `character_skills`
--
ALTER TABLE `character_skills`
  ADD KEY `character` (`character_id`),
  ADD KEY `skill` (`skill_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `stats` (`stats_id`);

--
-- Indexes for table `class_skills`
--
ALTER TABLE `class_skills`
  ADD KEY `class` (`class_id`),
  ADD KEY `skill` (`skill_id`),
  ADD KEY `teacher` (`teacher_id`);

--
-- Indexes for table `dungeon`
--
ALTER TABLE `dungeon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location_id`);

--
-- Indexes for table `dungeon_npc`
--
ALTER TABLE `dungeon_npc`
  ADD KEY `dungeon` (`dungeon_id`),
  ADD KEY `npc` (`npc_id`);

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
  ADD KEY `battle` (`participant_id`),
  ADD KEY `npc` (`npc_id`);

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
  ADD KEY `slot` (`slot_id`),
  ADD KEY `enchantment` (`enchantment_id`),
  ADD KEY `inventory_ibfk_1` (`character_id`),
  ADD KEY `inventory_ibfk_2` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `itemtype_ibfk_1` (`type_id`);

--
-- Indexes for table `itemtype`
--
ALTER TABLE `itemtype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `itemtype_relations`
--
ALTER TABLE `itemtype_relations`
  ADD KEY `child` (`child`),
  ADD KEY `parent` (`parent`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area` (`area_id`);

--
-- Indexes for table `npc`
--
ALTER TABLE `npc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rank` (`rank_id`);

--
-- Indexes for table `npc_loot`
--
ALTER TABLE `npc_loot`
  ADD KEY `npc` (`npc_id`),
  ADD KEY `loot` (`item_id`);

--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battle` (`battle`);

--
-- Indexes for table `participant_effects`
--
ALTER TABLE `participant_effects`
  ADD KEY `effect` (`effect_id`),
  ADD KEY `enemy` (`participant_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location_id`),
  ADD KEY `dungeon` (`dungeon_id`);

--
-- Indexes for table `race`
--
ALTER TABLE `race`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stats` (`stats_id`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rarity`
--
ALTER TABLE `rarity`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enemy`
--
ALTER TABLE `enemy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT for table `itemtype`
--
ALTER TABLE `itemtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `npc`
--
ALTER TABLE `npc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rarity`
--
ALTER TABLE `rarity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected_id`) REFERENCES `character` (`id`);

--
-- Constraints for table `battle`
--
ALTER TABLE `battle`
  ADD CONSTRAINT `battle_ibfk_1` FOREIGN KEY (`active_id`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `battle_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`);

--
-- Constraints for table `character`
--
ALTER TABLE `character`
  ADD CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`id`),
  ADD CONSTRAINT `character_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Constraints for table `character_skills`
--
ALTER TABLE `character_skills`
  ADD CONSTRAINT `character_skills_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `character_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`);

--
-- Constraints for table `class_skills`
--
ALTER TABLE `class_skills`
  ADD CONSTRAINT `class_skills_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `class_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`),
  ADD CONSTRAINT `class_skills_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `npc` (`id`);

--
-- Constraints for table `dungeon_npc`
--
ALTER TABLE `dungeon_npc`
  ADD CONSTRAINT `dungeon_npc_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`),
  ADD CONSTRAINT `dungeon_npc_ibfk_2` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`);

--
-- Constraints for table `enemy`
--
ALTER TABLE `enemy`
  ADD CONSTRAINT `enemy_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`);

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
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `slot` (`id`),
  ADD CONSTRAINT `inventory_ibfk_4` FOREIGN KEY (`enchantment_id`) REFERENCES `enchantment` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `itemtype_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `itemtype` (`id`);

--
-- Constraints for table `itemtype_relations`
--
ALTER TABLE `itemtype_relations`
  ADD CONSTRAINT `itemtype_relations_ibfk_1` FOREIGN KEY (`child`) REFERENCES `itemtype` (`id`),
  ADD CONSTRAINT `itemtype_relations_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `itemtype` (`id`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);

--
-- Constraints for table `npc`
--
ALTER TABLE `npc`
  ADD CONSTRAINT `npc_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `rank` (`id`);

--
-- Constraints for table `npc_loot`
--
ALTER TABLE `npc_loot`
  ADD CONSTRAINT `npc_loot_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `npc_loot_ibfk_2` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`);

--
-- Constraints for table `participant_effects`
--
ALTER TABLE `participant_effects`
  ADD CONSTRAINT `participant_effects_ibfk_1` FOREIGN KEY (`effect_id`) REFERENCES `effect` (`id`),
  ADD CONSTRAINT `participant_effects_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`);

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id`) REFERENCES `character` (`id`);

--
-- Constraints for table `race`
--
ALTER TABLE `race`
  ADD CONSTRAINT `race_ibfk_1` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
