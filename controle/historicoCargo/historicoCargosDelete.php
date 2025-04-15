<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/historicoCargo/historicoCargos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$metodo = $_SERVER['REQUEST_METHOD'];

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo === "DELETE") {
        // Extrai o ID do histórico da URI
        $vetor = explode("/", $_SERVER['REQUEST_URI']);
        $id_historico = intval($vetor[2] ?? 0);

        $id_historico = strip_tags($id_historico);


        if ($id_historico == '' || !isset($id_historico)) {
            $resposta['cod'] = 3;
            $resposta['msg'] = "id nao pode ser vazio";
            exit();
        }

        if ($id_historico <= 0) {
            echo json_encode([
                "cod" => 400,
                "msg" => "ID do histórico de cargo inválido."
            ]);
            exit();
        }

        $historico = new HistoricoCargo();
        $historico->setId($id_historico);
        if ($historico->delete()) {
            header("HTTP/1.1 204 No Content");
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "cod" => 500,
                "msg" => "Erro ao excluir histórico de cargo."
            ]);
            exit();
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: DELETE");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método HTTP não permitido. Apenas o método DELETE é suportado para exclusão de histórico de cargo."
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