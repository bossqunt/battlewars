<?php


// TODO:
// - Fix methods for all base attributes, they are currently derived from the player table instead of items/inventory_items
// - Fix method namming... getPlayerItemAttack() should be getEquippedItemAttack() - There should only be 1 attack method.. getAttack()
// - Find duplicate/overlapping methods and consolidate
// - Get should be used instead of fetch 

class Player
{
    private $conn;
    private $id;

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getLevel()
    {
        return $this->getAttribute('level');
    }
    public function getDefence()
    {
        $playerDefence = $this->getPlayerAttribute('defence');
        $playerItemDefence = $this->getPlayerItemDefence();
        return $playerDefence + $playerItemDefence;
    }
    public function getPlayerItemDefence() {
        $query = "SELECT SUM(pi.defence + i.defence) AS total_defence 
                FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['total_defence'] : 0;
    }
    public function getMagicLevel()
    {
        return $this->getAttribute('magic_level');
    }
    public function getCurrentHp()
    {
        return $this->getAttribute('c_hp');
    }
    public function getStamina()
    {
        return $this->getAttribute('stamina');
    }
    public function getMaxHp()
    {
        $playerMaxHp = $this->getAttribute('max_hp');
        $playerItemHp = $this->getPlayerItemHealth();
         
        return $playerMaxHp + $playerItemHp;
    }
    function getPlayerItemHealth() {
        $query = "SELECT SUM(pi.health + i.health) AS total_health 
                FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['total_health'] : 0;
    }
        
    public function getName()
    {
        $name = $this->getAttribute('name');
        if ($this->getAdmin() == 1) {
            return '[GM] ' . $name;
        }
        return $name;
    }
    public function getExp()
    {
        return $this->getAttribute('exp');
    }
    public function getRequiredExp()
    {
        return $this->getAttribute('exp_req');
    }
    public function getClass()
    {
        return $this->getAttribute('class_id');
    }
    public function getAdmin()
    {
        return $this->getAttribute('admin');
    }

    public function __construct($conn, $id)
    {
        $this->conn = $conn;
        $this->id = $id;
    }

    // Fetches the attack attribute from multiple sources
    public function getAttack()
    {
        $playerAttack = $this->getPlayerAttribute('attack');
        $itemAttack = $this->getPlayerItemAttack();

        return $playerAttack + $itemAttack;
    }
    public function getSpeed()
    {
        $playerSpeed = $this->getPlayerAttribute('speed');
        $itemSpeed = $this->getPlayerItemSpeed();
        return $playerSpeed + $itemSpeed;
    }

    // Fetches a single attribute for the player from the players table
    private function getPlayerAttribute($attributeName)
    {
        $query = "SELECT $attributeName FROM players WHERE id = ?";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row[$attributeName] : 0;
    }

    // Fetches the total attack from equipped items in the player_inventory table
    public function getPlayerItemAttack()
    {
        $query = "SELECT SUM(pi.attack + i.attack) AS total_attack 
                FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['total_attack'] : 0;
    }
    public function getPlayerItemSpeed()
    {
        $query = "SELECT SUM(pi.speed + i.speed) AS total_attack 
                FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['total_attack'] : 0;
    }
    public function getPlayerItemCritChance()
    {
        $query = "SELECT SUM(pi.crit_chance + i.crit_chance) AS crit_chance 
                FROM player_inventory pi 
                INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['crit_chance'] : 0;
    }
    public function getPlayerItemCritMulti()
    {
        $query = "SELECT SUM(pi.crit_multi + i.crit_multi) AS crit_multi 
                FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['crit_multi'] : 0;
    }
    public function getPlayerItemLifesteal()
    {
        $query = "SELECT SUM(pi.life_steal) AS life_steal 
                FROM player_inventory pi 
                WHERE pi.player_id = ? AND pi.equipped = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        return $row ? (int)$row['life_steal'] : 0;
    }


    // Fetches a single attribute for the player from the database
    private function getAttribute($attributeName)
    {
        // Default to selecting from players table
        $selectClause = "p.$attributeName";

        // If the attribute is exp_req, select it from exp_table
        if ($attributeName === 'exp_req') {
            $selectClause = "e.exp_req";
        }

        $query = "
        SELECT $selectClause 
        FROM players p
        INNER JOIN exp_table e ON e.level = p.level
        WHERE p.id = ?";

        $params = [$this->id];
        $row = $this->fetchSingleRow($query, $params);

        if ($row) {
            return $row[$attributeName];
        }
        return null;
    }

