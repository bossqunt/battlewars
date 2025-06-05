-- Table structure for `market_offers`
CREATE TABLE `market_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) NOT NULL,
  `offer_player_id` int(11) NOT NULL,
  `offer_amount` int(11) NOT NULL,
  `status` enum('active','accepted','removed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `market_offers`

