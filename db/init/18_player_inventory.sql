-- Table structure for `player_inventory`
CREATE TABLE `player_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rarity` int(11) DEFAULT 0,
  `equipped` int(11) DEFAULT 0,
  `attack` int(11) DEFAULT 0,
  `defence` int(11) DEFAULT 0,
  `crit_chance` float DEFAULT 0,
  `crit_multi` float DEFAULT 0,
  `life_steal` float DEFAULT 0,
  `armor` int(11) DEFAULT 0,
  `speed` int(11) DEFAULT 0,
  `health` int(11) DEFAULT 0,
  `stamina` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `player_inventory`
