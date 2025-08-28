<?php
require_once 'db_config.php';
$stmt = $conn->prepare("INSERT INTO User (id, username, password) VALUES (?, ?, ?)");
$stmt->execute([1, 'user1', password_hash('password1', PASSWORD_DEFAULT)]);
$stmt->execute([2, 'admin', password_hash('admin', PASSWORD_DEFAULT)]);
?>
