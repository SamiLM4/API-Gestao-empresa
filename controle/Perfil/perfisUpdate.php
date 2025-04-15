<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/perfil/perfis.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

$id = $obj->id;  
$id_funcionario = $obj->id_funcionario; 
$idade = $obj->idade; 
$endereco = $obj->endereco;  
$telefone = $obj->telefone;  
$genero = $obj->genero;  
$estado_civil = $obj->estado_civil;  


$id = strip_tags($id);
$id_funcionario = strip_tags($id_funcionario);
$idade = strip_tags($idade);
$endereco = strip_tags($endereco);
$telefone = strip_tags($telefone);
$genero = strip_tags($genero);
$estado_civil = strip_tags($estado_civil);

$resposta = array();
$verificador = 0;


if ($id_funcionario == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "ID do funcionário não pode ser vazio";
    $verificador = 1;
} else if ($idade == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Idade não pode ser vazia";
    $verificador = 1;
} else if ($endereco == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Endereço não pode ser vazio";
    $verificador = 1;
} else if ($telefone == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Telefone não pode ser vazio";
    $verificador = 1;
} else if ($genero == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Gênero não pode ser vazio";
    $verificador = 1;
} else if ($estado_civil == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Estado civil não pode ser vazio";
    $verificador = 1;
}

if (!is_numeric($idade)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Idade deve ser um número";
    $verificador = 1;
}

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($verificador == 0) {
        $perfil = new Perfil();
        $perfil->setId($id);
        $perfil->setId_funcionario($id_funcionario);
        $perfil->setIdade($idade);
        $perfil->setEndereco($endereco);
        $perfil->setTelefone($telefone);
        $perfil->setGenero($genero);
        $perfil->setEstado_civil($estado_civil);

        $resultado = $perfil->update();
        if ($resultado == true) {
            header("HTTP/1.1 201 Created");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Perfil atualizado com sucesso!";
            $resposta['perfil'] = $perfil->toArray(); 
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            $resposta['cod'] = 5;
            $resposta['msg'] = "Erro ao atualizar o perfil";
        }
    }
} else {

    header("HTTP/1.1 401 Unauthorized");
    $resposta = [
        "cod" => 401,
        "msg" => "Token inválido!"
    ];

}
echo json_encode($resposta);

?>