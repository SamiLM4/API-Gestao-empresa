<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/funcionario/funcionarios.php";
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();
$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();



if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();
    



    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === "DELETE") {
        
        $vetor = explode("/", $_SERVER['REQUEST_URI']);
        $id = $vetor[2];

        $id = strip_tags($id);


        if ($id == '' || !isset($id)) {
            $resposta['cod'] = 3;
            $resposta['msg'] = "id nao pode ser vazio";
            exit();
        }

        $funcionario = new Funcionarios();
        $funcionario->setid($id);

        
        if ($funcionario->delete()) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "mensagem" => "funcionario excluído com sucesso.",
                
            ]);
            exit(); 
        } else {
            header("HTTP/1.1 500 Internal Server Error"); 
            echo json_encode([
                "cod" => 500,
                "msg" => "Erro ao excluir funcionario."
            ]);
            exit();
        }
    } else {
        
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: DELETE");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método HTTP não permitido. Apenas o método DELETE é suportado para exclusão de funcionario."
        ]);
        exit();
    }
} else {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);

}
?>