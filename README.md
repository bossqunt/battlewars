// 
-- redirect after registration is not working
-- starting equipment
-- my image is displayed for players grid tile FIXED
-- stamina run out, display NOT defeated, that you have run out of stamina...
-- negative effects -- stronger upside
-- PVP - ADDED
-- world events 
-- Take ownership of grid/PVP -- ADDED
-- CONSOLIDATE RARITY CLASSES (They're now everywhere... and causing chaos..)
--- FIXED FOR FE
--- NOT FIXED FOR BE
-- Move inventory count outside of dashboard into playerStats class. -- FIXED
-- Display level of monster


CREATE TABLE item_rarity_modifiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rarity_id INT NOT NULL,
    stat_name VARCHAR(50) NOT NULL,
    modifier_type ENUM('fixed', 'percent') NOT NULL,
    min_value INT NOT NULL,
    max_value INT NOT NULL,
    FOREIGN KEY (rarity_id) REFERENCES item_rarities(id)
);


-- Uncommon
INSERT INTO item_rarity_modifiers (rarity_id, stat_name, modifier_type, min_value, max_value) VALUES
(2, 'attack', 'percent', 3, 6),
(2, 'defense', 'percent', 3, 6),
(2, 'crit_chance', 'percent', 2, 4),
(2, 'health', 'percent', 3, 5),
(2, 'stamina', 'percent', 3, 5);

-- Rare
INSERT INTO item_rarity_modifiers (rarity_id, stat_name, modifier_type, min_value, max_value) VALUES
(3, 'attack', 'percent', 8, 12),
(3, 'defense', 'percent', 8, 12),
(3, 'crit_chance', 'percent', 5, 8),
(3, 'crit_multi', 'percent', 8, 12),
(3, 'health', 'percent', 8, 12),
(3, 'armor', 'percent', 8, 12),
(3, 'stamina', 'percent', 8, 12);

-- Epic
INSERT INTO item_rarity_modifiers (rarity_id, stat_name, modifier_type, min_value, max_value) VALUES
(4, 'attack', 'percent', 12, 18),
(4, 'defense', 'percent', 12, 18),
(4, 'crit_chance', 'percent', 8, 12),
(4, 'crit_multi', 'fixed', 4, 7),
(4, 'life_steal', 'fixed', 2, 4),
(4, 'health', 'fixed', 20, 30),
(4, 'stamina', 'fixed', 18, 25);

-- Legendary
INSERT INTO item_rarity_modifiers (rarity_id, stat_name, modifier_type, min_value, max_value) VALUES
(5, 'attack', 'fixed', 20, 30),
(5, 'defense', 'fixed', 20, 30),
(5, 'crit_chance', 'fixed', 8, 12),
(5, 'crit_multi', 'fixed', 10, 20),
(5, 'life_steal', 'fixed', 6, 10),
(5, 'armor', 'fixed', 25, 35),
(5, 'health', 'fixed', 45, 60),
(5, 'stamina', 'fixed', 45, 60),
(5, 'speed', 'fixed', 8, 12);



