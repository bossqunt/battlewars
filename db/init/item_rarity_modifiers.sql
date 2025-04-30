-- Table structure for `item_rarity_modifiers`
CREATE TABLE `item_rarity_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rarity_id` int(11) NOT NULL,
  `stat_name` varchar(50) NOT NULL,
  `modifier_type` enum('fixed','percent') NOT NULL,
  `min_value` int(11) NOT NULL,
  `max_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rarity_id` (`rarity_id`),
  CONSTRAINT `item_rarity_modifiers_ibfk_1` FOREIGN KEY (`rarity_id`) REFERENCES `item_rarities` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `item_rarity_modifiers`
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('1', '5', 'attack', 'percent', '150', '200');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('2', '5', 'defence', 'percent', '36', '48');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('3', '5', 'crit_chance', 'fixed', '5', '15');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('4', '5', 'crit_multi', 'fixed', '7', '20');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('5', '5', 'life_steal', 'fixed', '3', '4');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('7', '5', 'health', 'percent', '10', '20');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('8', '5', 'stamina', 'fixed', '1', '45');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('9', '5', 'speed', 'percent', '20', '50');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('10', '4', 'attack', 'percent', '100', '150');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('11', '4', 'defence', 'percent', '24', '36');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('12', '4', 'crit_chance', 'fixed', '1', '10');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('13', '4', 'crit_multi', 'fixed', '5', '15');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('14', '4', 'life_steal', 'fixed', '1', '2');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('15', '4', 'health', 'percent', '5', '15');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('16', '4', 'stamina', 'fixed', '1', '30');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('17', '3', 'attack', 'percent', '50', '100');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('18', '3', 'defence', 'percent', '12', '24');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('19', '3', 'crit_chance', 'fixed', '1', '10');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('20', '3', 'crit_multi', 'fixed', '0', '10');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('21', '3', 'health', 'percent', '1', '10');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('23', '3', 'stamina', 'fixed', '1', '15');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('24', '2', 'attack', 'percent', '25', '50');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('25', '2', 'defence', 'percent', '6', '12');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('26', '2', 'crit_chance', 'fixed', '1', '10');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('27', '2', 'health', 'percent', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('28', '2', 'stamina', 'fixed', '1', '5');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('30', '1', 'attack', 'percent', '1', '50');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('32', '6', 'attack', 'percent', '200', '250');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('33', '1', 'gold', 'percent', '1', '50');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('34', '2', 'gold', 'percent', '50', '100');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('35', '3', 'gold', 'percent', '100', '150');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('36', '4', 'gold', 'percent', '150', '200');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('37', '5', 'gold', 'percent', '200', '300');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('38', '6', 'gold', 'percent', '350', '400');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('39', '1', 'defence', 'percent', '3', '6');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('40', '6', 'defence', 'percent', '48', '60');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('41', '1', 'crit_chance', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('42', '6', 'crit_chance', 'fixed', '7', '20');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('43', '1', 'crit_multi', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('44', '2', 'crit_multi', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('45', '6', 'crit_multi', 'fixed', '9', '30');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('46', '1', 'life_steal', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('47', '2', 'life_steal', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('48', '3', 'life_steal', 'fixed', '0', '1');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('49', '6', 'life_steal', 'fixed', '5', '6');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('50', '1', 'speed', 'percent', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('51', '2', 'speed', 'percent', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('52', '3', 'speed', 'percent', '20', '30');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('53', '4', 'speed', 'percent', '20', '40');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('54', '6', 'speed', 'percent', '30', '60');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('55', '1', 'stamina', 'fixed', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('56', '6', 'stamina', 'fixed', '1', '60');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('57', '1', 'health', 'percent', '0', '0');
INSERT INTO `item_rarity_modifiers` (`id`, `rarity_id`, `stat_name`, `modifier_type`, `min_value`, `max_value`) VALUES ('58', '6', 'health', 'percent', '25', '40');
