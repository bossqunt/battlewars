-- Table structure for `area_owner`
CREATE TABLE `area_owner` (
  `player_id` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `area_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `area_owner`
INSERT INTO `area_owner` (`player_id`, `x`, `y`, `area_id`) VALUES ('1', '0', '0', '1');
