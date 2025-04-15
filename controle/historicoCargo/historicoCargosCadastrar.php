<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/historicoCargo/historicoCargos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->id_funcionario) || !isset($obj->cargos_anteriores) || !isset($obj->cargo_atual) || !isset($obj->data_alteracao)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça todos os campos obrigatórios."
    ]);
    exit();
}

$id_funcionario = (int) $obj->id_funcionario;
$cargos_anteriores = strip_tags($obj->cargos_anteriores);
$cargo_atual = strip_tags($obj->cargo_atual);
$data_alteracao = strip_tags($obj->data_alteracao);

if ($id_funcionario == '' || $cargos_anteriores == '' || $cargo_atual == '' || $data_alteracao == '') {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça todos os campos obrigatórios."
    ]);
    exit();
}


$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();
    $historico = new HistoricoCargo();
    $historico->setId_funcionario($id_funcionario);
    $historico->setCargos_anteriores($cargos_anteriores);
    $historico->setCargo_atual($cargo_atual);
    $historico->setData_alteracao($data_alteracao);

    if ($historico->cadastrar()) {
        echo json_encode([
            "cod" => 201,
            "msg" => "Histórico de cargo cadastrado com sucesso!",
            "historico" => $historico
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "Erro ao cadastrar o histórico de cargo."
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