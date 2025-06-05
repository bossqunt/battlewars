-- Table structure for `player_area_boss`
CREATE TABLE `player_area_boss` (
  `player_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `boss_defeated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`player_id`,`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `player_area_boss`
