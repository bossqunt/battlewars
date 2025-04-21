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
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT 'uploads/default_image.jpg',
  `password` varchar(255) NOT NULL,
  `level` int(11) DEFAULT 1,
  `class_id` int(11) NOT NULL,
  `exp` int(11) NOT NULL DEFAULT 0,
  `max_hp` int(11) NOT NULL DEFAULT 200,
  `max_mp` int(11) NOT NULL DEFAULT 200,
  `c_hp` int(11) DEFAULT 200,
  `c_mp` int(11) DEFAULT 200,
  `attack` int(11) NOT NULL DEFAULT 1,
  `defence` int(11) DEFAULT 1,
  `speed` int(11) NOT NULL DEFAULT 1,
  `magic` int(11) NOT NULL DEFAULT 1,
  `gold` int(11) DEFAULT 0,
  `skill_points` int(11) DEFAULT 0,
  `sword` int(11) DEFAULT 1,
  `axe` int(11) DEFAULT 1,
  `fist` int(11) DEFAULT 1,
  `distance` int(11) DEFAULT 1,
  `club` int(11) DEFAULT 1,
  `magic_level` int(11) DEFAULT NULL,
  `shielding` int(11) DEFAULT 1,
  `stamina` int(11) DEFAULT 60,
  `online` int(11) DEFAULT 0,
  `admin` int(11) DEFAULT 0,
  PRIMARY KEY (`id`,`name`),
  CONSTRAINT `c_hp` CHECK (`c_hp` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (15,'Bosscunt the Elite','uploads/669b502a7a0eb_chrome_qZYmht5lV6.png','$2y$10$SOS413MXNOTwXn7Jlck1buQJhVWHtpyGGZXXTXSqx/Kt2jWojrni2',17,0,1300,200,200,0,200,1,1,1,1,1925,60,1,1,1,1,1,1,1,54,1,0),(23,'test','uploads/6527e6613a8aa_Element_TD_2_r3ykBdPWw9.jpg','$2y$10$5ZyQcCfPRXbth6eQZgS.lOOZhZLr28BX9iW1YXLq7nm/VOfM8PYj.',4,0,218,200,200,200,200,1,1,1,1,300,15,1,1,1,1,1,NULL,1,60,1,0),(37,'test1',NULL,'$2y$10$PjZxCbvIAPwxqYVlE5tYkO/UfsqrHySa/zOnxVk45eTitIqqg.3P2',1,0,0,200,200,158,200,1,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(38,'test3',NULL,'$2y$10$jYjWax5ZSSSLzykSgtWaZ.UOKxUYSzJ4hsuCTE3.4T99rnUh5o66m',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(39,'test5',NULL,'$2y$10$ud7TK7smscw7JhZUbP1nAu1va.Zdz4USDy7s9Oj9MqC3AOOXh62Y6',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(40,'test6',NULL,'$2y$10$Rbl5jchM1YyBbiUKeAB10eup/3L1sUEut9BIGY22LMnVSPOHehOxm',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(41,'test7',NULL,'$2y$10$t4ihguVLFZjRy/aEHNCPr.9hnxP/O5CQps3a9FoLZ8uLLQCZRvKqy',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(42,'elmo',NULL,'$2y$10$IVxuBSeq5fWT0z/4kMtcRe908wM9zvOExEGhaiIlTz6LF7TGNIYf6',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(43,'bosscunt',NULL,'$2y$10$YSCjUmnhxP5T/ZpOWAQJau1mejhB0u/aeIPzxhY8KrveGyLLUm6lK',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(44,'asdhaskd',NULL,'$2y$10$RhMpizb5XZTuVWoVHvGuW.vYh2.SNoDcHdovkREAjvzikGyT4Y6Uq',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(45,'asdhaskdd',NULL,'$2y$10$40FMSD0BXOREbjRw1eVQ6O0ylUspWKWuvyNPXJckvQK7A9IvCt1Ze',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(46,'asdhaskdda',NULL,'$2y$10$r2wV4gskXv3jC06wEe0cC.pd96QpntC9JmBeK8YBA5CsnnmFq08ym',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(47,'asdhaskddaa',NULL,'$2y$10$xDqOAHGSlXABxhqoBEk6i.0QJe2itarozCSVcb8xxyhQ.KZP1qcMy',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(48,'asdjhasjk',NULL,'$2y$10$Fg1yZPeoIoZdlrABPK23xu3NVsVgVCDu5NDPq9kQpNudc4vH.Y8Om',1,2,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(49,'asdjhasjkd',NULL,'$2y$10$z1OrSs67z57PIvZfXhwfh.D20M/duGtU6wiMoupUefMZsjHcoLtcy',1,2,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(50,'t',NULL,'$2y$10$g04Nkhcg0SQjay90R2Q8HOLeSDFaurLOwE7LkrM/BOLpcKrGlpHSK',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(51,'y',NULL,'$2y$10$azqhZcZUDRA8izcoPwR7nu4rYmRC75FahR4Thr6WKxA1Ym1fpzUIW',1,1,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(52,'Admin','uploads/669b502a7a0eb_chrome_qZYmht5lV6.png','$2y$10$tqbREQUZ39mIpOxmP2RVjOkRMyjkB3MDx1m3uvwRg/hMBYbuWa5qi',3,0,0,350,200,57,200,5,1,1,1,9565,3,1,1,1,1,1,NULL,1,60,0,1),(53,'bosscunt1',NULL,'$2y$10$d4n2vieGkIl7JpKJtFOGmeveT1PjPBF8gFCvB/FLKZNvu8iWM0Bb2',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(54,'bosscunt2',NULL,'$2y$10$QZo5HrOEe/kfcwbKGbpfS.AuQjSsU7JyzPZnAvExtq595p5WQH9Se',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(55,'newuser','uploads/default_image.jpg','$2y$10$aLFhNCLp8l//1KtxOqNA6O7UMNzaJjFFqH2lwwByQSp0mCIG1X0XK',1,0,0,200,200,200,200,5,1,1,1,0,0,1,1,1,1,1,NULL,1,60,0,0),(56,'LaNkYLaNkZ','uploads/669a6191cec62_360_F_442407721_my741A5zQJT1FXILAy2Ce5oLQsKkFiyY.jpg','$2y$10$wihwvI9Mc8S9dKxFPYVhMeOpCSHuwGmQHa65PvAmraQrZ9CVyazNq',55,0,0,260,200,148,200,1,1,1,1,825,0,1,1,1,1,1,NULL,1,8,0,1);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
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
