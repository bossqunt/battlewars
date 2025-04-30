-- Table structure for `players`
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
  `magic_level` int(11) DEFAULT 0,
  `shielding` int(11) DEFAULT 1,
  `stamina` int(11) DEFAULT 60,
  `online` int(11) DEFAULT 0,
  `admin` int(11) DEFAULT 0,
  `token_expire` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`,`name`),
  CONSTRAINT `c_hp` CHECK (`c_hp` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `players`
INSERT INTO `players` (`id`, `name`, `image_path`, `password`, `level`, `class_id`, `exp`, `max_hp`, `max_mp`, `c_hp`, `c_mp`, `attack`, `defence`, `speed`, `magic`, `gold`, `skill_points`, `sword`, `axe`, `fist`, `distance`, `club`, `magic_level`, `shielding`, `stamina`, `online`, `admin`, `token_expire`, `created_date`) VALUES ('1', 'admin', 'uploads/Image20-6-22at12.40pm_cffee00c-c0d8-49e0-a878-650ac3acb41c.webp', '$2y$10$4CNCk3jYBNpC0JO2wdQykOquRjIcpBJatl7BaUwacdFKW8vSLXzmy', '18', '0', '3227', '1400', '200', '1400', '200', '1', '1', '1', '1', '9465', '3', '1', '1', '1', '1', '1', '1', '1', '3198', '1', '1', '2025-04-30 20:14:15', '2025-04-26 01:08:26');
