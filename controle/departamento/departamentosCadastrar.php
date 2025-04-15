<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/departamento/departamentos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

// Middware
if (!isset($obj->nome_departamento) || !isset($obj->orcamento) || !isset($obj->localizacao) || !isset($obj->data_criacao)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos."
    ]);
    exit();
}


if (($obj->orcamento <= 0)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados Inválidos."
    ]);
    exit();
}

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();
//
    $nome_departamento = $obj->nome_departamento;
    $orcamento = $obj->orcamento;
    $localizacao = $obj->localizacao;
    $data_criacao = $obj->data_criacao;

    if (($nome_departamento == '') || ($localizacao == '') || ($data_criacao == '')) {
        echo json_encode([
            "cod" => 400,
            "msg" => "Dados Vazios."
        ]);
        exit();
    }
    

    $nome_departamento = strip_tags($nome_departamento);

    $departamento = new Departamentos();
    $departamento->setnome_departamento($nome_departamento);
    $departamento->setorcamento($orcamento);
    $departamento->setlocalizacao($localizacao);
    $departamento->setdata_criacao($data_criacao);

    if ($departamento->cadastrar()) {
        echo json_encode([
            "cod" => 201,
            "msg" => "Departamento cadastrado com sucesso.",
            "Departamento" => $departamento
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "ERRO ao cadastrar departamento"
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