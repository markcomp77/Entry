<?php
// db.php
define('ROOT', __DIR__);

$host = 'localhost';
$user = 'admin';
$pass = 'admin';
$db   = 'event_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Błąd połączenia z bazą']));
}

$conn->set_charset('utf8');
?>
