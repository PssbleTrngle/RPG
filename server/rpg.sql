-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 03, 2019 at 02:07 PM
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
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password_hash`, `birth`, `selected_id`) VALUES
(1, 'admin', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', '2019-09-12 18:46:58', NULL),
(2, 'user', '$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6', '2019-05-20 08:21:49', NULL),
(4, 'Luis', '$2y$10$wP5OEhJ.NjUZBOSpZ5KKbejZgxELrcuM/0NwA//JQrwTO0jYgntXu', '2019-05-29 11:44:25', 2),
(5, 'DerDruide', '$2y$10$I9UzxQjPAfDPNqODmh7y/embo.A8YqWMK91Xq2zHXkCV.U3avgAWq', '2019-05-29 11:44:28', 3),
(6, 'GoodThor', '$2y$10$OZHCNrEud4cL99M.Nvs4V.yue5iq6fm.WIeeTvhCb8OsD30Ck6Q1m', '2019-07-10 18:53:18', NULL),
(7, 'tester', '$2y$10$UT.5BWsZYGejUm2EDn94yemp1WL6S6qDZB1WWzGF5v66HM8bHGr.y', '2019-09-17 08:24:58', 4),
(8, 'xxx_darkoverlord_xxx', '$2y$10$jnClTKPXbjNTtDBDyuu7oObN/9jfLBfK0Z1s0sOWwSDYwfRg1wmRK', '2019-07-29 17:23:20', NULL),
(9, 'ichbinneuundso', '$2y$10$jkWBKHQaTP21sicKvFF8euSFKytqlet20R6GMkYmRiaI7d8OgWRma', '2019-10-02 21:19:57', 15);

-- --------------------------------------------------------

--
-- Table structure for table `account_permissions`
--

CREATE TABLE `account_permissions` (
  `account_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account_permissions`
--

INSERT INTO `account_permissions` (`account_id`, `permission_id`) VALUES
(1, 2),
(1, 3),
(7, 2),
(1, 1),
(2, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(4, 2),
(5, 2),
(7, 4),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `name`, `level`, `mapX`, `mapY`, `icon`) VALUES
(1, 'plain_fields', 1, 0, 0, 'forest'),
(2, 'beach', 5, 4, -7, NULL),
(3, 'forest', 10, -10, 2, 'forest_2'),
(4, 'everlasting_battlefields', 50, 8, 12, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE `battle` (
  `id` int(11) NOT NULL,
  `active_id` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '1',
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `battle_messages`
--

CREATE TABLE `battle_messages` (
  `id` int(11) NOT NULL,
  `battle_id` int(11) NOT NULL,
  `key` varchar(45) NOT NULL,
  `args` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `character`
--

CREATE TABLE `character` (
  `id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `xp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `level` int(10) DEFAULT '1',
  `account_id` int(11) DEFAULT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `participant_id` int(11) NOT NULL,
  `message` varchar(45) DEFAULT NULL,
  `skillpoints` int(10) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character`
--

