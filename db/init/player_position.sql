-- Table structure for `player_position`
CREATE TABLE `player_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `player_position`
INSERT INTO `player_position` (`id`, `player_id`, `area_id`, `x`, `y`) VALUES ('1', '1', '1', '0', '0');

