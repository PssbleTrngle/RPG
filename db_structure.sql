
-- Adminer 4.8.1 MySQL 8.0.30 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password_hash` varchar(100) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `selected` (`selected_id`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected_id`) REFERENCES `character` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `account_permissions`;
CREATE TABLE `account_permissions` (
  `account_id` int NOT NULL,
  `permission_id` int NOT NULL,
  KEY `account_permissions_ibfk_1` (`account_id`),
  KEY `account_permissions_ibfk_2` (`permission_id`),
  CONSTRAINT `account_permissions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  CONSTRAINT `account_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` int NOT NULL,
  `mapX` int NOT NULL,
  `mapY` int NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `battle`;
CREATE TABLE `battle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `active_id` int NOT NULL,
  `round` int NOT NULL DEFAULT '1',
  `position_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active_id`),
  KEY `position` (`position_id`),
  CONSTRAINT `battle_ibfk_1` FOREIGN KEY (`active_id`) REFERENCES `character` (`id`),
  CONSTRAINT `battle_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `battle_messages`;
CREATE TABLE `battle_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `battle_id` int NOT NULL,
  `key` varchar(45) NOT NULL,
  `args` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `battle_messages_ibfk_1` (`battle_id`),
  CONSTRAINT `battle_messages_ibfk_1` FOREIGN KEY (`battle_id`) REFERENCES `battle` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `character`;
