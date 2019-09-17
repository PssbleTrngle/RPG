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
  `status_id` int(11) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selected_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status_id`),
  KEY `selected` (`selected_id`),
  CONSTRAINT `account_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`selected_id`) REFERENCES `character` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,'admin','$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6',100,'2019-09-12 18:46:58',NULL),(2,'user','$2y$10$zSSTHduqltm3mIxIdsGvduwYn2oWd0aNS5QPCams6z084S8956kt6',1,'2019-05-20 08:21:49',NULL),(4,'Luis','$2y$10$wP5OEhJ.NjUZBOSpZ5KKbejZgxELrcuM/0NwA//JQrwTO0jYgntXu',50,'2019-05-29 11:44:25',2),(5,'DerDruide','$2y$10$I9UzxQjPAfDPNqODmh7y/embo.A8YqWMK91Xq2zHXkCV.U3avgAWq',50,'2019-05-29 11:44:28',3),(6,'GoodThor','$2y$10$OZHCNrEud4cL99M.Nvs4V.yue5iq6fm.WIeeTvhCb8OsD30Ck6Q1m',1,'2019-07-10 18:53:18',NULL),(7,'tester','$2y$10$UT.5BWsZYGejUm2EDn94yemp1WL6S6qDZB1WWzGF5v66HM8bHGr.y',100,'2019-09-16 17:49:34',5),(8,'xxx_darkoverlord_xxx','$2y$10$jnClTKPXbjNTtDBDyuu7oObN/9jfLBfK0Z1s0sOWwSDYwfRg1wmRK',100,'2019-07-29 17:23:20',NULL);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `battle`
--

