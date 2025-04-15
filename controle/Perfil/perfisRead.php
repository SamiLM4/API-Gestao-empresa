<?php

use Firebase\JWT\MeuTokenJWT2;
require_once "modelo/perfil/perfis.php";
require_once "modelo/tokenJWT/MeuTokenJWT2.php";

$headers = getallheaders();
$authorization = $headers['Authorization'] ?? null;

$meutoken = new MeuTokenJWT2();

if ($meutoken->validarToken($authorization) === true) {
    $payloadRecuperado = $meutoken->getPayload();

    $perfil = new Perfil();
    $perfis = $perfil->read();

    header("Content-Type: application/json");

    if ($perfis) {
        header("HTTP/1.1 200 OK");

        $perfisArray = array_map(function ($perfil) {
            return $perfil->toArray();
        }, $perfis);

        //        $tokenNovo = $meutoken->gerarToken($payloadRecuperado);
        echo json_encode([
            "cod" => 200,
            "msg" => "Perfis encontrados.",
            "perfis" => $perfisArray
        ], JSON_PRETTY_PRINT);

    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode([
            "cod" => 404,
            "msg" => "Nenhum perfil encontrado."
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