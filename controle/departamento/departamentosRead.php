<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/departamento/departamentos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();


if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    $departamento = new Departamentos();
    $departamento = $departamento->read();

    header("Content-Type: application/json");
    if ($departamento) {
        header("HTTP/1.1 200 OK");

        
        $departamentoArray = array_map(function ($departamento) {
            return $departamento->toArray();
        }, $departamento);

        
        echo json_encode($departamentoArray, JSON_PRETTY_PRINT);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Nenhum departamento encontrado."]);
    }
} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);

}
?>