LOCK TABLES `battle` WRITE;
/*!40000 ALTER TABLE `battle` DISABLE KEYS */;
INSERT INTO `battle` VALUES (162,4,1,4),(163,4,1,4),(164,4,1,4),(165,4,1,4),(166,4,1,4),(167,4,1,4),(168,4,1,4),(169,4,1,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `battle_messages`
--

LOCK TABLES `battle_messages` WRITE;
/*!40000 ALTER TABLE `battle_messages` DISABLE KEYS */;
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
  `class_id` int(11) NOT NULL,
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
  KEY `class` (`class_id`),
  KEY `character_ibfk_3` (`account_id`),
  KEY `participant_id` (`participant_id`),
  CONSTRAINT `character_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `race` (`id`),
  CONSTRAINT `character_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  CONSTRAINT `character_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `character`
--

LOCK TABLES `character` WRITE;
/*!40000 ALTER TABLE `character` DISABLE KEYS */;
INSERT INTO `character` VALUES (1,1,21,'Tom',0,1,1,'2019-05-03 15:47:17',78,NULL,0),(2,2,2,'The Rock',0,1,4,'2019-05-13 15:48:01',79,NULL,0),(3,3,111,'Groooover',5,1,5,'2019-05-20 08:25:24',80,NULL,0),(4,4,1,'Kurt',13,3,7,'2019-05-20 08:27:06',77,'ran',2),(5,1,23,'Test Subject 24',0,1,7,'2019-05-20 11:57:21',81,NULL,0),(6,1,3,'HoyHoy',0,1,8,'2019-07-29 20:24:43',82,NULL,0),(7,1,2,'Peter',0,1,8,'2019-07-29 20:31:40',83,NULL,0),(8,1,1,'Kim',0,1,8,'2019-07-29 20:32:42',84,NULL,0),(9,1,2,'Troy',0,1,8,'2019-07-29 20:42:17',85,NULL,0),(10,1,4,'Timo',0,1,8,'2019-07-29 20:42:36',86,NULL,0);
/*!40000 ALTER TABLE `character` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `character_skills`
--

DROP TABLE IF EXISTS `character_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `character_skills` (
  `character_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `nextUse` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `character` (`character_id`),
  KEY `skill` (`skill_id`),
  CONSTRAINT `character_skills_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `character` (`id`),
  CONSTRAINT `character_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `character_skills`
--

LOCK TABLES `character_skills` WRITE;
/*!40000 ALTER TABLE `character_skills` DISABLE KEYS */;
INSERT INTO `character_skills` VALUES (3,101,0),(4,101,0);
/*!40000 ALTER TABLE `character_skills` ENABLE KEYS */;
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
  `stats_id` int(11) DEFAULT NULL,
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
INSERT INTO `class` VALUES (1,'apprentice',12,106),(2,'warrior',13,100),(3,'rogue',14,110),(4,'wild',15,109),(21,'knight',16,NULL),(22,'shaman',17,NULL),(23,'ranger',18,NULL),(24,'mage',19,NULL),(25,'alchemist',20,NULL),(26,'tamer',1,NULL),(27,'berserk',1,NULL),(28,'assassin',1,NULL),(29,'traveller',1,NULL),(30,'fighter',1,NULL),(100,'reaper',1,NULL),(101,'swift',1,NULL),(102,'focused',1,NULL),(103,'guardian',1,NULL),(104,'fallen',1,NULL),(105,'kamikaze',1,NULL),(106,'hunter',1,NULL),(107,'beast',1,NULL),(108,'infused',1,NULL),(109,'elementalist',1,NULL),(110,'sage',1,NULL),(111,'druid',1,NULL),(112,'necromancer',1,NULL),(113,'driven',1,NULL),(114,'bard',1,NULL),(400,'wizard',1,NULL),(401,'touched',1,NULL),(402,'spirit',1,NULL),(403,'gate',1,NULL),(404,'narrator',1,NULL),(405,'fate',1,NULL),(406,'death',1,NULL),(407,'wind',1,NULL),(408,'seal',1,NULL),(409,'truth',1,NULL),(410,'pain',1,NULL);
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
INSERT INTO `dungeon_npc` VALUES (1,1,1,2),(2,2,2,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
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
INSERT INTO `evolution` VALUES (10,1,25),(10,1,24),(10,1,22),(10,4,23),(10,4,26),(10,4,27),(10,2,30),(10,2,27),(10,2,21),(10,3,28),(10,3,29),(20,23,106),(20,27,105),(20,21,104),(20,21,103),(20,30,102),(20,30,101),(20,28,101),(20,28,100),(20,29,114),(20,29,113),(20,22,112),(20,22,111),(20,24,110),(20,25,109),(20,25,108),(20,26,107),(30,105,410),(30,104,409),(30,103,408),(30,101,407),(30,100,406),(30,100,405),(30,114,404),(30,113,403),(30,112,403),(30,111,402),(30,110,401),(30,109,400),(30,110,400);
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
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,4,103,1,NULL,1),(19,4,1,1,NULL,9),(45,4,210,2,NULL,1),(47,4,2,1,NULL,48);
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
INSERT INTO `item` VALUES (1,'health_potion',2,1,1),(2,'honey',1,1,1),(3,'poison',2,1,1),(100,'blade',7,0,2),(101,'bow',16,0,2),(102,'florett',17,0,2),(103,'maze',12,0,2),(104,'nunchuck',15,0,2),(105,'sceptre',8,0,2),(106,'wand',11,0,2),(107,'battlestaff',9,0,2),(108,'club',14,0,2),(109,'dagger',10,0,2),(110,'hammer',13,0,2),(200,'blade',7,0,3),(201,'bow',16,0,3),(202,'florett',17,0,3),(203,'maze',12,0,3),(204,'nunchuck',15,0,3),(205,'sceptre',8,0,3),(206,'wand',11,0,3),(207,'battlestaff',9,0,3),(208,'club',14,0,3),(209,'dagger',10,0,3),(210,'hammer',13,0,3),(300,'blade',7,0,6),(301,'bow',16,0,6),(302,'florett',17,0,6),(303,'maze',12,0,6),(304,'nunchuck',15,0,6),(305,'sceptre',8,0,6),(306,'wand',11,0,6),(307,'battlestaff',9,0,6),(308,'club',14,0,6),(309,'dagger',10,0,6),(310,'hammer',13,0,6);
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
INSERT INTO `itemtype` VALUES (1,'object',0),(2,'potion',1),(3,'weapon',1),(4,'shield',0),(5,'armor',1),(6,'two-handed',0),(7,'blade',0),(8,'sceptre',0),(9,'battlestaff',0),(10,'dagger',0),(11,'wand',0),(12,'maze',0),(13,'hammer',0),(14,'club',0),(15,'nunchuck',0),(16,'bow',0),(17,'florett',0);
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
INSERT INTO `npc_loot` VALUES (1,2,100);
/*!40000 ALTER TABLE `npc_loot` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `battle` (`battle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant`
--

LOCK TABLES `participant` WRITE;
/*!40000 ALTER TABLE `participant` DISABLE KEYS */;
INSERT INTO `participant` VALUES (77,0,0,58,NULL),(78,0,0,100,NULL),(79,0,0,100,NULL),(80,0,0,100,NULL),(81,0,0,100,NULL),(82,0,0,100,NULL),(83,0,0,100,NULL),(84,0,0,100,NULL),(85,0,0,100,NULL),(86,0,0,100,NULL);
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
INSERT INTO `position` VALUES (1,1,NULL,NULL,NULL,0),(2,41,NULL,NULL,NULL,0),(3,1,1,1,0,0),(4,1,1,5,1,0),(5,1,NULL,1,0,0),(6,1,NULL,NULL,NULL,0),(7,1,NULL,NULL,NULL,0),(8,1,NULL,NULL,NULL,0),(9,1,NULL,NULL,NULL,0),(10,1,NULL,NULL,NULL,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slot`
--

LOCK TABLES `slot` WRITE;
/*!40000 ALTER TABLE `slot` DISABLE KEYS */;
INSERT INTO `slot` VALUES (1,'inventory',20),(2,'left_hand',1),(3,'right_hand',1),(4,'loot',20);
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
  `wisdom` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  `resistance` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
INSERT INTO `stats` VALUES (1,0,0,0,0,0),(12,35,10,20,25,10),(13,10,25,25,15,25),(14,10,22,28,30,10),(15,10,27,23,20,20),(16,20,30,10,15,35),(17,35,10,23,27,15),(18,20,15,30,30,15),(19,40,15,15,25,15),(20,30,28,20,18,14);
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'user'),(50,'betatester'),(100,'admin');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-17 10:14:47
