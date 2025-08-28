<?php
require_once __DIR__ . "/../db_config.php";
require_login();
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input["id"])) { http_response_code(400); echo json_encode(["error"=>"Invalid JSON"]); exit; }

$sql = "UPDATE records SET
autor=:autor, temat=:temat, data=:data, godzina=:godzina, tresc=:tresc,
typ=:typ, status=:status, kontakt1=:kontakt1, kontakt2=:kontakt2, kontakt3=:kontakt3,
priorytet=:priorytet, termin=:termin, uwaga=:uwaga, notatka=:notatka
WHERE id=:id";
$st = $pdo->prepare($sql);
$st->execute([
    ":autor" => $input["autor"] ?? "",
    ":temat" => $input["temat"] ?? "",
    ":data" => $input["data"] ?? "",
    ":godzina" => $input["godzina"] ?? "",
    ":tresc" => $input["tresc"] ?? "",
    ":typ" => $input["typ"] ?? "",
    ":status" => $input["status"] ?? "",
    ":kontakt1" => $input["kontakt1"] ?? null,
    ":kontakt2" => $input["kontakt2"] ?? null,
    ":kontakt3" => $input["kontakt3"] ?? null,
    ":priorytet" => $input["priorytet"] ?? "",
    ":termin" => $input["termin"] ?? null,
    ":uwaga" => $input["uwaga"] ?? null,
    ":notatka" => $input["notatka"] ?? null,
    ":id" => (int)$input["id"]
]);
echo json_encode(["success"=>true]);