    // Maps item rarity integer to corresponding string
    function getItemRarity($rarity)
    {
        switch ($rarity) {
            case 1:
                return "Common";
            case 2:
                return "Uncommon";
            case 3:
                return "Rare";
            case 4:
                return "Epic";
            case 5:
                return "Legendary";
            case 6:
                return "Godly";
            default:
                return "Invalid";
        }
    }
    function getRarityBadgeClass($rarity) {
        switch ($rarity) {
            case 1: return 'bg-black text-white';
            case 2: return 'bg-green-600 text-white';
            case 3: return 'bg-blue-600 text-white';
            case 4: return 'bg-purple-600 text-white';
            case 5: return 'bg-orange-500 text-white';
            case 6: return 'bg-red-400 text-white';
            default: return 'bg-gray-300 text-black';
        }
      }
      public function fetchProfileDetails()
      {
          $query = "
              SELECT p.id, p.level, p.name, p.image_path, p.exp, e.exp_req, p.gold, p.max_hp, p.max_mp,
                     p.c_hp, p.c_mp, p.attack, p.speed, p.defence, p.stamina,
                     pc.name AS class
              FROM players p
              LEFT JOIN exp_table e ON e.level = p.level
              INNER JOIN player_classes pc ON pc.id = p.class_id
              WHERE p.id = ?
          ";
      
          return $this->fetchSingleRow($query, [$this->id]);
      }

    // Retrieves player details including attributes and related data
    public function getDetails()
    {
        $player = $this->fetchProfileDetails();
    
        if ($player) {
            $player['area'] = $this->getPlayerArea();
            $player['areaOwner'] = $this->getPlayerAreaOwner();
            $player['inventoryCount'] = $this->getPlayerInventoryCount();
            $player['areasUnlocked'] = $this->getPlayerAreaUnlocked();
            $player['guild'] = $this->getPlayerGuild();
    
            return $player;
        }
    
        return null;
    }
    public function getPlayerGuild() {
        $sql = "SELECT g.id, g.name FROM guilds g INNER JOIN guild_members gm ON gm.guild_id = g.id WHERE gm.player_id = ?";
        $params = [$this->id];
        return $this->fetchSingleRow($sql, $params);
    }
    
    public function getProfile($playerId = null)
    {
        // Use $this->id for the player id
        $playerId = $this->id;

        $player = $this->fetchProfileDetails();

        // Build the response using getter methods for calculated stats
        $profile = [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'level'      => $this->getLevel(),
            'image_path' => $player['image_path'] ?? null,
            'exp'        => $this->getExp(),
            'exp_req'    => $this->getRequiredExp(),
            'class'      => $player['class'] ?? null,
            'attack'     => $this->getAttack(),
            'defence'    => $this->getDefence(),
            'speed'      => $this->getSpeed(),
            'max_hp'     => $this->getMaxHp(),
            'c_hp'       => $this->getCurrentHp(),
            'stamina'    => $this->getStamina(),
            'crit_chance'   => $this->getPlayerItemCritChance(),
            'crit_multi'    => $this->getPlayerItemCritMulti(),
            'life_steal'    => $this->getPlayerItemLifesteal(),
            'stats'      => $this->getPlayerStats(),
        ];

        return $profile;
    }
    public function getPlayerStats()
    {
        $sql = "SELECT travel_count, pvp_battles, pvp_kills, pve_battles, pve_kills FROM player_stats WHERE player_id = ?";
        $params = [$this->id];
        return $this->fetchSingleRow($sql, $params);
    }

    public function checkLevelUp($newExp) {
        $currentExp = $this->getExp();
        $nextLevelExp = $this->getRequiredExp();
        
        if ($currentExp + $newExp >= $nextLevelExp) {
            return true;
        }
        return false;
    }
    

    // Retrieves player's current area details
    private function getPlayerArea()
    {
        $sql = "SELECT pa.player_id, a.name, pa.x, pa.y, pa.area_id, a.boss_x, a.boss_y
                FROM player_position pa
                INNER JOIN areas a ON a.id = pa.area_id
                WHERE pa.player_id = ?";
        $params = [$this->id];
        return $this->fetchAllRows($sql, $params);
    }
    public function getPlayerAreaId()
    {
        $sql = "SELECT area_id FROM player_position WHERE player_id = ?";
        $params = [$this->id];
        $row = $this->fetchSingleRow($sql, $params);
        return $row ? (int)$row['area_id'] : null;
    }

