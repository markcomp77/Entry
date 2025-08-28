<?php
require_once __DIR__ . "/../db_config.php";
require_login();
header("Content-Type: application/json");

// tylko POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// JSON body z { id: number }
$input = json_decode(file_get_contents("php://input"), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad id']);
    exit;
}

$st = $pdo->prepare("DELETE FROM records WHERE id = ?");
$st->execute([$id]);

echo json_encode(['success' => true]);
