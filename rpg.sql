-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: rpg
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password_hash` varchar(100) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `selected` (`selected_id`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected_id`) REFERENCES `character` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,'admin','$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6','2019-09-12 18:46:58',NULL),(2,'user','$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6','2019-05-20 08:21:49',NULL),(4,'Luis','$2y$10$wP5OEhJ.NjUZBOSpZ5KKbejZgxELrcuM/0NwA//JQrwTO0jYgntXu','2019-05-29 11:44:25',2),(5,'DerDruide','$2y$10$I9UzxQjPAfDPNqODmh7y/embo.A8YqWMK91Xq2zHXkCV.U3avgAWq','2019-05-29 11:44:28',3),(6,'GoodThor','$2y$10$OZHCNrEud4cL99M.Nvs4V.yue5iq6fm.WIeeTvhCb8OsD30Ck6Q1m','2019-07-10 18:53:18',NULL),(7,'tester','$2y$10$UT.5BWsZYGejUm2EDn94yemp1WL6S6qDZB1WWzGF5v66HM8bHGr.y','2019-09-17 08:24:58',4),(8,'xxx_darkoverlord_xxx','$2y$10$jnClTKPXbjNTtDBDyuu7oObN/9jfLBfK0Z1s0sOWwSDYwfRg1wmRK','2019-07-29 17:23:20',NULL);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_permissions`
--

DROP TABLE IF EXISTS `account_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `account_permissions` (
  `account_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_permissions`
--

LOCK TABLES `account_permissions` WRITE;
/*!40000 ALTER TABLE `account_permissions` DISABLE KEYS */;
INSERT INTO `account_permissions` VALUES (1,2),(1,3),(7,2),(1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(4,2),(5,2),(7,4);
/*!40000 ALTER TABLE `account_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'plain_fields',1,0,0,'forest'),(2,'beach',5,4,-7,NULL),(3,'forest',10,-10,2,'forest_2'),(4,'everlasting_battlefields',50,8,12,NULL);
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `battle`
--

DROP TABLE IF EXISTS `battle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `battle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active_id` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '1',
  `position_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active_id`),
  KEY `position` (`position_id`),
  CONSTRAINT `battle_ibfk_1` FOREIGN KEY (`active_id`) REFERENCES `character` (`id`),
  CONSTRAINT `battle_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `battle`
--

LOCK TABLES `battle` WRITE;
/*!40000 ALTER TABLE `battle` DISABLE KEYS */;
/*!40000 ALTER TABLE `battle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `battle_messages`
--

DROP TABLE IF EXISTS `battle_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `battle_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `battle_id` int(11) NOT NULL,
  `key` varchar(45) NOT NULL,
  `args` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `battle_messages`
--

LOCK TABLES `battle_messages` WRITE;
/*!40000 ALTER TABLE `battle_messages` DISABLE KEYS */;
INSERT INTO `battle_messages` VALUES (76,201,'Kurt ran away',NULL),(86,204,'Kurt ran away',NULL),(87,205,'Kurt ran away',NULL),(88,206,'Kurt ran away',NULL);
/*!40000 ALTER TABLE `battle_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `character`
--

DROP TABLE IF EXISTS `character`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `character` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `xp` int(10) unsigned NOT NULL DEFAULT '0',
  `level` int(10) DEFAULT '1',
  `account_id` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `participant_id` int(11) NOT NULL,
  `message` varchar(45) DEFAULT NULL,
  `skillpoints` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `race` (`race_id`),
  KEY `character_ibfk_3` (`account_id`),
  KEY `participant_id` (`participant_id`),
  CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`id`),
  CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `character`
--

LOCK TABLES `character` WRITE;
/*!40000 ALTER TABLE `character` DISABLE KEYS */;
INSERT INTO `character` VALUES (1,1,'Tom',0,1,1,'2019-05-03 15:47:17',78,NULL,0),(2,2,'The Rock',0,1,4,'2019-05-13 15:48:01',79,NULL,0),(3,3,'Groooover',5,1,5,'2019-05-20 08:25:24',80,NULL,0),(4,4,'Kurt',22,4,7,'2019-05-20 08:27:06',77,'level_up',101),(5,1,'Test Subject 24',0,1,7,'2019-05-20 11:57:21',81,NULL,0),(6,1,'HoyHoy',0,1,8,'2019-07-29 20:24:43',82,NULL,0),(7,1,'Peter',0,1,8,'2019-07-29 20:31:40',83,NULL,0),(8,1,'Kim',0,1,8,'2019-07-29 20:32:42',84,NULL,0),(9,1,'Troy',0,1,8,'2019-07-29 20:42:17',85,NULL,0),(10,1,'Timo',0,1,8,'2019-07-29 20:42:36',86,NULL,0);
/*!40000 ALTER TABLE `character` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `character_classes`
--

DROP TABLE IF EXISTS `character_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `character_classes` (
  `character_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `character_classes`
--

LOCK TABLES `character_classes` WRITE;
/*!40000 ALTER TABLE `character_classes` DISABLE KEYS */;
INSERT INTO `character_classes` VALUES (4,1),(1,1),(2,1),(3,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1);
/*!40000 ALTER TABLE `character_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `stats_id` int(11) NOT NULL DEFAULT '1',
  `start_weapon_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `stats` (`stats_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
INSERT INTO `class` VALUES (1,'apprentice',1,106),(2,'traveller',1,NULL),(3,'wild',1,NULL),(4,'fighter',1,NULL),(5,'psychic',1,NULL),(11,'druid',1,NULL),(12,'mage',1,NULL),(13,'alchemist',1,NULL),(21,'rogue',1,NULL),(22,'barde',1,NULL),(23,'smith',1,NULL),(31,'ranger',1,NULL),(32,'hermit',1,NULL),(41,'duelist',1,NULL),(42,'warrior',1,NULL),(43,'focused',1,NULL),(51,'priest',1,NULL),(52,'medium',1,NULL),(53,'telepath',1,NULL),(101,'shaman',1,NULL),(102,'necromancer',1,NULL),(104,'wizard',1,NULL),(105,'elementalist',1,NULL),(106,'infused',1,NULL),(107,'ritualist',1,NULL),(201,'assassin',1,NULL),(202,'swift',1,NULL),(203,'inventor',1,NULL),(204,'fool',1,NULL),(205,'performer',1,NULL),(301,'tamer',1,NULL),(303,'hunter',1,NULL),(304,'monk',1,NULL),(401,'rebel',1,NULL),(402,'hero',1,NULL),(403,'knight',1,NULL),(404,'berserk',1,NULL),(501,'cleric',1,NULL),(502,'thaumaturge',1,NULL),(503,'summoner',1,NULL),(1001,'driven',1,NULL),(1002,'sage',1,NULL),(1003,'beast',1,NULL),(1004,'fallen',1,NULL),(1005,'guardian',1,NULL),(1006,'chosen',1,NULL),(1007,'insane',1,NULL),(1008,'narrator',1,NULL),(1009,'imposter',1,NULL),(1010,'king',1,NULL);
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_skills`
--

DROP TABLE IF EXISTS `class_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `class_skills` (
  `class_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  KEY `class` (`class_id`),
  KEY `skill` (`skill_id`),
  KEY `teacher` (`teacher_id`),
  CONSTRAINT `class_skills_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  CONSTRAINT `class_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`),
  CONSTRAINT `class_skills_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `npc` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_skills`
--

LOCK TABLES `class_skills` WRITE;
/*!40000 ALTER TABLE `class_skills` DISABLE KEYS */;
INSERT INTO `class_skills` VALUES (1,101,0,NULL),(1,500,3,NULL);
/*!40000 ALTER TABLE `class_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dungeon`
--

DROP TABLE IF EXISTS `dungeon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dungeon` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `level` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `floors` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `mapX` int(11) DEFAULT NULL,
  `mapY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dungeon`
--

LOCK TABLES `dungeon` WRITE;
/*!40000 ALTER TABLE `dungeon` DISABLE KEYS */;
INSERT INTO `dungeon` VALUES (1,'some_crypt',1,1,5,'stone',-15,-4),(2,'a_big_hole',10,1,10,'moss',10,2);
/*!40000 ALTER TABLE `dungeon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dungeon_npc`
--

DROP TABLE IF EXISTS `dungeon_npc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dungeon_npc` (
  `dungeon_id` int(11) NOT NULL,
  `npc_id` int(11) NOT NULL,
  `minFloor` int(11) NOT NULL,
  `maxFloor` int(11) NOT NULL,
  KEY `dungeon` (`dungeon_id`),
  KEY `npc` (`npc_id`),
  CONSTRAINT `dungeon_npc_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`),
  CONSTRAINT `dungeon_npc_ibfk_2` FOREIGN KEY (`dungeon_id`) REFERENCES `dungeon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dungeon_npc`
--

LOCK TABLES `dungeon_npc` WRITE;
/*!40000 ALTER TABLE `dungeon_npc` DISABLE KEYS */;
INSERT INTO `dungeon_npc` VALUES (1,1,1,2),(1,2,2,4),(1,5,1,100);
/*!40000 ALTER TABLE `dungeon_npc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `effect`
--

DROP TABLE IF EXISTS `effect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `effect` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `fade_min` int(10) DEFAULT '-1',
  `fade_max` int(10) DEFAULT '-1',
  `block` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `effect`
--

LOCK TABLES `effect` WRITE;
/*!40000 ALTER TABLE `effect` DISABLE KEYS */;
INSERT INTO `effect` VALUES (1,'poison',5,7,0),(2,'stunned',1,2,1),(3,'burned',2,4,0),(4,'frozen',3,4,1),(5,'rage',2,4,0),(6,'asleep',2,4,1);
/*!40000 ALTER TABLE `effect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enchantment`
--

DROP TABLE IF EXISTS `enchantment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `enchantment` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enchantment`
--

LOCK TABLES `enchantment` WRITE;
/*!40000 ALTER TABLE `enchantment` DISABLE KEYS */;
/*!40000 ALTER TABLE `enchantment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enemy`
--

DROP TABLE IF EXISTS `enemy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `enemy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) DEFAULT NULL,
  `npc_id` int(11) NOT NULL,
  `suffix` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `battle` (`participant_id`),
  KEY `npc` (`npc_id`),
  CONSTRAINT `enemy_ibfk_1` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enemy`
--

LOCK TABLES `enemy` WRITE;
/*!40000 ALTER TABLE `enemy` DISABLE KEYS */;
/*!40000 ALTER TABLE `enemy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evolution`
--

DROP TABLE IF EXISTS `evolution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `evolution` (
  `level` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  KEY `from` (`from`),
  KEY `to` (`to`),
  CONSTRAINT `evolution_ibfk_1` FOREIGN KEY (`from`) REFERENCES `class` (`id`),
  CONSTRAINT `evolution_ibfk_2` FOREIGN KEY (`to`) REFERENCES `class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evolution`
--

LOCK TABLES `evolution` WRITE;
/*!40000 ALTER TABLE `evolution` DISABLE KEYS */;
INSERT INTO `evolution` VALUES (10,1,11),(10,1,12),(10,1,13),(10,4,41),(10,4,42),(10,3,42),(10,4,43),(10,3,31),(10,3,32),(10,2,21),(10,2,22),(10,2,23),(10,5,51),(10,5,52),(10,5,53),(20,11,101),(20,32,101),(20,11,102),(20,52,102),(20,12,104),(20,53,104),(20,12,105),(20,13,105),(20,13,106),(20,13,107),(20,51,107),(20,41,401),(20,41,402),(20,42,403),(20,42,404),(20,31,301),(20,31,303),(20,32,304),(20,23,304),(20,21,201),(20,21,202),(20,43,202),(20,23,203),(20,22,204),(20,22,205),(20,51,501),(20,42,501),(20,52,502),(20,52,503),(30,107,1001),(30,404,1001),(30,104,1002),(30,304,1002),(30,301,1003),(30,107,1003),(30,403,1004),(30,403,1005),(30,402,1006),(30,204,1007),(30,204,1008),(30,205,1009),(30,502,1009),(30,401,1010);
/*!40000 ALTER TABLE `evolution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `enchantment_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slot` (`slot_id`),
  KEY `enchantment` (`enchantment_id`),
  KEY `inventory_ibfk_1` (`character_id`),
  KEY `inventory_ibfk_2` (`item_id`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`),
  CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `slot` (`id`),
  CONSTRAINT `inventory_ibfk_4` FOREIGN KEY (`enchantment_id`) REFERENCES `enchantment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,4,103,1,NULL,1),(19,4,1,1,NULL,54),(45,4,210,3,NULL,1),(47,4,2,1,NULL,44),(48,4,3,1,NULL,45),(49,4,10,1,NULL,17),(100,4,100,1,NULL,1),(103,4,109,4,NULL,1);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `type_id` int(11) NOT NULL,
  `stackable` tinyint(1) NOT NULL DEFAULT '1',
  `rarity_id` int(11) NOT NULL DEFAULT '1',
  `stats_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `itemtype_ibfk_1` (`type_id`),
  CONSTRAINT `itemtype_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `itemtype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'health_potion',2,1,1,1),(2,'sleep_potion',2,1,1,1),(3,'poison_potion',2,1,1,1),(4,'honey',1,1,1,1),(10,'ambrosia',2,1,1,1),(100,'blade',7,0,2,100),(101,'bow',16,0,2,101),(102,'florett',17,0,2,102),(103,'maze',12,0,2,103),(104,'nunchuck',15,0,2,104),(105,'sceptre',8,0,2,105),(106,'wand',11,0,2,106),(107,'battlestaff',9,0,2,107),(108,'club',14,0,2,108),(109,'dagger',10,0,2,109),(110,'hammer',13,0,2,110),(200,'blade',7,0,3,200),(201,'bow',16,0,3,201),(202,'florett',17,0,3,202),(203,'maze',12,0,3,203),(204,'nunchuck',15,0,3,204),(205,'sceptre',8,0,3,205),(206,'wand',11,0,3,206),(207,'battlestaff',9,0,3,207),(208,'club',14,0,3,208),(209,'dagger',10,0,3,209),(210,'hammer',13,0,3,210),(300,'blade',7,0,6,300),(301,'bow',16,0,6,301),(302,'florett',17,0,6,302),(303,'maze',12,0,6,303),(304,'nunchuck',15,0,6,304),(305,'sceptre',8,0,6,305),(306,'wand',11,0,6,306),(307,'battlestaff',9,0,6,307),(308,'club',14,0,6,308),(309,'dagger',10,0,6,309),(310,'hammer',13,0,6,310);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemtype`
--

DROP TABLE IF EXISTS `itemtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `itemtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `icon` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemtype`
--

LOCK TABLES `itemtype` WRITE;
/*!40000 ALTER TABLE `itemtype` DISABLE KEYS */;
INSERT INTO `itemtype` VALUES (1,'object',0),(2,'potion',1),(3,'weapon',1),(4,'shield',0),(5,'armor',1),(6,'two_handed',0),(7,'blade',0),(8,'sceptre',0),(9,'battlestaff',0),(10,'dagger',0),(11,'wand',0),(12,'maze',0),(13,'hammer',0),(14,'club',0),(15,'nunchuck',0),(16,'bow',0),(17,'florett',0);
/*!40000 ALTER TABLE `itemtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemtype_relations`
--

DROP TABLE IF EXISTS `itemtype_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `itemtype_relations` (
  `child` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  KEY `child` (`child`),
  KEY `parent` (`parent`),
  CONSTRAINT `itemtype_relations_ibfk_1` FOREIGN KEY (`child`) REFERENCES `itemtype` (`id`),
  CONSTRAINT `itemtype_relations_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `itemtype` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemtype_relations`
--

LOCK TABLES `itemtype_relations` WRITE;
/*!40000 ALTER TABLE `itemtype_relations` DISABLE KEYS */;
INSERT INTO `itemtype_relations` VALUES (6,3),(9,6),(14,6),(10,3),(13,6),(12,3),(8,3),(7,3),(11,6),(16,6),(15,3),(17,3);
/*!40000 ALTER TABLE `itemtype_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `area_id` int(11) NOT NULL,
  `mapX` int(11) NOT NULL,
  `mapY` int(11) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area_id`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'starter_town',1,0,0,NULL),(2,'big_city',1,1,13,NULL),(11,'pirate_bar',2,0,0,NULL),(41,'the_origin',4,0,0,NULL);
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc`
--

DROP TABLE IF EXISTS `npc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `npc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `maxHealth` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rank` (`rank_id`),
  CONSTRAINT `npc_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `rank` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc`
--

LOCK TABLES `npc` WRITE;
/*!40000 ALTER TABLE `npc` DISABLE KEYS */;
INSERT INTO `npc` VALUES (1,1,'angry_bees',20,1),(2,10,'drake',180,1),(3,5,'eye',8,1),(4,15,'skull',16,1),(5,1,'slime',30,1);
/*!40000 ALTER TABLE `npc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_loot`
--

DROP TABLE IF EXISTS `npc_loot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `npc_loot` (
  `npc_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `chance` int(11) NOT NULL,
  KEY `npc` (`npc_id`),
  KEY `loot` (`item_id`),
  CONSTRAINT `npc_loot_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `npc_loot_ibfk_2` FOREIGN KEY (`npc_id`) REFERENCES `npc` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_loot`
--

LOCK TABLES `npc_loot` WRITE;
/*!40000 ALTER TABLE `npc_loot` DISABLE KEYS */;
INSERT INTO `npc_loot` VALUES (1,4,100);
/*!40000 ALTER TABLE `npc_loot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_skills`
--

DROP TABLE IF EXISTS `npc_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `npc_skills` (
  `npc_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_skills`
--

LOCK TABLES `npc_skills` WRITE;
/*!40000 ALTER TABLE `npc_skills` DISABLE KEYS */;
INSERT INTO `npc_skills` VALUES (2,1);
/*!40000 ALTER TABLE `npc_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant`
--

DROP TABLE IF EXISTS `participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `died` tinyint(1) NOT NULL DEFAULT '0',
  `joined` tinyint(1) NOT NULL DEFAULT '0',
  `health` int(11) NOT NULL DEFAULT '0',
  `battle_id` int(11) DEFAULT NULL,
  `side` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `battle` (`battle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant`
--

LOCK TABLES `participant` WRITE;
/*!40000 ALTER TABLE `participant` DISABLE KEYS */;
INSERT INTO `participant` VALUES (77,0,0,30,NULL,1),(78,0,0,100,NULL,0),(79,0,0,100,NULL,0),(80,0,0,100,NULL,0),(81,0,0,100,NULL,0),(82,0,0,100,NULL,0),(83,0,0,100,NULL,0),(84,0,0,100,NULL,0),(85,0,0,100,NULL,0),(86,0,0,100,NULL,0);
/*!40000 ALTER TABLE `participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant_effects`
--

DROP TABLE IF EXISTS `participant_effects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `participant_effects` (
  `participant_id` int(11) NOT NULL,
  `effect_id` int(11) NOT NULL,
  `countdown` int(10) NOT NULL,
  KEY `effect` (`effect_id`),
  KEY `enemy` (`participant_id`),
  CONSTRAINT `participant_effects_ibfk_1` FOREIGN KEY (`effect_id`) REFERENCES `effect` (`id`),
  CONSTRAINT `participant_effects_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant_effects`
--

LOCK TABLES `participant_effects` WRITE;
/*!40000 ALTER TABLE `participant_effects` DISABLE KEYS */;
/*!40000 ALTER TABLE `participant_effects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant_skills`
--

DROP TABLE IF EXISTS `participant_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `participant_skills` (
  `participant_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `nextUse` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `character` (`participant_id`),
  KEY `skill` (`skill_id`),
  CONSTRAINT `participant_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant_skills`
--

LOCK TABLES `participant_skills` WRITE;
/*!40000 ALTER TABLE `participant_skills` DISABLE KEYS */;
INSERT INTO `participant_skills` VALUES (77,101,0);
/*!40000 ALTER TABLE `participant_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'user'),(2,'tester'),(3,'admin'),(4,'create_chars');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL DEFAULT '1',
  `dungeon_id` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `foundStairs` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location` (`location_id`),
  KEY `dungeon` (`dungeon_id`),
  CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id`) REFERENCES `character` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (1,1,NULL,NULL,NULL,0),(2,41,NULL,NULL,NULL,0),(3,1,1,1,0,0),(4,1,1,3,12,1),(5,1,NULL,1,0,0),(6,1,NULL,NULL,NULL,0),(7,1,NULL,NULL,NULL,0),(8,1,NULL,NULL,NULL,0),(9,1,NULL,NULL,NULL,0),(10,1,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `race`
--

DROP TABLE IF EXISTS `race`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `race` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `strength` int(11) NOT NULL,
  `resistance` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `skin` varchar(6) NOT NULL,
  `stats_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stats` (`stats_id`),
  CONSTRAINT `race_ibfk_1` FOREIGN KEY (`stats_id`) REFERENCES `stats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `race`
--

LOCK TABLES `race` WRITE;
/*!40000 ALTER TABLE `race` DISABLE KEYS */;
INSERT INTO `race` VALUES (1,'human',10,10,10,'ffcfa3',1),(2,'troll',12,15,3,'',1),(3,'nymph',5,8,17,'669619',1),(4,'shade',4,8,18,'',1);
/*!40000 ALTER TABLE `race` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rank` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rank`
--

LOCK TABLES `rank` WRITE;
/*!40000 ALTER TABLE `rank` DISABLE KEYS */;
INSERT INTO `rank` VALUES (1,'monster'),(2,'rare'),(3,'boss'),(4,'god');
/*!40000 ALTER TABLE `rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rarity`
--

DROP TABLE IF EXISTS `rarity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rarity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rarity`
--

LOCK TABLES `rarity` WRITE;
/*!40000 ALTER TABLE `rarity` DISABLE KEYS */;
INSERT INTO `rarity` VALUES (1,'none',NULL),(2,'rusty','8a4722'),(3,'sharp','a18f85'),(6,'royal','ba7713');
/*!40000 ALTER TABLE `rarity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skill`
--

DROP TABLE IF EXISTS `skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `timeout` int(10) unsigned NOT NULL,
  `cost` int(10) unsigned NOT NULL,
  `group` tinyint(1) NOT NULL,
  `affectDead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill`
--

LOCK TABLES `skill` WRITE;
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;
INSERT INTO `skill` VALUES (1,'slash',0,1,0,0),(2,'backstab',0,1,0,0),(51,'heal',0,2,0,0),(52,'cleansing Rain',0,2,1,0),(53,'revive',0,5,0,1),(101,'pulse',0,1,0,0),(102,'rumble',0,1,1,0),(500,'glow',0,2,1,0);
/*!40000 ALTER TABLE `skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slot`
--

DROP TABLE IF EXISTS `slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `slot` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `space` int(11) DEFAULT '1',
  `apply_stats` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slot`
--

LOCK TABLES `slot` WRITE;
/*!40000 ALTER TABLE `slot` DISABLE KEYS */;
INSERT INTO `slot` VALUES (1,'inventory',20,0),(2,'left_hand',1,1),(3,'right_hand',1,1),(4,'loot',20,0);
/*!40000 ALTER TABLE `slot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wisdom` int(11) NOT NULL DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '0',
  `agility` int(11) NOT NULL DEFAULT '0',
  `luck` int(11) NOT NULL DEFAULT '0',
  `resistance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
INSERT INTO `stats` VALUES (1,0,0,0,0,0),(12,35,10,20,25,10),(13,10,25,25,15,25),(14,10,22,28,30,10),(15,10,27,23,20,20),(16,20,30,10,15,35),(17,35,10,23,27,15),(18,20,15,30,30,15),(19,40,15,15,25,15),(20,30,28,20,18,14),(100,0,5,2,0,0),(101,0,3,2,4,0),(102,0,3,4,2,0),(103,0,7,0,2,0),(104,1,2,4,1,0),(105,6,0,0,1,0),(106,4,1,0,0,2),(107,1,3,2,0,2),(108,-2,7,-1,1,2),(109,0,3,4,0,0),(110,0,5,-3,0,2),(200,0,10,4,0,0),(201,0,6,4,8,0),(202,0,6,8,4,0),(203,0,14,0,4,0),(204,2,4,8,2,0),(205,12,0,0,2,0),(206,8,2,0,0,4),(207,2,6,4,0,4),(208,-3,14,-2,2,4),(209,0,6,8,0,0),(210,0,10,-4,0,4),(300,0,15,6,0,0),(301,0,9,6,12,0),(302,0,9,12,6,0),(303,0,21,0,6,0),(304,3,6,12,3,0),(305,18,0,0,3,0),(306,12,3,0,0,6),(307,3,9,6,0,6),(308,-4,21,-3,3,6),(309,0,9,12,0,0),(310,0,15,-5,0,6);
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-21 18:05:12
