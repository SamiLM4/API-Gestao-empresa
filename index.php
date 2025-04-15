<?php
require_once ("modelo/conexoes/Router.php");

$roteador = new Router();

/////////////////////////////////////////////////////////////////////////////////////////////////// FUNCIONARIOS 

$roteador->get("/funcionarios", function () {
    require_once ("controle/funcionario/funcionariosRead.php");
});

$roteador->get("/funcionarios/(\d+)", function ($id_curso) {
    require_once ("controle/funcionario/funcionariosReadID.php");
});

$roteador->post("/funcionarios/login", function () {
    require_once ("controle/funcionario/funcionariosLogin.php");
});

$roteador->post("/funcionarios", function () {
    require_once ("controle/funcionario/funcionariosCadastrar.php");
});

$roteador->put("/funcionarios", function () {
    require_once ("controle/funcionario/funcionariosUpdate.php");
});

$roteador->delete("/funcionarios/(\d+)", function ($id_curso) {
    require_once ("controle/funcionario/funcionariosDelete.php");
});

/////////////////////////////////////////////////////////////////////////////////////////////////// FUNCIONARIOS LOGIN

$roteador->post("/funcionarios/login", function () {
    require_once ("controle/funcionario/funcionariosLogin.php");
});

/////////////////////////////////////////////////////////////////////////////////////////////////// DEPARTAMENTOS

$roteador->get("/departamentos", function () {
    require_once ("controle/departamento/departamentosRead.php");
});

$roteador->get("/departamentos/(\d+)", function ($id_a) {
    require_once ("controle/departamento/departamentosReadID.php");
});

$roteador->post("/departamentos", function () {
    require_once ("controle/departamento/departamentosCadastrar.php");
});

$roteador->put("/departamentos", function () {
    require_once ("controle/departamento/departamentosUpdate.php");
});

$roteador->delete("/departamentos/(\d+)", function ($id_a) {
    require_once ("controle/departamento/departamentosDelete.php");
});

/////////////////////////////////////////////////////////////////////////////////////////////////// PERFIS

$roteador->get("/perfis", function () {
    require_once ("controle/perfil/perfisRead.php");
});

$roteador->get("/perfis/(\d+)", function ($id_a) {
    require_once ("controle/perfil/perfisReadID.php");
});

$roteador->post("/perfis", function () {
    require_once ("controle/perfil/perfisCadastrar.php");
});

$roteador->put("/perfis", function () {
    require_once ("controle/perfil/perfisUpdate.php");
});

$roteador->delete("/perfis/(\d+)", function ($id_a) {
    require_once ("controle/perfil/perfisDelete.php");
});

/////////////////////////////////////////////////////////////////////////////////////////////////// HISTORICO CARGO


$roteador->get("/historicoCargos", function () {
    require_once ("controle/historicoCargo/historicoCargosRead.php");
});


$roteador->get("/historicoCargos/(\d+)", function ($id_a) {
    require_once ("controle/historicoCargo/historicoCargosReadID.php");
});


$roteador->post("/historicoCargos", function () {
    require_once ("controle/historicoCargo/historicoCargosCadastrar.php");
});



$roteador->put("/historicoCargos", function () {
    require_once ("controle/historicoCargo/historicoCargosUpdate.php");
});


$roteador->delete("/historicoCargos/(\d+)", function ($id_a) {
    require_once ("controle/historicoCargo/historicoCargosDelete.php");
});
///////////////////////////////////////////////////////////////////////////////////////// ESTATISTICAS E RELATÓRIOS

$roteador->post("/dados", function () {
//    echo("Chegou aqui");
    require_once ("controle/recuperarDados/dados.php");
});

// Executa o roteador para lidar com as requisições
$roteador->run();
?>