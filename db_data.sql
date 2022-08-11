-- Adminer 4.8.1 MySQL 8.0.30 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

TRUNCATE `area`;
INSERT INTO `area` (`id`, `name`, `level`, `mapX`, `mapY`, `icon`) VALUES
(1,	'plain_fields',	1,	0,	0,	'forest'),
(2,	'beach',	5,	4,	-7,	NULL),
(3,	'forest',	10,	-10,	2,	'forest_2'),
(4,	'everlasting_battlefields',	50,	8,	12,	NULL);

TRUNCATE `class_skills`;
INSERT INTO `class_skills` (`class_id`, `skill_id`, `level`, `teacher_id`) VALUES
(1,	101,	0,	NULL),
(1,	102,	0,	NULL),
(1,	51,	3,	NULL),
(1,	103,	0,	NULL),
(1,	120,	0,	NULL);

TRUNCATE `dungeon`;
INSERT INTO `dungeon` (`id`, `name`, `level`, `area_id`, `floors`, `icon`, `mapX`, `mapY`) VALUES
(1,	'some_crypt',	1,	1,	5,	'stone',	-15,	-4),
(2,	'a_big_hole',	10,	1,	10,	'moss',	10,	2);

TRUNCATE `dungeon_npc`;
INSERT INTO `dungeon_npc` (`dungeon_id`, `npc_id`, `minFloor`, `maxFloor`) VALUES
(1,	1,	1,	2),
(1,	2,	2,	4),
(1,	5,	1,	100);

TRUNCATE `evolution`;
INSERT INTO `evolution` (`level`, `from`, `to`) VALUES
(10,	1,	11),
(10,	1,	12),
(10,	1,	13),
(10,	4,	41),
(10,	4,	42),
(10,	3,	42),
(10,	4,	43),
(10,	3,	31),
(10,	3,	32),
(10,	2,	21),
(10,	2,	22),
(10,	2,	23),
(10,	5,	51),
(10,	5,	52),
(10,	5,	53),
(20,	11,	101),
(20,	32,	101),
(20,	11,	102),
(20,	52,	102),
(20,	12,	104),
(20,	53,	104),
(20,	12,	105),
(20,	13,	105),
(20,	13,	106),
(20,	13,	107),
(20,	51,	107),
(20,	11,	108),
(20,	12,	109),
(20,	53,	110),
(20,	41,	401),
(20,	41,	402),
(20,	41,	403),
(20,	42,	404),
(20,	42,	405),
(20,	43,	406),
(20,	31,	301),
(20,	31,	303),
(20,	32,	304),
(20,	43,	304),
(20,	21,	201),
(20,	31,	201),
(20,	21,	202),
(20,	43,	202),
(20,	21,	203),
(20,	32,	203),
(20,	23,	204),
(20,	22,	205),
(20,	22,	206),
(20,	22,	207),
(20,	52,	207),
(20,	23,	208),
(20,	51,	501),
(20,	42,	501),
(20,	52,	502),
(20,	53,	504),
(30,	107,	1001),
(30,	404,	1001),
(30,	104,	1002),
(30,	304,	1002),
(30,	301,	1003),
(30,	106,	1003),
(30,	403,	1004),
(30,	403,	1005),
(30,	101,	1005),
(30,	109,	1005),
(30,	406,	1005),
(30,	402,	1006),
(30,	205,	1007),
(30,	205,	1008),
(30,	110,	1008),
(30,	206,	1009),
(30,	502,	1009),
(30,	401,	1010),
(30,	207,	1010),
(30,	105,	1011),
(30,	501,	1012),
(30,	108,	1012),
(30,	504,	1013),
(30,	303,	1014),
(30,	204,	1016),
(30,	102,	1016),
(30,	107,	1017),
(30,	402,	1017),
(30,	203,	1018),
(30,	109,	1018);

TRUNCATE `itemtype`;
INSERT INTO `itemtype` (`id`, `name`, `icon`) VALUES
(1,	'object',	0),
(2,	'potion',	1),
(3,	'weapon',	1),
(4,	'shield',	0),
(5,	'armor',	1),
(6,	'two_handed',	0),
(7,	'blade',	0),
(8,	'sceptre',	0),
(9,	'battlestaff',	0),
(10,	'dagger',	0),
(11,	'wand',	0),
(12,	'maze',	0),
(13,	'hammer',	0),
(14,	'club',	0),
(15,	'nunchuck',	0),
(16,	'bow',	0),
(17,	'florett',	0);

