<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/funcionario/funcionarios.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";
$headers = getallheaders();
$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();
$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

$id = $obj->id;
$nome = $obj->nome;
$email = $obj->email;
$cpf = $obj->cpf;
$cargo = $obj->cargo;
$salario = $obj->salario;
$data_contratacao = $obj->data_contratacao;
$departamento = $obj->departamento;
$funcionario_supervisor = $obj->funcionario_supervisor;

$id = strip_tags($id);
$nome = strip_tags($nome);
$email = strip_tags($email);
$cpf = strip_tags($cpf);
$cargo = strip_tags($cargo);
$salario = strip_tags($salario);
$data_contratacao = strip_tags($data_contratacao);
$departamento = strip_tags($departamento);
$funcionario_supervisor = strip_tags($funcionario_supervisor);

if (
    $id == '' || !isset($id) ||
    $nome == '' || !isset($nome) ||
    $email == '' || !isset($email) ||
    $cpf == '' || !isset($cpf) ||
    $cargo == '' || !isset($cargo) ||
    $salario == '' || !isset($salario) ||
    $data_contratacao == '' || !isset($data_contratacao)
) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Dados inválidos";
    echo json_encode($resposta);
    exit();
}
//echo($id);

$resposta = array();
$verificador = 0;



if (!is_numeric($id)) { 
    $resposta['cod'] = 3;
    $resposta['msg'] = "id deve ser um número";
    $verificador = 1;
}

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    //    $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
    if ($verificador == 0) {
        $funcionario = new Funcionarios();
        $funcionario->setid($id);
        $funcionario->setnome($nome);
        $funcionario->setemail($email);
        $funcionario->setcpf($cpf);
        $funcionario->setcargo($cargo);
        $funcionario->setsalario($salario);
        $funcionario->setdata_contratacao($data_contratacao);
        $funcionario->setdepartamento($departamento);
        $funcionario->setfuncionario_supervisor($funcionario_supervisor);
        $resultado = $funcionario->update();


        if ($resultado == true) {
            header("HTTP/1.1 201 Created");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Auteracao feita com sucesso!!!";
            //$resposta['Token'] = $tokenNovo;

        } else {
            header("HTTP/1.1 500 Internal Server Error");
            $resposta['cod'] = 5;
            $resposta['msg'] = "ERRO ao atualizar funcionário";
        }
    }

    echo json_encode($resposta);
} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);
}

?>