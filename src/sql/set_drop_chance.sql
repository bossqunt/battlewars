# This script is used to update the drop chance of certain items in the battlewarz.monster_item_drops table.
SET SQL_SAFE_UPDATES = 0;
UPDATE battlewarz.monster_item_drops
SET drop_chance = 7.5
WHERE drop_chance = 5;