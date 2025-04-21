-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: battlewarz
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `attack` int(11) DEFAULT 0,
  `defense` int(11) DEFAULT 0,
  `crit_chance` float DEFAULT 0,
  `crit_multi` float DEFAULT 0,
  `life_steal` float DEFAULT 0,
  `armor` int(11) DEFAULT 0,
  `speed` int(11) DEFAULT 0,
  `health` int(11) DEFAULT 0,
  `stamina` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (32,'Basic Sword','weapon',10,0,0,0,0,0,0,0,0),(33,'Novice Axe','weapon',12,0,0,0,0,0,0,0,0),(34,'Wooden Axe','weapon',8,0,0,0,0,0,0,0,0),(35,'Iron Helmet','helmet',0,5,0,0,0,0,0,0,0),(36,'Leather Helmet','helmet',0,3,0,0,0,0,0,0,0),(37,'Steel Armor','armor',0,10,0,0,0,0,0,0,0),(38,'Leather Armor','armor',0,5,0,0,0,0,0,0,0),(39,'Wooden Shield','shield',0,6,0,0,0,0,0,0,0),(40,'Iron Shield','shield',0,8,0,0,0,0,0,0,0),(41,'Ring of Power','ring',5,0,0,0,0,0,0,0,0),(42,'Ring of Fortitude','ring',0,3,0,0,0,0,0,0,0),(43,'Basic Leggings','legs',0,4,0,0,0,0,0,0,0),(44,'Iron Leggings','legs',0,6,0,0,0,0,0,0,0),(45,'Wooden Club','weapon',7,0,0,0,0,0,0,0,0),(46,'Novice Dagger','weapon',6,0,0,0,0,0,0,0,0),(47,'Silver Ring','ring',2,0,0,0,0,0,0,0,0),(48,'Chainmail Armor','armor',0,8,0,0,0,0,0,0,0),(49,'Shank','Weapon',2,1,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-22 22:52:42
