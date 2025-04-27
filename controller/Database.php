<?php

class Database {
    private $conn;

    public function __construct() {
        // db configuration can live here for now
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'battlewarz';
        $this->conn = new mysqli($host, $user, $pass, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if ($params && count($params)) {
            // Dynamically bind parameters
            $types = str_repeat('s', count($params)); // assumes all params are strings
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $stmt->close();
        return $rows;
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if ($params && count($params)) {
            $types = str_repeat('s', count($params)); // assumes all params are strings
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if ($params && count($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function beginTransaction() {
        $this->conn->begin_transaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollBack() {
        $this->conn->rollback();
    }

    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    // public function __destruct() {
    //     $this->conn->close();
    // }
}

?>
