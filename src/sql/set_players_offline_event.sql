-- MySQL EVENT: Set players offline if token_expire < NOW()
CREATE EVENT IF NOT EXISTS set_players_offline
ON SCHEDULE EVERY 1 MINUTE
DO
  UPDATE players
  SET online = 0
  WHERE token_expire < NOW() AND online = 1;
