<?php
require_once '../controller/Database.php'; // Adjust the path as needed

class Monster {
    private $conn;
    private $id;

    public function getSpeed() {
        return $this->getAttribute('speed');
    }
    public function getName() {
        return $this->getAttribute('name');
    }
    public function getLevel() {
        return $this->getAttribute('level');
    }
    public function getAttack() {
        return $this->getAttribute('attack');
    }
    public function getDefence() {
        return $this->getAttribute('defence');
    }
    public function getMaxHp() {
        return $this->getAttribute('hp');
    }
    public function getExp() {
        return $this->getAttribute('on_death_exp');
    }
    public function getGold() {
        return $this->getAttribute('on_death_gold');
    }


    // Add more methods for other attributes as needed...
    private function getAttribute($attributeName) {
        $query = "SELECT $attributeName FROM monsters WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row[$attributeName];
        }
        return null;
    }

    public function __construct($conn, $id = null) {
        $this->conn = $conn;
        $this->id = $id;
    }

    public function getMonsterList($playerId) {
        // Generate a random number of monsters when no ID is provided
        $num_monsters = rand(0, 6);

        // SQL query to retrieve monsters based on the player's area and level
        $query = 'SELECT 
                    m.id,
                    m.name,
                    m.level,
                    m.hp,
                    m.attack,
                    m.speed,
                    m.on_death_exp,
                    m.on_death_gold,
                    m.defence
                  FROM
                    player_position pa
                    INNER JOIN areas a ON a.id = pa.area_id
                    LEFT JOIN monsters m ON m.level BETWEEN a.min_level AND a.max_level
                  WHERE
                    pa.player_id = ? ORDER BY RAND() LIMIT ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $playerId, $num_monsters); // Bind the player ID and number of monsters
        $stmt->execute();

        // Fetch the results and return them as an array
        $result = $stmt->get_result();
        $monsters = [];
        while ($row = $result->fetch_assoc()) {
            $monsters[] = $row;
        }

        return $monsters;
    }

    public function getMonster($monsterId) {
        // SQL query to retrieve a specific monster by ID
        $query = 'SELECT 
                    id, 
                    name, 
                    level, 
                    hp, 
                    attack, 
                    speed, 
                    on_death_exp, 
                    on_death_gold, 
                    defence 
                  FROM monsters 
                  WHERE id = ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $monsterId); // Bind the monster ID
        $stmt->execute();

        // Fetch the result and return it
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }

        return null;
    }
}

