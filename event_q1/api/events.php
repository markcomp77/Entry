<?php
require '../db.php';  // ← teraz łączy się z SQLite

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->query("SELECT id, title, event_date FROM events ORDER BY event_date");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $events]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $title = trim($input['title'] ?? '');
        $date  = $input['event_date'] ?? '';

        if (empty($title) || empty($date)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Wszystkie pola są wymagane']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO events (title, event_date) VALUES (?, ?)");
        $result = $stmt->execute([$title, $date]);

        if ($result) {
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Błąd zapisu']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Nieprawidłowe ID']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Błąd usuwania']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Metoda niedozwolona']);
}
?>
