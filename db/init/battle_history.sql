-- Table structure for `battle_history`
CREATE TABLE `battle_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `monster_id` int(11) DEFAULT NULL,
  `result` varchar(155) NOT NULL,
  `outcome` longtext DEFAULT NULL,
  `exp_gain` int(11) NOT NULL,
  `gold_gain` int(11) NOT NULL,
  `player_won` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
