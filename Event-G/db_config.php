<?php
class Database {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('sqlite:'.__DIR__.'/db/database.sqlite');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initDatabase();
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function initDatabase() {
        // Create Record table
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS Record (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                autor TEXT NOT NULL,
                temat TEXT NOT NULL,
                data DATE NOT NULL,
                godzina TIME NOT NULL,
                tresc TEXT NOT NULL,
                typ TEXT NOT NULL,
                status TEXT NOT NULL,
                kontakt1 TEXT,
                kontakt2 TEXT,
                kontakt3 TEXT,
                priorytet TEXT NOT NULL,
                termin DATE,
                uwaga TEXT,
                notatka TEXT
            )
        ");

        // Create User table
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS User (
                id INTEGER PRIMARY KEY,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )
        ");
    }

    public function getConnection() {
        return $this->db;
    }
}

session_start();
$db = new Database();
$conn = $db->getConnection();
?>
