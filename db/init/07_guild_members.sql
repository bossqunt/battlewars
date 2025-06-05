-- Table structure for `guild_members`
CREATE TABLE `guild_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guild_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `joined_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_officer` tinyint(1) NOT NULL DEFAULT 0,
  `is_pending` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_guild_user` (`guild_id`,`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `guild_members`