    public function getPlayerAreaUnlocked() {
        // Get the highest area_id where the boss is defeated
        $sql = "SELECT MAX(area_id) AS max_defeated_area FROM player_area_boss WHERE player_id = ? AND boss_defeated = 1";
        $params = [$this->id];
        $row = $this->fetchSingleRow($sql, $params);
        $maxDefeatedArea = isset($row['max_defeated_area']) ? (int)$row['max_defeated_area'] : null;

        if ($maxDefeatedArea === null || $maxDefeatedArea === 0) {
            return [];
        }

        // Get all areas with id < maxDefeatedArea + 1
        $unlockUpTo = $maxDefeatedArea + 1;
        $sql = "SELECT a.id as area_id, a.name, a.min_level, a.max_level
                FROM areas a
                WHERE a.id <= ?";
        $params = [$unlockUpTo];
        $areas = $this->fetchAllRows($sql, $params);

        return $areas;
    }

    // Retrieves details of the player owning the current area
    private function getPlayerAreaOwner()
    {
        $sql = "SELECT 
                    po.id AS player_id,
                    ao.x,
                    ao.y,
                    ao.area_id,
                    po.image_path,
                    po.level,
                    po.name
                FROM
                    battlewarz.players p
                INNER JOIN
                    player_position pp ON pp.player_id = p.id
                LEFT JOIN
                    area_owner ao ON ao.area_id = pp.area_id
                INNER JOIN
                    players po ON po.id = ao.player_id
                WHERE p.id = ?";
        $params = [$this->id];
        $owners = $this->fetchAllRows($sql, $params);

        // Add guild details for each owner
        foreach ($owners as &$owner) {
            $owner['guild'] = $this->getPlayerGuildById($owner['player_id']);
        }
        return $owners;
    }

    // Used in battle.php to set the current area as the player's owned area
    public function setCurrentAreaOwnerAsPlayer($areaId, $x, $y)
    {
        $sql = "UPDATE area_owner SET player_id = ? WHERE area_id = ? AND x = ? AND y = ?";
        $params = [$this->id, $areaId, $x, $y];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", ...$params);
        $stmt->execute();
    }

