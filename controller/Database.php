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

    // public function __destruct() {
    //     $this->conn->close();
    // }
}
?>
