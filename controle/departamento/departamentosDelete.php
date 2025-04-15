<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/departamento/departamentos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$metodo = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();


if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo === "DELETE") {
        $vetor = explode("/", $_SERVER['REQUEST_URI']);
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

        if ($departamento->delete()) {
            header("HTTP/1.1 204 No Content");
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "cod" => 500,
                "msg" => "Erro ao excluir Departamento."
            ]);
            exit();
        }
    } else {
        
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: DELETE");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método HTTP não permitido. Apenas o método DELETE é suportado para exclusão de Departamento."
        ]);
        exit();
    }
} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);
}
?>