INSERT INTO `character` (`id`, `race_id`, `name`, `xp`, `level`, `account_id`, `birth`, `participant_id`, `message`, `skillpoints`) VALUES
(1, 1, 'Tom', 0, 1, 1, '2019-05-03 15:47:17', 78, NULL, 0),
(2, 2, 'The Rock', 0, 1, 4, '2019-05-13 15:48:01', 79, NULL, 0),
(3, 3, 'Groooover', 5, 1, 5, '2019-05-20 08:25:24', 80, NULL, 0),
(4, 4, 'Kurt', 24, 5, 7, '2019-05-20 08:27:06', 77, 'ran', 67),
(5, 1, 'Test Subject 24', 0, 1, 7, '2019-05-20 11:57:21', 81, NULL, 0),
(6, 1, 'HoyHoy', 0, 1, 8, '2019-07-29 20:24:43', 82, NULL, 0),
(7, 1, 'Peter', 0, 1, 8, '2019-07-29 20:31:40', 83, NULL, 0),
(8, 1, 'Kim', 0, 1, 8, '2019-07-29 20:32:42', 84, NULL, 0),
(9, 1, 'Troy', 0, 1, 8, '2019-07-29 20:42:17', 85, NULL, 0),
(10, 1, 'Timo', 0, 1, 8, '2019-07-29 20:42:36', 86, NULL, 0),
(11, 1, 'Mein Boi', 0, 1, 7, '2019-10-02 20:55:09', 284, NULL, 0),
(12, 1, 'Mein Boii', 0, 1, 7, '2019-10-02 20:55:55', 285, NULL, 0),
(13, 1, 'Mein Boiii', 0, 1, 7, '2019-10-02 20:56:24', 286, NULL, 0),
(14, 1, 'Mein Boiiii', 0, 1, 7, '2019-10-02 20:58:17', 287, NULL, 0),
(15, 1, 'Yeah Boiiiii', 0, 1, 9, '2019-10-02 21:19:55', 288, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `character_classes`
--

CREATE TABLE `character_classes` (
  `character_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `character_classes`
--

INSERT INTO `character_classes` (`character_id`, `class_id`) VALUES
(4, 1),
(1, 1),
(2, 1),
(3, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(14, 2),
(11, 3),
(11, 1),
(12, 4),
(12, 1),
(13, 5),
(13, 1),
(15, 5);

-- --------------------------------------------------------

--
-- Table structure for table `charging_skills`
--

CREATE TABLE `charging_skills` (
  `id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `field_id` int(11) NOT NULL,
  `countdown` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `stats_id` int(11) NOT NULL DEFAULT '1',
  `start_weapon_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `stats_id`, `start_weapon_id`) VALUES
(1, 'apprentice', 1, 106),
(2, 'traveller', 1, 107),
(3, 'wild', 1, 108),
(4, 'fighter', 1, 100),
(5, 'psychic', 1, 105),
(11, 'druid', 1, NULL),
(12, 'mage', 1, NULL),
(13, 'alchemist', 1, NULL),
(21, 'rogue', 1, NULL),
(22, 'barde', 1, NULL),
(23, 'merchant', 1, NULL),
(31, 'ranger', 1, NULL),
(32, 'hermit', 1, NULL),
(41, 'duelist', 1, NULL),
(42, 'warrior', 1, NULL),
(43, 'focused', 1, NULL),
(51, 'priest', 1, NULL),
(52, 'medium', 1, NULL),
(53, 'telepath', 1, NULL),
(101, 'shaman', 1, NULL),
(102, 'necromancer', 1, NULL),
(104, 'wizard', 1, NULL),
(105, 'elementalist', 1, NULL),
(106, 'infused', 1, NULL),
(107, 'ritualist', 1, NULL),
(108, 'astromancer', 1, NULL),
(109, 'warlock', 1, NULL),
(110, 'illusionist', 1, NULL),
(201, 'assassin', 1, NULL),
(202, 'derwish', 1, NULL),
(203, 'bandit', 1, NULL),
(204, 'inventor', 1, NULL),
(205, 'fool', 1, NULL),
(206, 'performer', 1, NULL),
(207, 'charmer', 1, NULL),
(208, 'artificer', 1, NULL),
(301, 'tamer', 1, NULL),
(303, 'hunter', 1, NULL),
(304, 'monk', 1, NULL),
(401, 'rebel', 1, NULL),
(402, 'hero', 1, NULL),
(403, 'knight', 1, NULL),
(404, 'berserk', 1, NULL),
(405, 'mercenary', 1, NULL),
(406, 'blockade', 1, NULL),
(501, 'cleric', 1, NULL),
(502, 'thaumaturge', 1, NULL),
(504, 'temporal', 1, NULL),
(1001, 'possessed', 1, NULL),
(1002, 'sage', 1, NULL),
(1003, 'beast', 1, NULL),
(1004, 'fallen', 1, NULL),
(1005, 'guardian', 1, NULL),
(1006, 'chosen', 1, NULL),
(1007, 'insane', 1, NULL),
(1008, 'narrator', 1, NULL),
(1009, 'imposter', 1, NULL),
(1010, 'king', 1, NULL),
(1011, 'spirit', 1, NULL),
(1012, 'touched', 1, NULL),
(1013, 'oracle', 1, NULL),
(1014, 'slayer', 1, NULL),
(1016, 'creator', 1, NULL),
(1017, 'corrupted', 1, NULL),
(1018, 'conquerer', 1, NULL);

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
(1, 101, 0, NULL),
(1, 102, 0, NULL),
(1, 51, 3, NULL),
(1, 103, 0, NULL),
(1, 120, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dungeon`
--

CREATE TABLE `dungeon` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `level` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `floors` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `mapX` int(11) DEFAULT NULL,
  `mapY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dungeon`
--

INSERT INTO `dungeon` (`id`, `name`, `level`, `area_id`, `floors`, `icon`, `mapX`, `mapY`) VALUES
(1, 'some_crypt', 1, 1, 5, 'stone', -15, -4),
(2, 'a_big_hole', 10, 1, 10, 'moss', 10, 2);

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
(1, 2, 2, 4),
(1, 5, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `effect`
--

CREATE TABLE `effect` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `fade_min` int(10) DEFAULT '-1',
  `fade_max` int(10) DEFAULT '-1',
  `block` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `effect`
--

INSERT INTO `effect` (`id`, `name`, `fade_min`, `fade_max`, `block`) VALUES
(1, 'poison', 5, 7, 0),
(2, 'stunned', 1, 2, 1),
(3, 'burned', 2, 4, 0),
(4, 'frozen', 3, 4, 1),
(5, 'rage', 2, 4, 0),
(6, 'asleep', 2, 4, 1);

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
(10, 1, 11),
(10, 1, 12),
(10, 1, 13),
(10, 4, 41),
(10, 4, 42),
(10, 3, 42),
(10, 4, 43),
(10, 3, 31),
(10, 3, 32),
(10, 2, 21),
(10, 2, 22),
(10, 2, 23),
(10, 5, 51),
(10, 5, 52),
(10, 5, 53),
(20, 11, 101),
(20, 32, 101),
(20, 11, 102),
(20, 52, 102),
(20, 12, 104),
(20, 53, 104),
(20, 12, 105),
(20, 13, 105),
(20, 13, 106),
(20, 13, 107),
(20, 51, 107),
(20, 11, 108),
(20, 12, 109),
(20, 53, 110),
(20, 41, 401),
(20, 41, 402),
(20, 41, 403),
(20, 42, 404),
(20, 42, 405),
(20, 43, 406),
(20, 31, 301),
(20, 31, 303),
(20, 32, 304),
(20, 43, 304),
(20, 21, 201),
(20, 31, 201),
(20, 21, 202),
(20, 43, 202),
(20, 21, 203),
(20, 32, 203),
(20, 23, 204),
(20, 22, 205),
(20, 22, 206),
(20, 22, 207),
(20, 52, 207),
(20, 23, 208),
(20, 51, 501),
(20, 42, 501),
(20, 52, 502),
(20, 53, 504),
(30, 107, 1001),
(30, 404, 1001),
(30, 104, 1002),
(30, 304, 1002),
(30, 301, 1003),
(30, 106, 1003),
(30, 403, 1004),
(30, 403, 1005),
(30, 101, 1005),
(30, 109, 1005),
(30, 406, 1005),
(30, 402, 1006),
(30, 205, 1007),
(30, 205, 1008),
(30, 110, 1008),
(30, 206, 1009),
(30, 502, 1009),
(30, 401, 1010),
(30, 207, 1010),
(30, 105, 1011),
(30, 501, 1012),
(30, 108, 1012),
(30, 504, 1013),
(30, 303, 1014),
(30, 204, 1016),
(30, 102, 1016),
(30, 107, 1017),
(30, 402, 1017),
(30, 203, 1018),
(30, 109, 1018);

-- --------------------------------------------------------

--
-- Table structure for table `field`
--

CREATE TABLE `field` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `battle_id` int(11) NOT NULL,
  `spawn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(19, 4, 1, 1, NULL, 46),
(45, 4, 210, 2, NULL, 1),
(47, 4, 2, 1, NULL, 43),
(48, 4, 3, 1, NULL, 44),
(49, 4, 10, 1, NULL, 17),
(100, 4, 100, 1, NULL, 1),
(103, 4, 109, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `type_id` int(11) NOT NULL,
  `stackable` tinyint(1) NOT NULL DEFAULT '1',
  `rarity_id` int(11) NOT NULL DEFAULT '1',
  `stats_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `type_id`, `stackable`, `rarity_id`, `stats_id`) VALUES
(1, 'health_potion', 2, 1, 1, 1),
(2, 'sleep_potion', 2, 1, 1, 1),
(3, 'poison_potion', 2, 1, 1, 1),
(4, 'honey', 1, 1, 1, 1),
(10, 'ambrosia', 2, 1, 1, 1),
(100, 'blade', 7, 0, 2, 100),
(101, 'bow', 16, 0, 2, 101),
(102, 'florett', 17, 0, 2, 102),
(103, 'maze', 12, 0, 2, 103),
(104, 'nunchuck', 15, 0, 2, 104),
(105, 'sceptre', 8, 0, 2, 105),
(106, 'wand', 11, 0, 2, 106),
(107, 'battlestaff', 9, 0, 2, 107),
(108, 'club', 14, 0, 2, 108),
(109, 'dagger', 10, 0, 2, 109),
(110, 'hammer', 13, 0, 2, 110),
(200, 'blade', 7, 0, 3, 200),
(201, 'bow', 16, 0, 3, 201),
(202, 'florett', 17, 0, 3, 202),
(203, 'maze', 12, 0, 3, 203),
(204, 'nunchuck', 15, 0, 3, 204),
(205, 'sceptre', 8, 0, 3, 205),
(206, 'wand', 11, 0, 3, 206),
(207, 'battlestaff', 9, 0, 3, 207),
(208, 'club', 14, 0, 3, 208),
(209, 'dagger', 10, 0, 3, 209),
(210, 'hammer', 13, 0, 3, 210),
(300, 'blade', 7, 0, 6, 300),
(301, 'bow', 16, 0, 6, 301),
(302, 'florett', 17, 0, 6, 302),
(303, 'maze', 12, 0, 6, 303),
(304, 'nunchuck', 15, 0, 6, 304),
(305, 'sceptre', 8, 0, 6, 305),
(306, 'wand', 11, 0, 6, 306),
(307, 'battlestaff', 9, 0, 6, 307),
(308, 'club', 14, 0, 6, 308),
(309, 'dagger', 10, 0, 6, 309),
(310, 'hammer', 13, 0, 6, 310);

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
(1, 'object', 0),
(2, 'potion', 1),
(3, 'weapon', 1),
(4, 'shield', 0),
(5, 'armor', 1),
(6, 'two_handed', 0),
(7, 'blade', 0),
(8, 'sceptre', 0),
(9, 'battlestaff', 0),
(10, 'dagger', 0),
(11, 'wand', 0),
(12, 'maze', 0),
(13, 'hammer', 0),
(14, 'club', 0),
(15, 'nunchuck', 0),
(16, 'bow', 0),
(17, 'florett', 0);

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
  `mapY` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `area_id`, `mapX`, `mapY`, `icon`) VALUES
(1, 'starter_town', 1, 0, 0, NULL),
(2, 'big_city', 1, 1, 13, NULL),
(11, 'pirate_bar', 2, 0, 0, NULL),
(41, 'the_origin', 4, 0, 0, NULL);

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
(1, 4, 100);

-- --------------------------------------------------------

--
-- Table structure for table `npc_skills`
--

CREATE TABLE `npc_skills` (
  `npc_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `npc_skills`
--

INSERT INTO `npc_skills` (`npc_id`, `skill_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `died` tinyint(1) NOT NULL DEFAULT '0',
  `joined` tinyint(1) NOT NULL DEFAULT '0',
  `health` int(11) NOT NULL DEFAULT '0',
  `side` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participant`
--

INSERT INTO `participant` (`id`, `died`, `joined`, `health`, `side`) VALUES
(77, 0, 0, 26, 1),
(78, 0, 0, 1000, 0),
(79, 0, 0, 1000, 0),
(80, 0, 0, 1000, 0),
(81, 0, 0, 1000, 0),
(82, 0, 0, 1000, 0),
(83, 0, 0, 1000, 0),
(84, 0, 0, 1000, 0),
(85, 0, 0, 1000, 0),
(86, 0, 0, 1000, 0),
(284, 0, 0, 100, 0),
(285, 0, 0, 100, 0),
(286, 0, 0, 100, 0),
(287, 0, 0, 100, 0),
(288, 0, 0, 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `participant_effects`
--

CREATE TABLE `participant_effects` (
  `participant_id` int(11) NOT NULL,
  `effect_id` int(11) NOT NULL,
  `countdown` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `participant_skills`
--

CREATE TABLE `participant_skills` (
  `participant_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `nextUse` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participant_skills`
--

INSERT INTO `participant_skills` (`participant_id`, `skill_id`, `nextUse`) VALUES
(77, 101, 0),
(77, 102, 0),
(77, 51, 0),
(77, 103, 0),
(77, 120, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'user'),
(2, 'tester'),
(3, 'editor'),
(4, 'create_chars');

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
  `foundStairs` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `location_id`, `dungeon_id`, `floor`, `attempts`, `foundStairs`) VALUES
(1, 1, NULL, NULL, NULL, 0),
(2, 41, NULL, NULL, NULL, 0),
(3, 1, 1, 1, 0, 0),
(4, 1, 1, 3, 49, 1),
(5, 1, NULL, 1, 0, 0),
(6, 1, NULL, NULL, NULL, 0),
(7, 1, NULL, NULL, NULL, 0),
(8, 1, NULL, NULL, NULL, 0),
(9, 1, NULL, NULL, NULL, 0),
(10, 1, NULL, NULL, NULL, 0),
(11, 1, NULL, NULL, NULL, 0),
(12, 1, NULL, NULL, NULL, 0),
(13, 1, NULL, NULL, NULL, 0),
(14, 1, NULL, NULL, NULL, 0),
(15, 1, 1, 1, 0, 0);

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
(1, 'human', 10, 10, 10, 'ffcfa3', 1),
(2, 'troll', 12, 15, 3, '', 1),
(3, 'nymph', 5, 8, 17, '669619', 1),
(4, 'shade', 4, 8, 18, '', 1);

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
(1, 'monster'),
(2, 'rare'),
(3, 'boss'),
(4, 'god');

-- --------------------------------------------------------

--
-- Table structure for table `rarity`
--

CREATE TABLE `rarity` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rarity`
--

INSERT INTO `rarity` (`id`, `name`, `color`) VALUES
(1, 'none', NULL),
(2, 'rusty', '8a4722'),
(3, 'sharp', 'a18f85'),
(6, 'royal', 'ba7713');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `timeout` int(10) UNSIGNED NOT NULL,
  `charge` int(11) NOT NULL DEFAULT '0',
  `cost` int(10) UNSIGNED NOT NULL,
  `range` tinyint(1) NOT NULL,
  `affectDead` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`id`, `name`, `timeout`, `charge`, `cost`, `range`, `affectDead`) VALUES
(1, 'slash', 0, 0, 1, 1, 0),
(2, 'backstab', 0, 0, 1, 1, 0),
(51, 'heal', 0, 0, 2, 2, 0),
(52, 'cleansing_rain', 0, 0, 2, 1, 0),
(53, 'revive', 0, 0, 5, 1, 1),
(101, 'pulse', 0, 0, 1, 2, 0),
(102, 'rumble', 0, 0, 1, 1, 0),
(103, 'discharge', 0, 1, 1, 2, 0),
(120, 'blast', 0, 2, 1, 0, 0),
(121, 'kamikaze', 0, 2, 1, 0, 0),
(500, 'glow', 0, 0, 2, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `slot`
--

CREATE TABLE `slot` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `space` int(11) DEFAULT '1',
  `apply_stats` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slot`
--

INSERT INTO `slot` (`id`, `name`, `space`, `apply_stats`) VALUES
(1, 'inventory', 20, 0),
(2, 'left_hand', 1, 1),
(3, 'right_hand', 1, 1),
(4, 'loot', 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `wisdom` int(11) NOT NULL DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '0',
  `agility` int(11) NOT NULL DEFAULT '0',
  `luck` int(11) NOT NULL DEFAULT '0',
  `resistance` int(11) NOT NULL DEFAULT '0'
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
(20, 30, 28, 20, 18, 14),
(100, 0, 5, 2, 0, 0),
(101, 0, 3, 2, 4, 0),
(102, 0, 3, 4, 2, 0),
(103, 0, 7, 0, 2, 0),
(104, 1, 2, 4, 1, 0),
(105, 6, 0, 0, 1, 0),
(106, 4, 1, 0, 0, 2),
(107, 1, 3, 2, 0, 2),
(108, -2, 7, -1, 1, 2),
(109, 0, 3, 4, 0, 0),
(110, 0, 5, -3, 0, 2),
(200, 0, 10, 4, 0, 0),
(201, 0, 6, 4, 8, 0),
(202, 0, 6, 8, 4, 0),
(203, 0, 14, 0, 4, 0),
(204, 2, 4, 8, 2, 0),
(205, 12, 0, 0, 2, 0),
(206, 8, 2, 0, 0, 4),
(207, 2, 6, 4, 0, 4),
(208, -3, 14, -2, 2, 4),
(209, 0, 6, 8, 0, 0),
(210, 0, 10, -4, 0, 4),
(300, 0, 15, 6, 0, 0),
(301, 0, 9, 6, 12, 0),
(302, 0, 9, 12, 6, 0),
(303, 0, 21, 0, 6, 0),
(304, 3, 6, 12, 3, 0),
(305, 18, 0, 0, 3, 0),
(306, 12, 3, 0, 0, 6),
(307, 3, 9, 6, 0, 6),
(308, -4, 21, -3, 3, 6),
(309, 0, 9, 12, 0, 0),
(310, 0, 15, -5, 0, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selected` (`selected_id`);

--
-- Indexes for table `account_permissions`
--
ALTER TABLE `account_permissions`
  ADD KEY `account_permissions_ibfk_1` (`account_id`),
  ADD KEY `account_permissions_ibfk_2` (`permission_id`);

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
-- Indexes for table `battle_messages`
--
ALTER TABLE `battle_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `battle_messages_ibfk_1` (`battle_id`);

--
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`),
  ADD KEY `race` (`race_id`),
  ADD KEY `participant_id` (`participant_id`),
  ADD KEY `character_ibfk_3` (`account_id`);

--
-- Indexes for table `character_classes`
--
ALTER TABLE `character_classes`
  ADD KEY `character_classes_ibfk_1` (`character_id`),
  ADD KEY `character_classes_ibfk_2` (`class_id`);

--
-- Indexes for table `charging_skills`
--
ALTER TABLE `charging_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `charging_skills_ibfk_1` (`field_id`),
  ADD KEY `charging_skills_ibfk_2` (`participant_id`),
  ADD KEY `charging_skills_ibfk_3` (`skill_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `stats` (`stats_id`),
  ADD KEY `class_ibfk_1` (`start_weapon_id`);

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
  ADD KEY `location` (`area_id`);

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
-- Indexes for table `field`
--
ALTER TABLE `field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `participant_id` (`participant_id`),
  ADD KEY `field_ibfk_1` (`battle_id`);

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
  ADD KEY `itemtype_ibfk_1` (`type_id`),
  ADD KEY `itemtype_ibfk_2` (`rarity_id`),
  ADD KEY `itemtype_ibfk_3` (`stats_id`);

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
-- Indexes for table `npc_skills`
--
ALTER TABLE `npc_skills`
  ADD KEY `npc_skills_ibfk_1` (`npc_id`),
  ADD KEY `npc_skills_ibfk_2` (`skill_id`);

--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_effects`
--
ALTER TABLE `participant_effects`
  ADD KEY `effect` (`effect_id`),
  ADD KEY `enemy` (`participant_id`);

--
-- Indexes for table `participant_skills`
--
ALTER TABLE `participant_skills`
  ADD KEY `character` (`participant_id`),
  ADD KEY `skill` (`skill_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `battle`
--
ALTER TABLE `battle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `battle_messages`
--
ALTER TABLE `battle_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=860;

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `charging_skills`
--
ALTER TABLE `charging_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `enemy`
--
ALTER TABLE `enemy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT for table `field`
--
ALTER TABLE `field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1134;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `rarity`
--
ALTER TABLE `rarity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected_id`) REFERENCES `character` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `account_permissions`
--
ALTER TABLE `account_permissions`
  ADD CONSTRAINT `account_permissions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `battle`
--
ALTER TABLE `battle`
  ADD CONSTRAINT `battle_ibfk_1` FOREIGN KEY (`active_id`) REFERENCES `character` (`id`),
  ADD CONSTRAINT `battle_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`);

--
-- Constraints for table `battle_messages`
--
ALTER TABLE `battle_messages`
  ADD CONSTRAINT `battle_messages_ibfk_1` FOREIGN KEY (`battle_id`) REFERENCES `battle` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `character`
--
ALTER TABLE `character`
  ADD CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`id`),
  ADD CONSTRAINT `character_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `character_classes`
--
ALTER TABLE `character_classes`
  ADD CONSTRAINT `character_classes_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `character_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `charging_skills`
--
ALTER TABLE `charging_skills`
  ADD CONSTRAINT `charging_skills_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `charging_skills_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `charging_skills_ibfk_3` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`start_weapon_id`) REFERENCES `item` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `class_ibfk_2` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`);

--
-- Constraints for table `class_skills`
--
ALTER TABLE `class_skills`
  ADD CONSTRAINT `class_skills_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_skills_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `npc` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dungeon`
--
ALTER TABLE `dungeon`
  ADD CONSTRAINT `area_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);

