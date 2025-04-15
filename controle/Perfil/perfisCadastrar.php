<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/perfil/perfis.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (
    !isset($obj->id_funcionario) || !isset($obj->idade) || !isset($obj->endereco) ||
    !isset($obj->telefone) || !isset($obj->genero) || !isset($obj->estado_civil)
) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça todos os campos obrigatórios."
    ]);
    exit();
}

$id_funcionario = (int) $obj->id_funcionario;
$idade = (int) $obj->idade;
$endereco = strip_tags($obj->endereco);
$telefone = (float) $obj->telefone;
$genero = strip_tags($obj->genero);
$estado_civil = strip_tags($obj->estado_civil);

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    $perfil = new Perfil();
    $perfil->setId_funcionario($id_funcionario);
    $perfil->setIdade($idade);
    $perfil->setEndereco($endereco);
    $perfil->setTelefone($telefone);
    $perfil->setGenero($genero);
    $perfil->setEstado_civil($estado_civil);

    if ($perfil->cadastrar()) {
        echo json_encode([
            "cod" => 201,
            "msg" => "Perfil cadastrado com sucesso!",
            "perfil" => $perfil
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "Erro ao cadastrar o perfil."
        ]);
    }
} else {
}
?>