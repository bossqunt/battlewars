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
-- Table structure for table `monsters`
--

DROP TABLE IF EXISTS `monsters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monsters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `on_death_exp` int(11) DEFAULT NULL,
  `on_death_gold` int(11) DEFAULT NULL,
  `defence` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monsters`
--

LOCK TABLES `monsters` WRITE;
/*!40000 ALTER TABLE `monsters` DISABLE KEYS */;
INSERT INTO `monsters` VALUES (1,'Elf',10,50,20,30,5,50,15),(2,'Minotaur',15,100,30,25,2,75,20),(3,'Dwarf',5,40,15,40,5,40,10),(4,'Orc',12,70,25,28,4,60,18),(5,'Goblin',3,20,10,35,3,20,5),(6,'Dragon',25,150,45,22,6,110,30),(7,'Specter',20,120,40,30,7,90,25),(8,'Kraken',28,160,50,18,8,125,32),(9,'Phoenix',22,100,35,35,9,100,20),(10,'Banshee',18,80,28,34,10,75,22),(11,'Cyclops',16,90,32,26,11,70,20),(12,'Manticore',14,70,30,32,12,65,18),(13,'Hydra',30,180,55,20,13,150,40),(14,'Harpy',8,40,18,38,8,35,12),(15,'Basilisk',24,140,48,28,15,115,28),(16,'Chimera',21,110,36,33,16,95,24),(17,'Griffin',17,85,30,36,17,80,20),(18,'Mummy',13,60,26,24,18,55,16),(19,'Yeti',11,50,24,28,19,45,14),(20,'Siren',19,95,32,30,20,85,22),(21,'EXP MONSTER',8,45,18,32,40,45,12),(22,'Monster2',22,120,40,20,22,100,25),(23,'Monster3',7,35,13,38,7,35,9),(24,'Died',0,0,0,0,0,0,0),(25,'Wot',0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `monsters` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-22 22:52:43
