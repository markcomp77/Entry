<?php
require_once 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filters = [
    'typ' => isset($_GET['typ']) ? $_GET['typ'] : '',
    'status' => isset($_GET['status']) ? $_GET['status'] : '',
    'priorytet' => isset($_GET['priorytet']) ? $_GET['priorytet'] : ''
];

$where = [];
$params = [];
if ($search) {
    $where[] = "(temat LIKE ? OR autor LIKE ? OR tresc LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
foreach ($filters as $key => $value) {
    if ($value) {
        $where[] = "$key = ?";
        $params[] = $value;
    }
}

$where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$stmt = $conn->prepare("SELECT COUNT(*) FROM Record $where_clause");
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

$stmt = $conn->prepare("SELECT * FROM Record $where_clause ORDER BY data DESC, godzina DESC LIMIT ? OFFSET ?");
$params[] = $records_per_page;
$params[] = $offset;
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event2 - Zarządzanie wydarzeniami</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Event2</h1>
        <a href="logout.php">Wyloguj</a>
    </header>
    <main>
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Szukaj..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="typ">
                <option value="">Wszystkie typy</option>
                <option value="Spotkanie" <?php if ($filters['typ'] == 'Spotkanie') echo 'selected'; ?>>Spotkanie</option>
                <option value="Zadanie" <?php if ($filters['typ'] == 'Zadanie') echo 'selected'; ?>>Zadanie</option>
            </select>
            <select name="status">
                <option value="">Wszystkie statusy</option>
                <option value="Otwarte" <?php if ($filters['status'] == 'Otwarte') echo 'selected'; ?>>Otwarte</option>
                <option value="Zamknięte" <?php if ($filters['status'] == 'Zamknięte') echo 'selected'; ?>>Zamknięte</option>
            </select>
            <select name="priorytet">
                <option value="">Wszystkie priorytety</option>
                <option value="Wysoki" <?php if ($filters['priorytet'] == 'Wysoki') echo 'selected'; ?>>Wysoki</option>
                <option value="Średni" <?php if ($filters['priorytet'] == 'Średni') echo 'selected'; ?>>Średni</option>
                <option value="Niski" <?php if ($filters['priorytet'] == 'Niski') echo 'selected'; ?>>Niski</option>
            </select>
            <button type="submit">Filtruj</button>
        </form>

        <button onclick="showForm()">Dodaj wydarzenie</button>
        <div id="form-container" style="display: none;">
            <form id="event-form" action="api/create.php" method="POST">
                <input type="text" name="autor" placeholder="Autor" required>
                <input type="text" name="temat" placeholder="Temat" required>
                <input type="date" name="data" required>
                <input type="time" name="godzina" required>
                <textarea name="tresc" placeholder="Treść" required></textarea>
                <select name="typ" required>
                    <option value="Spotkanie">Spotkanie</option>
                    <option value="Zadanie">Zadanie</option>
                </select>
                <select name="status" required>
                    <option value="Otwarte">Otwarte</option>
                    <option value="Zamknięte">Zamknięte</option>
                </select>
                <input type="text" name="kontakt1" placeholder="Kontakt 1">
                <input type="text" name="kontakt2" placeholder="Kontakt 2">
                <input type="text" name="kontakt3" placeholder="Kontakt 3">
                <select name="priorytet" required>
                    <option value="Wysoki">Wysoki</option>
                    <option value="Średni">Średni</option>
                    <option value="Niski">Niski</option>
                </select>
                <input type="date" name="termin" placeholder="Termin">
                <input type="text" name="uwaga" placeholder="Uwaga">
                <textarea name="notatka" placeholder="Notatka"></textarea>
                <button type="submit">Zapisz</button>
                <button type="button" onclick="hideForm()">Anuluj</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autor</th>
                    <th>Temat</th>
                    <th>Data</th>
                    <th>Godzina</th>
                    <th>Typ</th>
                    <th>Status</th>
                    <th>Priorytet</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo htmlspecialchars($record['autor']); ?></td>
                        <td><?php echo htmlspecialchars($record['temat']); ?></td>
                        <td><?php echo $record['data']; ?></td>
                        <td><?php echo $record['godzina']; ?></td>
                        <td><?php echo $record['typ']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td><?php echo $record['priorytet']; ?></td>
                        <td>
                            <button onclick="editRecord(<?php echo $record['id']; ?>)">Edytuj</button>
                            <button onclick="deleteRecord(<?php echo $record['id']; ?>)">Usuń</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&typ=<?php echo urlencode($filters['typ']); ?>&status=<?php echo urlencode($filters['status']); ?>&priorytet=<?php echo urlencode($filters['priorytet']); ?>">Poprzednia</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&typ=<?php echo urlencode($filters['typ']); ?>&status=<?php echo urlencode($filters['status']); ?>&priorytet=<?php echo urlencode($filters['priorytet']); ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&typ=<?php echo urlencode($filters['typ']); ?>&status=<?php echo urlencode($filters['status']); ?>&priorytet=<?php echo urlencode($filters['priorytet']); ?>">Następna</a>
            <?php endif; ?>
        </div>
    </main>
    <script src="app.js"></script>
</body>
</html>
