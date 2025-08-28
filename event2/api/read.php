<?php
require_once __DIR__ . "/../db_config.php";
require_login();

header("Content-Type: application/json");

$page = max(1, (int)($_GET["page"] ?? 1));
$per_page = max(1, min(100, (int)($_GET["per_page"] ?? 10)));
$offset = ($page - 1) * $per_page;

$q = trim($_GET["q"] ?? "");
$f_typ = trim($_GET["typ"] ?? "");
$f_status = trim($_GET["status"] ?? "");
$f_priorytet = trim($_GET["priorytet"] ?? "");
$f_autor = trim($_GET["autor"] ?? "");
$f_date_from = trim($_GET["date_from"] ?? "");
$f_date_to = trim($_GET["date_to"] ?? "");

$where = [];
$params = [];

if ($q !== "") {
    $where[] = "(autor LIKE :q OR temat LIKE :q OR tresc LIKE :q)";
    $params[":q"] = "%{$q}%";
}
if ($f_typ !== "") { $where[] = "typ = :typ"; $params[":typ"] = $f_typ; }
if ($f_status !== "") { $where[] = "status = :status"; $params[":status"] = $f_status; }
if ($f_priorytet !== "") { $where[] = "priorytet = :priorytet"; $params[":priorytet"] = $f_priorytet; }
if ($f_autor !== "") { $where[] = "autor = :autor"; $params[":autor"] = $f_autor; }
if ($f_date_from !== "") { $where[] = "date(data) >= date(:df)"; $params[":df"] = $f_date_from; }
if ($f_date_to !== "") { $where[] = "date(data) <= date(:dt)"; $params[":dt"] = $f_date_to; }

$where_sql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

$count_sql = "SELECT COUNT(*) AS c FROM records $where_sql";
$stc = $pdo->prepare($count_sql);
$stc->execute($params);
$total = (int)$stc->fetch(PDO::FETCH_ASSOC)["c"];

$sql = "SELECT * FROM records $where_sql ORDER BY date(data) DESC, time(godzina) DESC LIMIT :lim OFFSET :off";
$st = $pdo->prepare($sql);
foreach ($params as $k => $v) $st->bindValue($k, $v);
$st->bindValue(":lim", $per_page, PDO::PARAM_INT);
$st->bindValue(":off", $offset, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "items" => $rows,
    "page" => $page,
    "per_page" => $per_page,
    "total" => $total,
    "pages" => max(1, (int)ceil($total / $per_page))
]);