TRUNCATE `itemtype_relations`;
INSERT INTO `itemtype_relations` (`child`, `parent`) VALUES
(6,	3),
(9,	6),
(14,	6),
(10,	3),
(13,	6),
(12,	3),
(8,	3),
(7,	3),
(11,	6),
(16,	6),
(15,	3),
(17,	3);

TRUNCATE `location`;
INSERT INTO `location` (`id`, `name`, `area_id`, `mapX`, `mapY`, `icon`) VALUES
(1,	'starter_town',	1,	0,	0,	NULL),
(2,	'big_city',	1,	1,	13,	NULL),
(11,	'pirate_bar',	2,	0,	0,	NULL),
(41,	'the_origin',	4,	0,	0,	NULL);

TRUNCATE `npc`;
INSERT INTO `npc` (`id`, `level`, `name`, `maxHealth`, `rank_id`) VALUES
(1,	1,	'angry_bees',	20,	1),
(2,	10,	'drake',	180,	1),
(3,	5,	'eye',	8,	1),
(4,	15,	'skull',	16,	1),
(5,	1,	'slime',	30,	1);

TRUNCATE `permission`;
INSERT INTO `permission` (`id`, `name`) VALUES
(1,	'user'),
(2,	'tester'),
(3,	'editor'),
(4,	'create_chars');

TRUNCATE `rank`;
INSERT INTO `rank` (`id`, `name`) VALUES
(1,	'monster'),
(2,	'rare'),
(3,	'boss'),
(4,	'god');

TRUNCATE `rarity`;
INSERT INTO `rarity` (`id`, `name`, `color`) VALUES
(1,	'none',	NULL),
(2,	'rusty',	'8a4722'),
(3,	'sharp',	'a18f85'),
(6,	'royal',	'ba7713');

TRUNCATE `stats`;
INSERT INTO `stats` (`id`, `wisdom`, `strength`, `agility`, `luck`, `resistance`) VALUES
(1,	0,	0,	0,	0,	0),
(12,	35,	10,	20,	25,	10),
(13,	10,	25,	25,	15,	25),
(14,	10,	22,	28,	30,	10),
(15,	10,	27,	23,	20,	20),
(16,	20,	30,	10,	15,	35),
(17,	35,	10,	23,	27,	15),
(18,	20,	15,	30,	30,	15),
(19,	40,	15,	15,	25,	15),
(20,	30,	28,	20,	18,	14),
(100,	0,	5,	2,	0,	0),
(101,	0,	3,	2,	4,	0),
(102,	0,	3,	4,	2,	0),
(103,	0,	7,	0,	2,	0),
(104,	1,	2,	4,	1,	0),
(105,	6,	0,	0,	1,	0),
(106,	4,	1,	0,	0,	2),
(107,	1,	3,	2,	0,	2),
(108,	-2,	7,	-1,	1,	2),
(109,	0,	3,	4,	0,	0),
(110,	0,	5,	-3,	0,	2),
(200,	0,	10,	4,	0,	0),
(201,	0,	6,	4,	8,	0),
(202,	0,	6,	8,	4,	0),
(203,	0,	14,	0,	4,	0),
(204,	2,	4,	8,	2,	0),
(205,	12,	0,	0,	2,	0),
(206,	8,	2,	0,	0,	4),
(207,	2,	6,	4,	0,	4),
(208,	-3,	14,	-2,	2,	4),
(209,	0,	6,	8,	0,	0),
(210,	0,	10,	-4,	0,	4),
(300,	0,	15,	6,	0,	0),
(301,	0,	9,	6,	12,	0),
(302,	0,	9,	12,	6,	0),
(303,	0,	21,	0,	6,	0),
(304,	3,	6,	12,	3,	0),
(305,	18,	0,	0,	3,	0),
(306,	12,	3,	0,	0,	6),
(307,	3,	9,	6,	0,	6),
(308,	-4,	21,	-3,	3,	6),
(309,	0,	9,	12,	0,	0),
(310,	0,	15,	-5,	0,	6);

-- 2022-08-11 13:23:36