--
-- Constraints for table `dungeon_npc`
--
ALTER TABLE `dungeon_npc`
  ADD CONSTRAINT `dungeon_npc_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dungeon_npc_ibfk_2` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enemy`
--
ALTER TABLE `enemy`
  ADD CONSTRAINT `enemy_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`),
  ADD CONSTRAINT `enemy_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evolution`
--
ALTER TABLE `evolution`
  ADD CONSTRAINT `evolution_ibfk_1` FOREIGN KEY (`from`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evolution_ibfk_2` FOREIGN KEY (`to`) REFERENCES `class` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `field`
--
ALTER TABLE `field`
  ADD CONSTRAINT `field_ibfk_1` FOREIGN KEY (`battle_id`) REFERENCES `battle` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `field_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `slot` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_4` FOREIGN KEY (`enchantment_id`) REFERENCES `enchantment` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `itemtype_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `itemtype` (`id`),
  ADD CONSTRAINT `itemtype_ibfk_2` FOREIGN KEY (`rarity_id`) REFERENCES `rarity` (`id`),
  ADD CONSTRAINT `itemtype_ibfk_3` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`);

--
-- Constraints for table `itemtype_relations`
--
ALTER TABLE `itemtype_relations`
  ADD CONSTRAINT `itemtype_relations_ibfk_1` FOREIGN KEY (`child`) REFERENCES `itemtype` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itemtype_relations_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `itemtype` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `npc_loot_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `npc_loot_ibfk_2` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `npc_skills`
--
ALTER TABLE `npc_skills`
  ADD CONSTRAINT `npc_skills_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `npc_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participant_effects`
--
ALTER TABLE `participant_effects`
  ADD CONSTRAINT `participant_effects_ibfk_1` FOREIGN KEY (`effect_id`) REFERENCES `effect` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participant_effects_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participant_skills`
--
ALTER TABLE `participant_skills`
  ADD CONSTRAINT `participant_skills_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participant_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `position_ibfk_3` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `race`
--
ALTER TABLE `race`
  ADD CONSTRAINT `race_ibfk_1` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
