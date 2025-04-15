<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/perfil/perfis.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$metodo = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo === "DELETE") {
        // Extrai o ID do perfil da URI
        $vetor = explode("/", $_SERVER['REQUEST_URI']);
        $id_perfil = intval($vetor[2] ?? 0);

        if ($id_perfil <= 0) {
            echo json_encode([
                "cod" => 400,
                "msg" => "ID do perfil inválido ou não fornecido."
            ]);
            exit();
        }

        $Perfil = new Perfil();
        $Perfil->setId($id_perfil);

        if ($Perfil->delete()) {
            header("HTTP/1.1 204 No Content"); 
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error"); 
            echo json_encode([
                "cod" => 500,
                "msg" => "Erro ao excluir perfil."
            ]);
            exit();
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: DELETE");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método HTTP não permitido. Apenas o método DELETE é suportado para exclusão de perfil."
        ]);
        exit();
    }
} else {
}
?>