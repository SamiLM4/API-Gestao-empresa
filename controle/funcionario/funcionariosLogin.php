<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Firebase\JWT\MeuTokenJWT;

require_once "modelo/funcionario/funcionarios.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);



if (!isset($obj->email) || !isset($obj->cpf)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incorretos ou incompletos. Por favor, forneça email e cpf válido."
    ]);
    exit();
}

$email = $obj->email;
$cpf = $obj->cpf;

$email = strip_tags($email);
$cpf = strip_tags($cpf);

if ($email == '' || ($cpf == '')) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "Dados inválidos";
    exit();
}


$funcionario = new Funcionarios();
$funcionario->setemail($email);
$funcionario->setcpf($cpf);

if ($funcionario->login()) {

    $tokenJWT = new MeuTokenJWT();
    $objectClaimsToken = new stdClass();
    $objectClaimsToken->id = $funcionario->getid();
    $objectClaimsToken->nome = $funcionario->getnome();
    $objectClaimsToken->email = $funcionario->getemail();
    $objectClaimsToken->id_curso = $funcionario->getcpf();
    $objectClaimsToken->id_curso = $funcionario->getcargo();
    $objectClaimsToken->id_curso = $funcionario->getsalario();
    $objectClaimsToken->id_curso = $funcionario->getdata_contratacao();
    $objectClaimsToken->id_curso = $funcionario->getdepartamento();
    $objectClaimsToken->id_curso = $funcionario->getfuncionario_supervisor();
    $novoToken = $tokenJWT->gerarToken($objectClaimsToken);

    echo json_encode([
        "cod" => 200,
        "msg" => "Login realizado com sucesso!!!",
        "Aluno" => [
            "id" => $funcionario->getid(),
            "nome" => $funcionario->getnome(),
            "email" => $funcionario->getemail(),
            "cpf" => $funcionario->getcpf(),
            "cargo" => $funcionario->getcargo(),
            "salario" => $funcionario->getsalario(),
            "data da contratação" => $funcionario->getdata_contratacao(),
            "departamento" => $funcionario->getdepartamento(),
            "id do supervisor" => $funcionario->getfuncionario_supervisor()
        ],
        "token" => $novoToken
    ]);
} else {
    echo json_encode([
        "cod" => 401,
        "msg" => "ERRO: Login inválido. Verifique suas credenciais."
    ]);
}
?>