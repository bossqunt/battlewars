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
-- Table structure for table `player_inventory`
--

DROP TABLE IF EXISTS `player_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rarity` int(11) DEFAULT 0,
  `equipped` int(11) DEFAULT 0,
  `attack` int(11) DEFAULT 0,
  `defense` int(11) DEFAULT 0,
  `crit_chance` float DEFAULT 0,
  `crit_multi` float DEFAULT 0,
  `life_steal` float DEFAULT 0,
  `armor` int(11) DEFAULT 0,
  `speed` int(11) DEFAULT 0,
  `health` int(11) DEFAULT 0,
  `stamina` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_inventory`
--

LOCK TABLES `player_inventory` WRITE;
/*!40000 ALTER TABLE `player_inventory` DISABLE KEYS */;
INSERT INTO `player_inventory` VALUES (2,52,33,1,1,1,4,2,5,6,7,8,9,10,11),(3,52,39,1,1,1,5,0,0,0,0,0,0,0,0),(4,52,44,1,1,1,6,0,0,0,0,0,0,0,0),(5,52,31,1,1,0,7,0,0,0,0,0,0,0,0),(7,52,41,1,2,1,0,0,0,0,0,0,0,0,0),(10,52,38,1,1,1,0,0,0,0,0,0,0,0,0),(24,56,33,1,1,0,0,0,0,0,0,0,0,0,0),(25,56,39,1,1,1,0,0,0,0,0,0,0,0,0),(26,56,44,1,1,0,0,0,0,0,0,0,0,0,0),(27,56,31,1,1,0,0,0,0,0,0,0,0,0,0),(28,56,37,1,1,0,10,1,0,0,0,3,0,0,0),(29,56,41,1,1,0,9,0,0,3,0,0,56,0,0),(30,56,34,1,1,1,8,3,5,0,0,4,6,0,5),(31,56,48,1,2,1,0,0,0,12,4,0,0,6,0),(32,56,47,1,2,1,7,3,0,0,0,0,4,0,0),(33,56,46,1,3,0,6,4,5,0,0,5,0,6,7),(34,56,45,1,3,0,0,5,0,3,5,0,0,0,6),(35,56,44,1,4,1,5,6,4,0,0,0,0,0,0),(36,56,43,1,5,0,1,0,0,0,0,0,0,7,0),(37,52,49,1,5,0,15,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `player_inventory` ENABLE KEYS */;
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
