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
-- Table structure for table `battle_history`
--

DROP TABLE IF EXISTS `battle_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `battle_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `monster_id` int(11) NOT NULL,
  `result` varchar(155) NOT NULL,
  `outcome` longtext DEFAULT NULL,
  `exp_gain` int(11) NOT NULL,
  `gold_gain` int(11) NOT NULL,
  `player_won` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=515 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `battle_history`
--

LOCK TABLES `battle_history` WRITE;
/*!40000 ALTER TABLE `battle_history` DISABLE KEYS */;
INSERT INTO `battle_history` VALUES (435,56,1,'LaNkYLaNkZ has been defeated...','[\"Elf does 24 damage to LaNkYLaNkZ (Player: 63\\/230 HP, Monster: 50\\/50 HP)\",\"LaNkYLaNkZ does 10 damage to Elf (Player: 63\\/230 HP, Monster: 40\\/50 HP)\",\"Elf does 27 damage to LaNkYLaNkZ (Player: 36\\/230 HP, Monster: 40\\/50 HP)\",\"LaNkYLaNkZ does 16 damage to Elf (Player: 36\\/230 HP, Monster: 24\\/50 HP)\",\"Elf does 22 damage to LaNkYLaNkZ (Player: 14\\/230 HP, Monster: 24\\/50 HP)\",\"LaNkYLaNkZ does 11 damage to Elf (Player: 14\\/230 HP, Monster: 13\\/50 HP)\",\"Elf does 23 damage to LaNkYLaNkZ (Player: -9\\/230 HP, Monster: 13\\/50 HP)\"]',0,0,0),(480,56,1,'LaNkYLaNkZ wins the battle against Elf','[\"Elf does 23 damage to LaNkYLaNkZ (Player: 207\\/230 HP, Monster: 50\\/50 HP)\",\"LaNkYLaNkZ does 12 damage to Elf (Player: 207\\/230 HP, Monster: 38\\/50 HP)\",\"Elf does 24 damage to LaNkYLaNkZ (Player: 183\\/230 HP, Monster: 38\\/50 HP)\",\"LaNkYLaNkZ does 15 damage to Elf (Player: 183\\/230 HP, Monster: 23\\/50 HP)\",\"Elf does 20 damage to LaNkYLaNkZ (Player: 163\\/230 HP, Monster: 23\\/50 HP)\",\"LaNkYLaNkZ does 16 damage to Elf (Player: 163\\/230 HP, Monster: 7\\/50 HP)\",\"Elf does 18 damage to LaNkYLaNkZ (Player: 145\\/230 HP, Monster: 7\\/50 HP)\",\"LaNkYLaNkZ does 18 damage to Elf (Player: 145\\/230 HP, Monster: -11\\/50 HP)\"]',5,50,1),(481,56,23,'LaNkYLaNkZ wins the battle against Monster3','[\"Monster3 does 10 damage to LaNkYLaNkZ (Player: 135\\/230 HP, Monster: 35\\/35 HP)\",\"LaNkYLaNkZ does 27 damage to Monster3 (Player: 135\\/230 HP, Monster: 8\\/35 HP)\",\"Monster3 does 15 damage to LaNkYLaNkZ (Player: 120\\/230 HP, Monster: 8\\/35 HP)\",\"LaNkYLaNkZ does 28 damage to Monster3 (Player: 120\\/230 HP, Monster: -20\\/35 HP)\"]',7,35,1),(482,56,5,'LaNkYLaNkZ wins the battle against Goblin','[\"Goblin does 7 damage to LaNkYLaNkZ (Player: 113\\/230 HP, Monster: 20\\/20 HP)\",\"LaNkYLaNkZ does 32 damage to Goblin (Player: 113\\/230 HP, Monster: -12\\/20 HP)\"]',3,20,1),(483,56,21,'LaNkYLaNkZ wins the battle against EXP MONSTER','[\"EXP MONSTER does 26 damage to LaNkYLaNkZ (Player: 87\\/230 HP, Monster: 45\\/45 HP)\",\"LaNkYLaNkZ does 23 damage to EXP MONSTER (Player: 87\\/230 HP, Monster: 22\\/45 HP)\",\"EXP MONSTER does 27 damage to LaNkYLaNkZ (Player: 60\\/230 HP, Monster: 22\\/45 HP)\",\"LaNkYLaNkZ does 19 damage to EXP MONSTER (Player: 60\\/230 HP, Monster: 3\\/45 HP)\",\"EXP MONSTER does 22 damage to LaNkYLaNkZ (Player: 38\\/230 HP, Monster: 3\\/45 HP)\",\"LaNkYLaNkZ does 27 damage to EXP MONSTER (Player: 38\\/230 HP, Monster: -24\\/45 HP)\"]',40,45,1),(484,56,23,'LaNkYLaNkZ wins the battle against Monster3','[\"Monster3 does 13 damage to LaNkYLaNkZ (Player: 25\\/230 HP, Monster: 35\\/35 HP)\",\"LaNkYLaNkZ does 26 damage to Monster3 (Player: 25\\/230 HP, Monster: 9\\/35 HP)\",\"Monster3 does 15 damage to LaNkYLaNkZ (Player: 10\\/230 HP, Monster: 9\\/35 HP)\",\"LaNkYLaNkZ does 25 damage to Monster3 (Player: 10\\/230 HP, Monster: -16\\/35 HP)\"]',7,35,1),(485,56,1,'LaNkYLaNkZ has been defeated...','[\"Elf does 17 damage to LaNkYLaNkZ (Player: -7\\/230 HP, Monster: 50\\/50 HP)\"]',0,0,0),(486,56,5,'LaNkYLaNkZ wins the battle against Goblin','[\"Goblin does 7 damage to LaNkYLaNkZ (Player: 223\\/230 HP, Monster: 20\\/20 HP)\",\"LaNkYLaNkZ does 25 damage to Goblin (Player: 223\\/230 HP, Monster: -5\\/20 HP)\"]',3,20,1),(487,56,23,'LaNkYLaNkZ wins the battle against Monster3','[\"Monster3 does 12 damage to LaNkYLaNkZ (Player: 211\\/230 HP, Monster: 35\\/35 HP)\",\"LaNkYLaNkZ does 31 damage to Monster3 (Player: 211\\/230 HP, Monster: 4\\/35 HP)\",\"Monster3 does 12 damage to LaNkYLaNkZ (Player: 199\\/230 HP, Monster: 4\\/35 HP)\",\"LaNkYLaNkZ does 28 damage to Monster3 (Player: 199\\/230 HP, Monster: -24\\/35 HP)\"]',7,35,1),(488,56,21,'LaNkYLaNkZ wins the battle against EXP MONSTER','[\"EXP MONSTER does 17 damage to LaNkYLaNkZ (Player: 182\\/230 HP, Monster: 45\\/45 HP)\",\"LaNkYLaNkZ does 20 damage to EXP MONSTER (Player: 182\\/230 HP, Monster: 25\\/45 HP)\",\"EXP MONSTER does 21 damage to LaNkYLaNkZ (Player: 161\\/230 HP, Monster: 25\\/45 HP)\",\"LaNkYLaNkZ does 18 damage to EXP MONSTER (Player: 161\\/230 HP, Monster: 7\\/45 HP)\",\"EXP MONSTER does 13 damage to LaNkYLaNkZ (Player: 148\\/230 HP, Monster: 7\\/45 HP)\",\"LaNkYLaNkZ does 27 damage to EXP MONSTER (Player: 148\\/230 HP, Monster: -20\\/45 HP)\"]',40,45,1),(505,52,21,'Admin wins the battle against EXP MONSTER','[\"EXP MONSTER does 16 damage to Admin (Player: 91\\/320 HP, Monster: 45\\/45 HP)\",\"Admin does 26 damage to EXP MONSTER (Player: 91\\/320 HP, Monster: 19\\/45 HP)\",\"EXP MONSTER does 18 damage to Admin (Player: 73\\/320 HP, Monster: 19\\/45 HP)\",\"Admin does 25 damage to EXP MONSTER (Player: 73\\/320 HP, Monster: -6\\/45 HP)\"]',40,45,1),(506,52,14,'Admin wins the battle against Harpy','[\"Harpy does 24 damage to Admin (Player: 49\\/320 HP, Monster: 40\\/40 HP)\",\"Admin does 25 damage to Harpy (Player: 49\\/320 HP, Monster: 15\\/40 HP)\",\"Harpy does 28 damage to Admin (Player: 21\\/320 HP, Monster: 15\\/40 HP)\",\"Admin does 29 damage to Harpy (Player: 21\\/320 HP, Monster: -14\\/40 HP)\"]',8,35,1),(507,52,1,'Admin has been defeated...','[\"Elf does 28 damage to Admin (Player: -7\\/320 HP, Monster: 50\\/50 HP)\"]',0,0,0),(508,52,5,'Admin wins the battle against Goblin','[\"Goblin does 14 damage to Admin (Player: 306\\/320 HP, Monster: 20\\/20 HP)\",\"Admin does 37 damage to Goblin (Player: 306\\/320 HP, Monster: -17\\/20 HP)\"]',3,20,1),(509,52,23,'Admin wins the battle against Monster3','[\"Monster3 does 8 damage to Admin (Player: 298\\/320 HP, Monster: 35\\/35 HP)\",\"Admin does 24 damage to Monster3 (Player: 298\\/320 HP, Monster: 11\\/35 HP)\",\"Monster3 does 17 damage to Admin (Player: 281\\/320 HP, Monster: 11\\/35 HP)\",\"Admin does 37 damage to Monster3 (Player: 281\\/320 HP, Monster: -26\\/35 HP)\"]',7,35,1),(510,52,1,'Admin wins the battle against Elf','[\"Elf does 28 damage to Admin (Player: 253\\/320 HP, Monster: 50\\/50 HP)\",\"Admin does 28 damage to Elf (Player: 253\\/320 HP, Monster: 22\\/50 HP)\",\"Elf does 27 damage to Admin (Player: 226\\/320 HP, Monster: 22\\/50 HP)\",\"Admin does 26 damage to Elf (Player: 226\\/320 HP, Monster: -4\\/50 HP)\"]',5,50,1),(511,52,1,'Admin wins the battle against Elf','[\"Elf does 25 damage to Admin (Player: 201\\/320 HP, Monster: 50\\/50 HP)\",\"Admin does 29 damage to Elf (Player: 201\\/320 HP, Monster: 21\\/50 HP)\",\"Elf does 22 damage to Admin (Player: 179\\/320 HP, Monster: 21\\/50 HP)\",\"Admin does 30 damage to Elf (Player: 179\\/320 HP, Monster: -9\\/50 HP)\"]',5,50,1),(512,52,1,'Admin wins the battle against Elf','[\"Elf does 25 damage to Admin (Player: 154\\/320 HP, Monster: 50\\/50 HP)\",\"Admin does 32 damage to Elf (Player: 154\\/320 HP, Monster: 18\\/50 HP)\",\"Elf does 23 damage to Admin (Player: 131\\/320 HP, Monster: 18\\/50 HP)\",\"Admin does 29 damage to Elf (Player: 131\\/320 HP, Monster: -11\\/50 HP)\"]',5,50,1),(513,52,1,'Admin wins the battle against Elf','[\"Elf does 24 damage to Admin (Player: 107\\/320 HP, Monster: 50\\/50 HP)\",\"Admin does 22 damage to Elf (Player: 107\\/320 HP, Monster: 28\\/50 HP)\",\"Elf does 21 damage to Admin (Player: 86\\/320 HP, Monster: 28\\/50 HP)\",\"Admin does 28 damage to Elf (Player: 86\\/320 HP, Monster: 0\\/50 HP)\"]',5,50,1),(514,52,21,'Admin wins the battle against EXP MONSTER','[\"EXP MONSTER does 13 damage to Admin (Player: 73\\/320 HP, Monster: 45\\/45 HP)\",\"Admin does 28 damage to EXP MONSTER (Player: 73\\/320 HP, Monster: 17\\/45 HP)\",\"EXP MONSTER does 16 damage to Admin (Player: 57\\/320 HP, Monster: 17\\/45 HP)\",\"Admin does 32 damage to EXP MONSTER (Player: 57\\/320 HP, Monster: -15\\/45 HP)\"]',40,45,1);
/*!40000 ALTER TABLE `battle_history` ENABLE KEYS */;
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
