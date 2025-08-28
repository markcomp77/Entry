<?php
// db_config.php
declare(strict_types=1);
session_start();

// Path to SQLite file (non-public folder)
$db_file = __DIR__ . "/db/database.sqlite";

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (Exception $e) {
    http_response_code(500);
    die("Błąd połączenia z bazą: " . htmlspecialchars($e->getMessage()));
}

// Create schema if not exists
$schema = <<<SQL
CREATE TABLE IF NOT EXISTS records (
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
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
);
SQL;

$pdo->exec($schema);

// Helper: require login for API endpoints (except login.php)
function require_login(): void {
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        header("Content-Type: application/json");
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }
}
?>
