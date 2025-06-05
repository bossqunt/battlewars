-- Table structure for `player_classes`
CREATE TABLE `player_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hp_per_level` int(11) NOT NULL,
  `mp_per_level` int(11) NOT NULL,
  `hp_minute` int(11) DEFAULT NULL,
  `mp_minute` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `player_classes`
INSERT INTO `player_classes` (`id`, `name`, `hp_per_level`, `mp_per_level`, `hp_minute`, `mp_minute`) VALUES ('0', 'Knight', '30', '5', '30', '5');
INSERT INTO `player_classes` (`id`, `name`, `hp_per_level`, `mp_per_level`, `hp_minute`, `mp_minute`) VALUES ('1', 'Archer', '15', '15', '15', '15');
INSERT INTO `player_classes` (`id`, `name`, `hp_per_level`, `mp_per_level`, `hp_minute`, `mp_minute`) VALUES ('2', 'Sorcerer', '5', '30', '5', '30');
INSERT INTO `player_classes` (`id`, `name`, `hp_per_level`, `mp_per_level`, `hp_minute`, `mp_minute`) VALUES ('3', 'Druid', '5', '30', '5', '30');
