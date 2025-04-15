<?php
require_once "modelo/funcionario/funcionarios.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->nome) || !isset($obj->email) || !isset($obj->cpf) || !isset($obj->cargo) || !isset($obj->salario) || !isset($obj->data_contratacao)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos."
    ]);
    exit();
}

$nome = $obj->nome;
$email = $obj->email;
$cpf = $obj->cpf;
$cargo = $obj->cargo;
$salario = $obj->salario;
$data_contratacao = $obj->data_contratacao;
$departamento = $obj->departamento;
$funcionario_supervisor = $obj->funcionario_supervisor;

//middware
$nome = strip_tags($nome);
$email = strip_tags($email);
$cpf = strip_tags($cpf);
$cargo = strip_tags($cargo);
$salario = strip_tags($salario);
$data_contratacao = strip_tags($data_contratacao);
$departamento = strip_tags($departamento);
$funcionario_supervisor = strip_tags($funcionario_supervisor);


if ($nome == '' || !isset($nome)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($email == '' || !isset($email)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "email nao pode ser vazio";
    $verificador = 1;
} else if ($cpf == '' || !isset($cpf)){
    $resposta['cod'] = 3;
    $resposta['msg'] = "CPF nao pode ser vazio";
    $verificador = 1;
} else if ($cargo == '' || !isset($cargo)){
    $resposta['cod'] = 3;
    $resposta['msg'] = "cargo nao pode ser vazio";
    $verificador = 1;
}   else if ($data_contratacao == '' || !isset($data_contratacao)){
    $resposta['cod'] = 3;
    $resposta['msg'] = "data de contratação nao pode ser vazio";
    exit();
}
//
$funcionario = new Funcionarios();
$funcionario->setnome($nome);
$funcionario->setemail($email);
$funcionario->setcpf($cpf);
$funcionario->setcargo($cargo);
$funcionario->setsalario($salario);
$funcionario->setdata_contratacao($data_contratacao);
$funcionario->setdepartamento($departamento);
$funcionario->setfuncionario_supervisor($funcionario_supervisor);


if ($funcionario->cadastrar()) {
    echo json_encode([
        "cod" => 201,
        "msg" => "Funcionario cadastrado com sucesso!",
        "Funcionario" => $funcionario
    ]);
} else {
    echo json_encode([
        "cod" => 556,
        "msg" => "Erro ao cadastrar Funcionario",
        "Funcionario" => $funcionario
    ]);
}
?>