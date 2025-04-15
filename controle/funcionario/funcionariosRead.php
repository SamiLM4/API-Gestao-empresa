<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/funcionario/funcionarios.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";
$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    $funcionario = new Funcionarios();
    $funcionario = $funcionario->read();

    header("Content-Type: application/json");
    if ($funcionario) {
        header("HTTP/1.1 200 OK");

        // Converter cada objeto Curso em um array
        $funcionarioArray = array_map(function ($funcionario) {
            return $funcionario->toArray();
        }, $funcionario);
        //          $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
//          Codificar o resultado como JSON
        echo json_encode($funcionarioArray, JSON_PRETTY_PRINT);
        //        echo json_encode(["Token Novo"]);
//        echo json_encode($tokenNovo, JSON_PRETTY_PRINT);

    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Nenhum funcionario encontrado."]);
    }


} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);

}


?>