CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    stat_key VARCHAR(255) NOT NULL, -- The stat to track (e.g., 'pve_kills', 'quests_completed')
    threshold INT NOT NULL,         -- The value required to unlock the achievement
    icon_path VARCHAR(255) DEFAULT NULL, -- Optional: Path to an icon for the achievement
    title_unlocked VARCHAR(255) DEFAULT NULL, -- Optional: Title to display when the achievement is unlocked
);

CREATE TABLE player_achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at DATETIME DEFAULT CURRENT_TIMESTAMP
);