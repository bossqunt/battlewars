-- Table structure for `market_listings`
CREATE TABLE `market_listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `price` int(55) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','sold','archived') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `market_listings`
INSERT INTO `market_listings` (`id`, `player_id`, `inventory_id`, `price`, `created_at`, `status`) VALUES ('1', '52', '102', '50', '2025-04-25 01:02:50', 'sold');
INSERT INTO `market_listings` (`id`, `player_id`, `inventory_id`, `price`, `created_at`, `status`) VALUES ('2', '57', '121', '150', '2025-04-25 01:29:30', 'sold');
INSERT INTO `market_listings` (`id`, `player_id`, `inventory_id`, `price`, `created_at`, `status`) VALUES ('3', '57', '120', '50000', '2025-04-25 01:30:12', 'sold');
INSERT INTO `market_listings` (`id`, `player_id`, `inventory_id`, `price`, `created_at`, `status`) VALUES ('4', '67', '4579', '300', '2025-04-25 21:32:52', 'active');
