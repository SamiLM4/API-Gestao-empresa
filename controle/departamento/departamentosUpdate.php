<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/departamento/departamentos.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

$id = $obj->id;
$nome_departamento = $obj->nome_departamento;
$orcamento = $obj->orcamento;
$localizacao = $obj->localizacao;
$data_criacao = $obj->data_criacao;

$id = strip_tags($id);
$nome_departamento = strip_tags($nome_departamento);
$orcamento = strip_tags($orcamento);
$localizacao = strip_tags($localizacao);
$data_criacao = strip_tags($data_criacao);

$resposta = array();
$verificador = 0;

if ($nome_departamento == '' || !isset($nome_departamento)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome_departamento nao pode ser vazio";
    $verificador = 1;
} else if ($orcamento == '' || !isset($orcamento)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "orcamento nao pode ser vazio";
    $verificador = 1;
} else if ($localizacao == '' || !isset($localizacao)){
    $resposta['cod'] = 3;
    $resposta['msg'] = "localizacao nao pode ser vazio";
    $verificador = 1;
} else if ($data_criacao == '' || !isset($data_criacao)){
    $resposta['cod'] = 3;
    $resposta['msg'] = "data de criacao nao pode ser vazio";
    $verificador = 1;
}

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($verificador == 0) {
        $departamento = new Departamentos();
        $departamento->setid($id);
        $departamento->setnome_departamento($nome_departamento);
        $departamento->setorcamento($orcamento);
        $departamento->setlocalizacao($localizacao);
        $departamento->setdata_criacao($data_criacao); 

        $resultado = $departamento->update();
        if ($resultado == true) {
            header("HTTP/1.1 201 Created");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Atualizado com sucesso!!!";
            $resposta['Departamento'] = $departamento;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            $resposta['cod'] = 5;
            $resposta['msg'] = "ERRO ao cadastrar o departamento";
        }
    }

} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);
}

echo json_encode($resposta);

?>