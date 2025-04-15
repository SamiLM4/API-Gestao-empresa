<?php

use Firebase\JWT\MeuTokenJWT2;
require_once "modelo/historicoCargo/historicoCargos.php";
require_once "modelo/tokenJWT/MeuTokenJWT2.php";

$headers = getallheaders();
$authorization = $headers['Authorization'] ?? null;

$headers = getallheaders();

$meutoken = new MeuTokenJWT2();

if ($meutoken->validarToken($authorization) === true) {
    $payloadRecuperado = $meutoken->getPayload();

    $historicoCargo = new HistoricoCargo();
    $historicos = $historicoCargo->read();

    header("Content-Type: application/json");

    if ($historicos) {
        header("HTTP/1.1 200 OK");


        $historicosArray = array_map(function ($h) {
            return $h->toArray();
        }, $historicos);

        echo json_encode([
            "cod" => 200,
            "msg" => "Histórico de cargos encontrado.",
            "historicoCargos" => $historicosArray
        ], JSON_PRETTY_PRINT);

    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode([
            "cod" => 404,
            "msg" => "Nenhum histórico de cargos encontrado."
        ]);
    }

} else {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);
}
?>