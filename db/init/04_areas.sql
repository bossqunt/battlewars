-- Table structure for `areas`
CREATE TABLE `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `min_level` varchar(45) DEFAULT NULL,
  `max_level` varchar(45) DEFAULT NULL,
  `boss_x` int(11) DEFAULT NULL,
  `boss_y` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `areas`
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('1', 'Whispering Wilds', '1', '10', '8', '7');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('2', 'Ashen Valley', '11', '20', '5', '3');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('3', 'Duskmire Hollow', '21', '30', '6', '4');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('4', 'Crimson Glade', '31', '40', '4', '8');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('5', 'Obsidian Bastion', '41', '50', '3', '3');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('6', 'Frostveil Peaks', '51', '60', '6', '9');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('7', 'Verdant Labyrinth', '61', '70', '7', '2');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('8', 'Sable Highlands', '71', '80', '5', '5');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('9', 'Twilight Fen', '81', '90', '2', '6');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('10', 'Stormbreak Wastes', '91', '100', '4', '4');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('11', 'Caverns of Echo', '101', '110', '1', '7');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('12', 'Ironroot Expanse', '111', '120', '9', '3');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('13', 'Scorched Reaches', '121', '130', '3', '8');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('14', 'Gloamspire Ridge', '131', '140', '6', '2');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('15', 'Veil of Thorns', '141', '150', '7', '6');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('16', 'Netherwake Depths', '151', '160', '2', '2');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('17', 'Briarfall Sanctuary', '161', '170', '8', '4');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('18', 'Celestial Crossing', '171', '180', '5', '1');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('19', 'Ebonreach Hollow', '181', '190', '9', '5');
INSERT INTO `areas` (`id`, `name`, `min_level`, `max_level`, `boss_x`, `boss_y`) VALUES ('20', 'Throne of the Ancients', '191', '200', '10', '10');
