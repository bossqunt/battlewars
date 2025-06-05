-- Table structure for `scheduler_state`
CREATE TABLE `scheduler_state` (
  `id` int(11) NOT NULL,
  `last_run` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `scheduler_state`
INSERT INTO `scheduler_state` (`id`, `last_run`) VALUES ('1', '2025-04-22 13:24:17');
