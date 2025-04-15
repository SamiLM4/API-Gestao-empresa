<?php

use Firebase\JWT\MeuTokenJWT2;
require_once "modelo/historicoCargo/historicoCargos.php";
require_once "modelo/tokenJWT/MeuTokenJWT2.php";

$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];


$headers = getallheaders();
$authorization = $headers['Authorization'] ?? null;

$meutoken = new MeuTokenJWT2();

if ($meutoken->validarToken($authorization) === true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo === "GET") {
        $id_historico = $vetor[2] ?? null;

        if (!$id_historico || !is_numeric($id_historico)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "cod" => 400,
                "msg" => "ID do histórico inválido ou ausente."
            ]);
            exit();
        }

        $id_historico = strip_tags($id_historico);


        if ($id_historico == '' || !isset($id_historico)) {
            $resposta['cod'] = 3;
            $resposta['msg'] = "id nao pode ser vazio";
            exit();
        }

        $historico = new HistoricoCargo();
        $historico->setId($id_historico);
        $historicoSelecionado = $historico->readID();

        if ($historicoSelecionado) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "Histórico de cargo encontrado.",
                "historico" => $historicoSelecionado->toArray()
            ]);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "cod" => 404,
                "msg" => "Histórico de cargo não encontrado."
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