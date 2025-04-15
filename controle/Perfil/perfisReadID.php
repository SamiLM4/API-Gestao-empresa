<?php

use Firebase\JWT\MeuTokenJWT2;
require_once "modelo/perfil/perfis.php";
require_once "modelo/tokenJWT/MeuTokenJWT2.php";

$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();
$authorization = $headers['Authorization'] ?? null;

$meutoken = new MeuTokenJWT2();

if ($meutoken->validarToken($authorization) === true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo === "GET") {
        
        $id_perfil = $vetor[2] ?? null;

        if (!$id_perfil || !is_numeric($id_perfil)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "cod" => 400,
                "msg" => "ID do perfil inválido ou ausente."
            ]);
            exit();
        }

        $perfil = new Perfil();
        $perfil->setId($id_perfil);
        $perfilSelecionado = $perfil->readID();

        

        if ($perfilSelecionado) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "Perfil encontrado",
                "perfil" => $perfilSelecionado->toArray()  

            ]);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "cod" => 404,
                "msg" => "Perfil não encontrado"
            ]);
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método não permitido"
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