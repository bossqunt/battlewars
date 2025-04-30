-- Table structure for `player_stats`
CREATE TABLE `player_stats` (
  `player_id` int(11) NOT NULL,
  `pve_battles` int(11) DEFAULT 0,
  `pve_kills` int(11) DEFAULT 0,
  `travel_count` int(11) DEFAULT 0,
  `pvp_kills` int(11) DEFAULT 0,
  `pvp_battles` int(11) DEFAULT 0,
  `gold_sum` int(11) DEFAULT 0,
  `boss_kills` int(11) DEFAULT 0,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `player_stats`
INSERT INTO `player_stats` (`player_id`, `pve_battles`, `pve_kills`, `travel_count`, `pvp_kills`, `pvp_battles`, `gold_sum`, `boss_kills`) VALUES ('1', '143', '137', '463', '36', '56', '625', '4');

