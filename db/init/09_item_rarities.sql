-- Table structure for `item_rarities`
CREATE TABLE `item_rarities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `chance` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `item_rarities`
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('1', 'Common', '90');
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('2', 'Uncommon', '45');
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('3', 'Rare', '20');
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('4', 'Epic', '5');
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('5', 'Legendary', '1');
INSERT INTO `item_rarities` (`id`, `name`, `chance`) VALUES ('6', 'Godly', '0.1');
