<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/funcionario/funcionarios.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();
$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    if ($metodo == "GET") {
        $id = $vetor[2];

        $id = strip_tags($id);
        
        if ($id == '' || !isset($id)) {
            $resposta['cod'] = 3;
            $resposta['msg'] = "Dados inválidos";
            exit();
        }

        $funcionario = new Funcionarios();
        $funcionario->setid($id);
        $funcionarioSelecionado = $funcionario->readID();
        //        $tokenNovo=$meutoken->gerarToken($payloadRecuperado);

        if ($funcionarioSelecionado) {

            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "funcionario encontrado",
                "funcionario" => [
                    "id" => $funcionarioSelecionado->getid(),
                    "nome" => $funcionarioSelecionado->getnome(),
                    "email" => $funcionarioSelecionado->getemail(),
                    "cpf" => $funcionarioSelecionado->getcpf(),
                    "cargo" => $funcionarioSelecionado->getcargo(),
                    "salario" => $funcionarioSelecionado->getsalario(),
                    "data da contratacao" => $funcionarioSelecionado->getdata_contratacao(),
                    "departamento" => $funcionarioSelecionado->getdepartamento(),
                    "supervisor" => $funcionarioSelecionado->getfuncionario_supervisor()

                ],
                //                "token" => $tokenNovo
            ]);





        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "cod" => 404,
                "msg" => "Funcionário não encontrado"
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