<?php
require_once __DIR__ . "/db_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    header("Location: index.php");
    exit;
}

// ensure default admin exists (admin/admin) if users table empty
$stmt = $pdo->query("SELECT COUNT(*) AS c FROM users");
$count = (int)$stmt->fetch(PDO::FETCH_ASSOC)["c"];
if ($count === 0) {
    $default_hash = password_hash("admin", PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")
        ->execute(["admin", $default_hash]);
}

$stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = (int)$user["id"];
    $_SESSION["username"] = $user["username"];
    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;
