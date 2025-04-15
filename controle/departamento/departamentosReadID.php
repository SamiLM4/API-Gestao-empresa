<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/departamento/departamentos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo == "GET") {
        $id = $vetor[2];

        //Middware
        $id = strip_tags($id);
        if (!isset($id) || ($id == '')) {
            echo json_encode([
                "cod" => 400,
                "msg" => "Dados incompletos."
            ]);
            exit();
        }
        //

        $departamento = new Departamentos();
        $departamento->setid($id);
        $departamentoSelecionado = $departamento->readID();

        if ($departamentoSelecionado) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "departamento encontrado",
                "departamento" => [
                    "id" => $departamentoSelecionado->getid(),
                    "nome_departamento" => $departamentoSelecionado->getnome_departamento(),
                    "orcamento" => $departamentoSelecionado->getorcamento(),
                    "localizacao" => $departamentoSelecionado->getlocalizacao(),
                    "data_criacao" => $departamentoSelecionado->getdata_criacao()
                ]
            ]);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "cod" => 404,
                "msg" => "Departamento não encontrado"
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