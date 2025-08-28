<?php
require_once '../db_config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id' => $_POST['id'],
        'autor' => $_POST['autor'],
        'temat' => $_POST['temat'],
        'data' => $_POST['data'],
        'godzina' => $_POST['godzina'],
        'tresc' => $_POST['tresc'],
        'typ' => $_POST['typ'],
        'status' => $_POST['status'],
        'kontakt1' => $_POST['kontakt1'] ?? null,
        'kontakt2' => $_POST['kontakt2'] ?? null,
        'kontakt3' => $_POST['kontakt3'] ?? null,
        'priorytet' => $_POST['priorytet'],
        'termin' => $_POST['termin'] ?? null,
        'uwaga' => $_POST['uwaga'] ?? null,
        'notatka' => $_POST['notatka'] ?? null
    ];

    $stmt = $conn->prepare("
        UPDATE Record SET
            autor = :autor,
            temat = :temat,
            data = :data,
            godzina = :godzina,
            tresc = :tresc,
            typ = :typ,
            status = :status,
            kontakt1 = :kontakt1,
            kontakt2 = :kontakt2,
            kontakt3 = :kontakt3,
            priorytet = :priorytet,
            termin = :termin,
            uwaga = :uwaga,
            notatka = :notatka
        WHERE id = :id
    ");
    $stmt->execute($data);
    header('Location: ../index.php');
}
?>
