<?php
// api/events.php
require '../db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT id, title, event_date FROM events ORDER BY event_date");
        $events = $result->fetch_all(MYSQLI_ASSOC);
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
        $stmt->bind_param("ss", $title, $date);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
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
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
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
