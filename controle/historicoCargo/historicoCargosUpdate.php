<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/historicoCargo/historicoCargos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();


$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);


$id = strip_tags($obj->id ?? '');
$id_funcionario = strip_tags($obj->id_funcionario ?? '');
$cargos_anteriores = strip_tags($obj->cargos_anteriores ?? '');
$cargo_atual = strip_tags($obj->cargo_atual ?? '');
$data_alteracao = strip_tags($obj->data_alteracao ?? '');


$resposta = array();
$verificador = 0;

if ($id == '' || !isset($id)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "ID do histórico não pode ser vazio";
    $verificador = 1;
} else if ($id_funcionario == '' || !isset($id_funcionario)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "ID do funcionário não pode ser vazio";
    $verificador = 1;
} else if ($cargo_atual == '' || !isset($cargo_atual)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Cargo atual não pode ser vazio";
    $verificador = 1;
} else if ($data_alteracao == '' || !isset($data_alteracao)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Data da alteração não pode ser vazia";
    $verificador = 1;
}

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($verificador == 0) {
        $historico = new HistoricoCargo();
        $historico->setId($id);
        $historico->setId_funcionario($id_funcionario);
        $historico->setCargos_anteriores($cargos_anteriores);
        $historico->setCargo_atual($cargo_atual);
        $historico->setData_alteracao($data_alteracao);

        $resultado = $historico->update();

        if ($resultado == true) {
            header("HTTP/1.1 200 OK");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Histórico de cargo atualizado com sucesso!";
            $resposta['historico'] = $historico->toArray();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            $resposta['cod'] = 5;
            $resposta['msg'] = "Erro ao atualizar o histórico de cargo";
        }
    }
} else {

    header("HTTP/1.1 401 Unauthorized");
    $resposta = json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);

}

echo json_encode($resposta);
?>