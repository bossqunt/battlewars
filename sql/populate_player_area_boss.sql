INSERT INTO player_area_boss (player_id, area_id, boss_defeated)
SELECT pp.player_id, pp.area_id, 0
FROM player_position pp
ON DUPLICATE KEY UPDATE boss_defeated = boss_defeated;
