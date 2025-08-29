<?php
// db.php
define('ROOT', __DIR__);
define('DB_FILE', ROOT . '/data/events.db');

// Utwórz katalog data/, jeśli nie istnieje
if (!is_dir('data')) {
    mkdir('data', 0755, true);
}

try {
    // Połączenie z SQLite
    $conn = new PDO('sqlite:' . DB_FILE);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("PRAGMA foreign_keys = ON");

    // Utwórz tabelę, jeśli nie istnieje
    $conn->exec("
        CREATE TABLE IF NOT EXISTS events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            event_date TEXT NOT NULL,  -- ISO format: YYYY-MM-DD
            created_at TEXT DEFAULT (date('now'))
        )
    ");

} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]));
}
?>