    // Updates player's location when traveling
    public function updateLocation($x, $y, $area_id)
    {
        $sql = "UPDATE player_position SET x = ?, y = ?, area_id = ? WHERE player_id = ?";
        $params = [$x, $y, $area_id, $this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", ...$params);
        $result = $stmt->execute();

        if ($result) {
            $this->setPlayerTravelCount();
        }
        return $result;
        
    }
    public function setPlayerTravelCount() {
        $sql = "INSERT INTO player_stats (player_id, travel_count) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE travel_count = travel_count + 1";
        $params = [$this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", ...$params);
        return $stmt->execute();
    }
    
    public function setPlayerPvpBattleCount() {
        $sql = "INSERT INTO player_stats (player_id, pvp_battles) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE pvp_battles = pvp_battles + 1";
        $params = [$this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", ...$params);
        return $stmt->execute();
    }
    public function setPlayerPvpBattleWinCount() {
        $sql = "INSERT INTO player_stats (player_id, pvp_kills) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE pvp_kills = pvp_kills + 1";
        $params = [$this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", ...$params);
        return $stmt->execute();
    }
    public function setPlayerPveBattleWinCount() {
        $sql = "INSERT INTO player_stats (player_id, pve_kills) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE pve_kills = pve_kills + 1";
        $params = [$this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", ...$params);
        return $stmt->execute();
    }
    public function setPlayerPveBattleCount() {
        $sql = "INSERT INTO player_stats (player_id, pve_battles) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE pve_battles = pve_battles + 1";
        $params = [$this->id];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", ...$params);
        return $stmt->execute();
    }

    // Retrieves equipped items for the player
    public function getPlayerEquippedItems()    
    {
        $sql = "SELECT 
        pi.id, 
        pi.equipped, 
        pi.item_id, 
        i.name, 
        i.type, 
        i.life_steal + pi.life_steal as life_steal, 
        i.defence + pi.defence as defence, 
        i.speed + pi.speed as speed, 
        i.stamina + pi.stamina as stamina, 
        i.health + pi.health as health, 
        i.attack + pi.attack as attack, 
        i.crit_chance + pi.crit_chance as crit_chance, 
        i.crit_multi + pi.crit_multi as crit_multi, 
        pi.rarity, 
        pi.quantity 
        FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id WHERE pi.player_id = ? AND pi.equipped = 1
        ORDER BY pi.rarity desc";
        $params = [$this->id];
        return $this->fetchAllRows($sql, $params);
    }
    public function getPlayerInventoryItems()
    {
        $sql = "SELECT 
        pi.id, 
        pi.equipped,
        pi.item_id,
        i.name, 
        i.type, 
        i.life_steal + pi.life_steal as life_steal,
        i.defence + pi.defence as defence,
        i.speed + pi.speed as speed, 
        i.stamina + pi.stamina as stamina, 
        i.health + pi.health as health, 
        i.attack + pi.attack as attack, 
        i.crit_chance + pi.crit_chance as crit_chance, 
        i.crit_multi + pi.crit_multi as crit_multi, 
        pi.rarity,
        pi.quantity,
        i.gold as gold_value
         FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id WHERE pi.player_id = ? AND pi.equipped = 0
         ORDER BY pi.rarity desc";
        $params = [$this->id];
        return $this->fetchAllRows($sql, $params);
    }
    public function getPlayerInventoryCount()
    {
        $sql = "SELECT count(1) count FROM player_inventory pi WHERE pi.player_id = ? AND pi.equipped = 0";
        $params = [$this->id];
        return $this->fetchAllRows($sql, $params);
    }

    // Retrieves battle history for the player
    public function getBattleHistory()
    {
        $sql = "SELECT bh.id, COALESCE(p.name, m.name) AS opponent, bh.result, bh.exp_gain, bh.gold_gain FROM battle_history bh LEFT JOIN monsters m ON m.id = bh.monster_id LEFT JOIN players p ON p.id = bh.opponent_id WHERE bh.player_id = ?";
        $params = [$this->id];
        return $this->fetchAllRows($sql, $params);
    }

    // Updates player's current HP after battle
    public function updateCurrentHp($newHp)
    {
        $newHp = max(0, $newHp);

        $query = "UPDATE players SET c_hp = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $newHp, $this->id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // update players exp, gold and player level after battle
    public function updatePlayerBattleReward($hpChange, $goldChange = 0, $expChange = 0)
    {
        // Update HP, gold, and exp
        $this->updateCurrentHp($hpChange);
        $this->updateGold($goldChange);
        $this->updateExp($expChange);

        // Check for level up
        $currentLevel = $this->getLevel();
        $newExp = $this->getExp() + $expChange;
        $nextLevelExp = $this->getRequiredExp();

        if ($newExp >= $nextLevelExp) {
            $this->levelUp();
        }
    }
    private function getNextLevelExperience($currentLevel)
    {
        // Fetch experience required for next level from exp_table or similar structure
        $query = "SELECT exp_req FROM exp_table WHERE level = ?";
        $params = [$currentLevel + 1];
        $row = $this->fetchSingleRow($query, $params);

        if ($row) {
            return $row['exp_req'];
        }

        return PHP_INT_MAX; // Default to a large number if next level exp requirement not found
    }
    private function levelUp()
    {
        // Perform level up actions
        $currentLevel = $this->getAttribute('level');
        $hpPerLevel = $this->getLevelUpHp();

        // Update player's level and max HP
        $newLevel = $currentLevel + 1;
        $newMaxHP = $this->getAttribute('max_hp') + $hpPerLevel;

        // Prepare and execute the update query
        $query = "UPDATE players SET level = ?, max_hp = ?, exp = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iii', $newLevel, $newMaxHP, $this->id); // Assuming 'i' for integer type
        $stmt->execute();

        // Check for successful update
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            // Handle update failure
            return false;
        }
       
    }

    private function getLevelUpHp()
    {
        // Fetch HP per level from player_classes table
        $query = "SELECT hp_per_level FROM player_classes WHERE id = ?";
        $params = [$this->getClass()];
        $row = $this->fetchSingleRow($query, $params);

        if ($row) {
            return $row['hp_per_level'];
        }

        return 0; // Default if no result found
    }
    private function updateExp($expChange)
    {
        // Update player's experience
        $query = "UPDATE players SET exp = exp + ? WHERE id = ?";
        $params = [$expChange, $this->id];

        // Prepare and execute the update query
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $expChange, $this->id); // Assuming 'i' for integer type
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Updates player's stamina after an action
    public function updateStamina($cost)
    {
        $query = "UPDATE players SET stamina = stamina - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $cost, $this->id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
    private function updateGold($goldChange)
    {
        // Update player's gold
        $query = "UPDATE players SET gold = gold + ? WHERE id = ?";
        $params = [$goldChange, $this->id];

        // Prepare and execute the update query
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $goldChange, $this->id); // Assuming 'i' for integer type
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
    private function getItemGoldValue($item_id)
    {
        $query = "SELECT i.gold FROM items i INNER JOIN player_inventory pi ON pi.item_id = i.id WHERE pi.id = ?";
        $params = [$item_id];
        $row = $this->fetchSingleRow($query, $params);
    
        return $row ? (int)$row['gold'] : 0;
    }
    
    public function sellItem($item_id)
    {
        $itemGoldValue = $this->getItemGoldValue($item_id);
        
        if ($itemGoldValue) {
            $query = "DELETE FROM player_inventory WHERE id = ? AND player_id = ?";
            $params = [$item_id, $this->id];
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", ...$params);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                // Update player's gold
                $this->updateGold($itemGoldValue);
                return true;
            }
        }
    
        return false;
    }

    // Equips an item for the player
    public function equipItem($itemId)
    {
        // Validate if the item exists in the player's inventory
        $inventoryItem = $this->getInventoryItem($itemId);

        if (!$inventoryItem) {
            return "Item not found in player's inventory.";
        }

        // Begin transaction
        $this->conn->begin_transaction();

        try {
            // Unequip the current item of the same type if it exists
            if ($this->unequipPlayerItemByType($inventoryItem['type'])) {
                // Equip the new item
                if ($this->equipPlayerItem($itemId)) {
                    // Commit transaction
                    $this->conn->commit();
                    return "{$inventoryItem['name']} equipped successfully.";
                }
            }

            // Rollback transaction on failure
            $this->conn->rollback();
            return "Failed to equip {$inventoryItem['name']}.";
        } catch (Exception $e) {
            // Handle exceptions and rollback transaction
            $this->conn->rollback();
            return "Error: " . $e->getMessage();
        }
    }

    // Validates if the item exists in the player's inventory
    private function getInventoryItem($itemId)
    {
        $sql = "SELECT pi.id, i.type, i.name FROM player_inventory pi INNER JOIN items i ON i.id = pi.item_id WHERE player_id = ? AND pi.id = ? AND equipped = 0";
        $params = [$this->id, $itemId];
        return $this->fetchSingleRow($sql, $params);
    }


    // Equips the specified item for the player
    public function equipPlayerItem($itemId)
    {
        $sql = "UPDATE player_inventory SET equipped = 1 WHERE player_id = ? AND id = ?";
        $params = [$this->id, $itemId];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $this->id, $itemId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Unequips currently equipped item of a specific type for the player
    private function unequipPlayerItemByType($itemType)
    {
        $sql = "UPDATE player_inventory pi INNER JOIN items i ON i.id = pi.item_id SET pi.equipped = 0 WHERE pi.player_id = ? AND i.type = ? AND pi.equipped = 1";
        $params = [$this->id, $itemType];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $this->id, $itemType);
        $stmt->execute();

        // Check if any rows were affected (an item was unequipped)
        if ($stmt->affected_rows > 0) {
            return true;
        }

        // If no rows were affected, it means there was no item of this type equipped, so return true
        return true;
    }

    // Retrieves details of an item by ID
    public function getItemDetails($itemId)
    {
        $query = "SELECT * FROM items WHERE id = ?";
        $params = [$itemId];
        return $this->fetchSingleRow($query, $params);
    }

    // Utility method to fetch a single row from the database
    private function fetchSingleRow($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Utility method to fetch all rows from the database
    private function fetchAllRows($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function updateProfileImage($imagePath)
    {
        $query = "UPDATE players SET image_path = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $imagePath, $this->id);
        return $stmt->execute();
    }
    
    public function updatePassword($hashedPassword)
    {
        $query = "UPDATE players SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $hashedPassword, $this->id);
        return $stmt->execute();
    }

    public function setBossDefeated($area_id) {
        $stmt = $this->conn->prepare("INSERT INTO player_area_boss (player_id, area_id, boss_defeated) VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE boss_defeated=1");
        $stmt->bind_param('ii', $this->id, $area_id);
        return $stmt->execute();
    }

    // Helper to get guild info for a specific player id
    private function getPlayerGuildById($playerId) {
        $sql = "SELECT g.id, g.name, g.tag FROM guilds g INNER JOIN guild_members gm ON gm.guild_id = g.id WHERE gm.player_id = ?";
        $params = [$playerId];
        return $this->fetchSingleRow($sql, $params);
    }
}