# Battlewarz

Battlewarz is a browser-based RPG featuring player-vs-player (PVP), a dynamic grid world, item rarity, inventory management, and a marketplace. Players can equip items, battle monsters, and interact with other players in real time.

---

## Premise

You are a warrior in a persistent world. Explore the grid, fight monsters, challenge other players, collect and upgrade equipment, and trade items in the marketplace. Manage your stamina, health, and gold as you progress and compete for dominance.

---

## Setup & Installation

### Requirements

- PHP 8.0+
- MySQL/MariaDB
- Composer
- Node.js (for frontend asset builds, if needed)
- Web server (Apache, Nginx, etc.)

### Installation Steps

1. **Clone the repository**
   ```sh
   git clone https://github.com/yourusername/battlewarz.git
   cd battlewarz
   ```

2. **Install PHP dependencies**
   ```sh
   composer install
   ```

3. **Configure Database**
   - Create a new MySQL database (e.g., `bw2`).
   - Import the provided schema (see [Database Schema](#database-schema)).
   - Update your database credentials in `controller/Database.php` if needed.

4. **Set up file permissions**
   - Ensure your web server can write to any directories used for uploads or caching.

5. **Configure Web Server**
   - Point your web root to the `bw2` directory.
   - Enable URL rewriting if using Apache (`.htaccess`).

6. **Frontend Assets**
   - If you modify frontend JS/CSS, rebuild assets as needed.

---

## Database Schema

### Core Tables

```sql
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gold INT DEFAULT 0,
    level INT DEFAULT 1,
    c_hp INT DEFAULT 100,
    max_hp INT DEFAULT 100,
    stamina INT DEFAULT 60,
    admin TINYINT(1) DEFAULT 0,
    online TINYINT(1) DEFAULT 0,
    online_last DATETIME,
    last_active DATETIME,
    -- ...other fields...
);

CREATE TABLE item_rarities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(32) NOT NULL,
    color VARCHAR(16) NOT NULL
);

CREATE TABLE item_rarity_modifiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rarity_id INT NOT NULL,
    stat_name VARCHAR(50) NOT NULL,
    modifier_type ENUM('fixed', 'percent') NOT NULL,
    min_value INT NOT NULL,
    max_value INT NOT NULL,
    FOREIGN KEY (rarity_id) REFERENCES item_rarities(id)
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(32) NOT NULL,
    rarity_id INT NOT NULL,
    attack INT DEFAULT 0,
    defense INT DEFAULT 0,
    crit_multi INT DEFAULT 0,
    crit_chance INT DEFAULT 0,
    speed INT DEFAULT 0,
    health INT DEFAULT 0,
    stamina INT DEFAULT 0,
    life_steal INT DEFAULT 0,
    -- ...other stats...
    FOREIGN KEY (rarity_id) REFERENCES item_rarities(id)
);

CREATE TABLE player_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    item_id INT NOT NULL,
    equipped TINYINT(1) DEFAULT 0,
    -- ...other fields...
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

CREATE TABLE market_listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_inventory_id INT NOT NULL,
    price INT NOT NULL,
    offer INT DEFAULT NULL,
    offer_amount INT DEFAULT NULL,
    -- ...other fields...
    FOREIGN KEY (player_inventory_id) REFERENCES player_inventory(id)
);
```

### Example Rarity Modifiers

```sql
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
```

---

## Basic Calculations

- **Healing Cost:** `10 + (player level * 5)`
- **Stamina:** Used for actions; running out disables certain actions but does not defeat the player.
- **Item Stat Calculation:**  
  - Item stats are determined by base stats and rarity modifiers (fixed or percent).
- **Marketplace:**  
  - Players can list items, make offers, and accept offers.
- **PVP:**  
  - Players can challenge others on the grid; outcomes depend on stats and equipment.

---

## Known Issues / To-Do List

- [ ] Redirect after registration is not working
- [ ] Starting equipment
- [x] My image is displayed for players grid tile (**FIXED**)
- [ ] Stamina run out, display NOT defeated, that you have run out of stamina...
- [ ] Negative effects -- stronger upside
- [x] PVP (**ADDED**)
- [ ] World events
- [x] Take ownership of grid/PVP (**ADDED**)
- [x] Consolidate rarity classes (**FIXED FOR FE**)
- [ ] Consolidate rarity classes (**NOT FIXED FOR BE**)
- [x] Move inventory count outside of dashboard into playerStats class (**FIXED**)
- [ ] Display level of monster

---

## Configuration

- **Database:**  
  Edit `controller/Database.php` for your DB credentials.
- **JWT Secret:**  
  Set your JWT secret in API files as needed.
- **Session:**  
  PHP sessions are used for authentication and player state.

---

## Contribution

- Fork the repo, create a feature branch, and submit a pull request.
- Please update the checklist above as you address issues.

---

## License

MIT or BSD-3-Clause (see LICENSE file).

---



