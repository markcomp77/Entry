<?php
require_once '../db_config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

if (isset($_POST['id'])) {
    $stmt = $conn->prepare("DELETE FROM Record WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: ../index.php');
}
?>