CREATE TABLE `character` (
  `id` int NOT NULL AUTO_INCREMENT,
  `race_id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `xp` int unsigned NOT NULL DEFAULT '0',
  `level` int DEFAULT '1',
  `account_id` int DEFAULT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `participant_id` int NOT NULL,
  `message` varchar(45) DEFAULT NULL,
  `skillpoints` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `race` (`race_id`),
  KEY `participant_id` (`participant_id`),
  KEY `character_ibfk_3` (`account_id`),
  CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`id`),
  CONSTRAINT `character_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `character_classes`;
CREATE TABLE `character_classes` (
  `character_id` int NOT NULL,
  `class_id` int NOT NULL,
  KEY `character_classes_ibfk_1` (`character_id`),
  KEY `character_classes_ibfk_2` (`class_id`),
  CONSTRAINT `character_classes_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  CONSTRAINT `character_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `charging_skills`;
CREATE TABLE `charging_skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `skill_id` int NOT NULL,
  `participant_id` int DEFAULT NULL,
  `field_id` int NOT NULL,
  `countdown` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `charging_skills_ibfk_1` (`field_id`),
  KEY `charging_skills_ibfk_2` (`participant_id`),
  KEY `charging_skills_ibfk_3` (`skill_id`),
  CONSTRAINT `charging_skills_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`) ON DELETE CASCADE,
  CONSTRAINT `charging_skills_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE SET NULL,
  CONSTRAINT `charging_skills_ibfk_3` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `stats_id` int NOT NULL DEFAULT '1',
  `start_weapon_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `stats` (`stats_id`),
  KEY `class_ibfk_1` (`start_weapon_id`),
  CONSTRAINT `class_ibfk_1` FOREIGN KEY (`start_weapon_id`) REFERENCES `item` (`id`) ON DELETE SET NULL,
  CONSTRAINT `class_ibfk_2` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `class_skills`;
CREATE TABLE `class_skills` (
  `class_id` int NOT NULL,
  `skill_id` int NOT NULL,
  `level` int unsigned NOT NULL,
  `teacher_id` int DEFAULT NULL,
  KEY `class` (`class_id`),
  KEY `skill` (`skill_id`),
  KEY `teacher` (`teacher_id`),
  CONSTRAINT `class_skills_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_skills_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `npc` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `dungeon`;
CREATE TABLE `dungeon` (
  `id` int NOT NULL,
  `name` varchar(40) NOT NULL,
  `level` int NOT NULL,
  `area_id` int NOT NULL,
  `floors` int NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `mapX` int DEFAULT NULL,
  `mapY` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`area_id`),
  CONSTRAINT `area_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `dungeon_npc`;
CREATE TABLE `dungeon_npc` (
  `dungeon_id` int NOT NULL,
  `npc_id` int NOT NULL,
  `minFloor` int NOT NULL,
  `maxFloor` int NOT NULL,
  KEY `dungeon` (`dungeon_id`),
  KEY `npc` (`npc_id`),
  CONSTRAINT `dungeon_npc_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dungeon_npc_ibfk_2` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `effect`;
CREATE TABLE `effect` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `fade_min` int DEFAULT '-1',
  `fade_max` int DEFAULT '-1',
  `block` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `enchantment`;
CREATE TABLE `enchantment` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `enemy`;
CREATE TABLE `enemy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `participant_id` int DEFAULT NULL,
  `npc_id` int NOT NULL,
  `suffix` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `battle` (`participant_id`),
  KEY `npc` (`npc_id`),
  CONSTRAINT `enemy_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`),
  CONSTRAINT `enemy_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `evolution`;
CREATE TABLE `evolution` (
  `level` int NOT NULL,
  `from` int NOT NULL,
  `to` int NOT NULL,
  KEY `from` (`from`),
  KEY `to` (`to`),
  CONSTRAINT `evolution_ibfk_1` FOREIGN KEY (`from`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evolution_ibfk_2` FOREIGN KEY (`to`) REFERENCES `class` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `field`;
CREATE TABLE `field` (
  `id` int NOT NULL AUTO_INCREMENT,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `participant_id` int DEFAULT NULL,
  `battle_id` int NOT NULL,
  `spawn` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participant_id` (`participant_id`),
  KEY `field_ibfk_1` (`battle_id`),
  CONSTRAINT `field_ibfk_1` FOREIGN KEY (`battle_id`) REFERENCES `battle` (`id`) ON DELETE CASCADE,
  CONSTRAINT `field_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `character_id` int NOT NULL,
  `item_id` int NOT NULL,
  `slot_id` int NOT NULL,
  `enchantment_id` int DEFAULT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slot` (`slot_id`),
  KEY `enchantment` (`enchantment_id`),
  KEY `inventory_ibfk_1` (`character_id`),
  KEY `inventory_ibfk_2` (`item_id`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `slot` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_ibfk_4` FOREIGN KEY (`enchantment_id`) REFERENCES `enchantment` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `type_id` int NOT NULL,
  `stackable` tinyint(1) NOT NULL DEFAULT '1',
  `rarity_id` int NOT NULL DEFAULT '1',
  `stats_id` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `itemtype_ibfk_1` (`type_id`),
  KEY `itemtype_ibfk_2` (`rarity_id`),
  KEY `itemtype_ibfk_3` (`stats_id`),
  CONSTRAINT `itemtype_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `itemtype` (`id`),
  CONSTRAINT `itemtype_ibfk_2` FOREIGN KEY (`rarity_id`) REFERENCES `rarity` (`id`),
  CONSTRAINT `itemtype_ibfk_3` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `itemtype`;
CREATE TABLE `itemtype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `icon` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `itemtype_relations`;
CREATE TABLE `itemtype_relations` (
  `child` int NOT NULL,
  `parent` int NOT NULL,
  KEY `child` (`child`),
  KEY `parent` (`parent`),
  CONSTRAINT `itemtype_relations_ibfk_1` FOREIGN KEY (`child`) REFERENCES `itemtype` (`id`) ON DELETE CASCADE,
  CONSTRAINT `itemtype_relations_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `itemtype` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `area_id` int NOT NULL,
  `mapX` int NOT NULL,
  `mapY` int NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area_id`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `npc`;
CREATE TABLE `npc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `maxHealth` int NOT NULL,
  `rank_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rank` (`rank_id`),
  CONSTRAINT `npc_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `rank` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `npc_loot`;
CREATE TABLE `npc_loot` (
  `npc_id` int NOT NULL,
  `item_id` int NOT NULL,
  `chance` int NOT NULL,
  KEY `npc` (`npc_id`),
  KEY `loot` (`item_id`),
  CONSTRAINT `npc_loot_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `npc_loot_ibfk_2` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `npc_skills`;
CREATE TABLE `npc_skills` (
  `npc_id` int NOT NULL,
  `skill_id` int NOT NULL,
  KEY `npc_skills_ibfk_1` (`npc_id`),
  KEY `npc_skills_ibfk_2` (`skill_id`),
  CONSTRAINT `npc_skills_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`) ON DELETE CASCADE,
  CONSTRAINT `npc_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `died` tinyint(1) NOT NULL DEFAULT '0',
  `joined` tinyint(1) NOT NULL DEFAULT '0',
  `health` int NOT NULL DEFAULT '0',
  `side` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `participant_effects`;
CREATE TABLE `participant_effects` (
  `participant_id` int NOT NULL,
  `effect_id` int NOT NULL,
  `countdown` int NOT NULL,
  KEY `effect` (`effect_id`),
  KEY `enemy` (`participant_id`),
  CONSTRAINT `participant_effects_ibfk_1` FOREIGN KEY (`effect_id`) REFERENCES `effect` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participant_effects_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `participant_skills`;
CREATE TABLE `participant_skills` (
  `participant_id` int NOT NULL,
  `skill_id` int NOT NULL,
  `nextUse` int unsigned NOT NULL DEFAULT '0',
  KEY `character` (`participant_id`),
  KEY `skill` (`skill_id`),
  CONSTRAINT `participant_skills_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participant_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `id` int NOT NULL,
  `location_id` int NOT NULL DEFAULT '1',
  `dungeon_id` int DEFAULT NULL,
  `floor` int DEFAULT NULL,
  `attempts` int DEFAULT NULL,
  `foundStairs` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location` (`location_id`),
  KEY `dungeon` (`dungeon_id`),
  CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id`) REFERENCES `character` (`id`) ON DELETE CASCADE,
  CONSTRAINT `position_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  CONSTRAINT `position_ibfk_3` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `race`;
CREATE TABLE `race` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `strength` int NOT NULL,
  `resistance` int NOT NULL,
  `agility` int NOT NULL,
  `skin` varchar(6) NOT NULL,
  `stats_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stats` (`stats_id`),
  CONSTRAINT `race_ibfk_1` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `rank`;
CREATE TABLE `rank` (
  `id` int NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `rarity`;
CREATE TABLE `rarity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `skill`;
CREATE TABLE `skill` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `timeout` int unsigned NOT NULL,
  `charge` int NOT NULL DEFAULT '0',
  `cost` int unsigned NOT NULL,
  `range` tinyint(1) NOT NULL,
  `affectDead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `slot`;
CREATE TABLE `slot` (
  `id` int NOT NULL,
  `name` varchar(10) NOT NULL,
  `space` int DEFAULT '1',
  `apply_stats` tinyint DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `wisdom` int NOT NULL DEFAULT '0',
  `strength` int NOT NULL DEFAULT '0',
  `agility` int NOT NULL DEFAULT '0',
  `luck` int NOT NULL DEFAULT '0',
  `resistance` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- 2022-08-11 13:03:33
