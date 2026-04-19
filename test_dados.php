<?php
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
            d.nome_departamento = 'inexistente'
    ");

    $stmt->execute();
    $resultado = $stmt->fetch();
    var_dump($resultado);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
