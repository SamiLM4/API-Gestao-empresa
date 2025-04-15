<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/tokenJWT/MeuTokenJWT.php";

$headers = getallheaders();

$autorization = $headers['Authorization'];
$meutoken = new MeuTokenJWT();

$body = json_decode(file_get_contents("php://input"), true);
$nomeDepartamento = $body['departamento'] ?? '';

if ($meutoken->validarToken($autorization) == true) {
    $payloadRecuperado = $meutoken->getPayload();

    $host = '127.0.0.1';
    $db = 'teste_estagio_api_php';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        $stmt = $pdo->prepare("
            SELECT 
                AVG(IFNULL(p.idade, 0)) AS media_idade,
                AVG(f.salario) AS media_salario,
                COUNT(f.id) AS total_funcionarios
            FROM 
                funcionarios f
            LEFT JOIN 
                perfis p ON f.id = p.id_funcionario
            JOIN 
                departamentos d ON f.departamento = d.id
            WHERE 
                d.nome_departamento = ?

        ");

        $stmt->execute([$nomeDepartamento]);
        $resultado = $stmt->fetch();

        if ($resultado && $resultado['media_idade'] !== null) {
            header("HTTP/1.1 200 OK");
            $resposta = [
                "msg" => "Dados Departamento: $nomeDepartamento",
                "media_idade" => round($resultado['media_idade'], 2),
                "media_salario" => number_format($resultado['media_salario'], 2, ',', '.'),
                "total_funcionarios" => $resultado['total_funcionarios']
            ];
        } else {
            $resposta['resultado'] = "Nenhum funcionário encontrado para o departamento $nomeDepartamento.";
        }

    } catch (PDOException $e) {
        $resposta['resultado'] = "Erro na conexão ou na consulta: " . $e->getMessage();
    }

} else {
    header("HTTP/1.1 401 Unauthorized");
    $resposta['resultado'] = ([
        "cod" => 401,
        "msg" => "Token inválido!"
    ]);
}

echo json_encode($resposta);
?>