<?php
require_once '../db_config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM Record WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($record);
}
?>
