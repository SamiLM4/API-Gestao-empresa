<?php
require_once "modelo/conexoes/Banco.php";

class Perfil {
    private $id;
    private $id_funcionario;
    private $idade;
    private $endereco;
    private $telefone;
    private $genero;
    private $estado_civil;

    public function jsonSerialize() {
        $objetoResposta = new stdClass();
        $objetoResposta->id = $this->id;
        $objetoResposta->id_funcionario = $this->id_funcionario;
        $objetoResposta->idade = $this->idade;
        $objetoResposta->endereco = $this->endereco;
        $objetoResposta->telefone = $this->telefone;
        $objetoResposta->genero = $this->genero;
        $objetoResposta->estado_civil = $this->estado_civil;
        return $objetoResposta;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getId_funcionario() { return $this->id_funcionario; }
    public function setId_funcionario($id_funcionario) { $this->id_funcionario = $id_funcionario; }

    public function getIdade() { return $this->idade; }
    public function setIdade($idade) { $this->idade = $idade; }

    public function getEndereco() { return $this->endereco; }
    public function setEndereco($endereco) { $this->endereco = $endereco; }

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }

    public function getGenero() { return $this->genero; }
    public function setGenero($genero) { $this->genero = $genero; }

    public function getEstado_civil() { return $this->estado_civil; }
    public function setEstado_civil($estado_civil) { $this->estado_civil = $estado_civil; }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'id_funcionario' => $this->getId_funcionario(),
            'idade' => $this->getIdade(),
            'endereco' => $this->getEndereco(),
            'telefone' => $this->getTelefone(),
            'genero' => $this->getGenero(),
            'estado_civil' => $this->getEstado_civil(),
        ];
    }

    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO perfis (id_funcionario, idade, endereco, telefone, genero, estado_civil) VALUES (?, ?, ?, ?, ?, ?);");
        if ($stmt === false) return false;

        $stmt->bind_param("iissss", $this->id_funcionario, $this->idade, $this->endereco, $this->telefone, $this->genero, $this->estado_civil);
        return $stmt->execute();
    }

    public function read() {
        $meuBanco = new Banco();
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM perfis");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) return null;

        $vetorPerfis = array();
        while ($tupla = $resultado->fetch_object()) {
            $perfil = new Perfil();
            $perfil->setId($tupla->id);
            $perfil->setId_funcionario($tupla->id_funcionario);
            $perfil->setIdade($tupla->idade);
            $perfil->setEndereco($tupla->endereco);
            $perfil->setTelefone($tupla->telefone);
            $perfil->setGenero($tupla->genero);
            $perfil->setEstado_civil($tupla->estado_civil);
            $vetorPerfis[] = $perfil;
        }

        return $vetorPerfis;
    }

    public function readID() {
        $meuBanco = new Banco();
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM perfis WHERE id = ?");
        $stm->bind_param("i", $this->id);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) return null;

        $linha = $resultado->fetch_object();
        $perfil = new Perfil();
        $perfil->setId($linha->id);
        $perfil->setId_funcionario($linha->id_funcionario);
        $perfil->setIdade($linha->idade);
        $perfil->setEndereco($linha->endereco);
        $perfil->setTelefone($linha->telefone);
        $perfil->setGenero($linha->genero);
        $perfil->setEstado_civil($linha->estado_civil);

        return $perfil;
    }

    public function update() {
        $meuBanco = new Banco();
        $sql = "UPDATE perfis SET id_funcionario = ?, idade = ?, endereco = ?, telefone = ?, genero = ?, estado_civil = ? WHERE id = ?";
        $stm = $meuBanco->getConexao()->prepare($sql);
        if ($stm === false) return false;

        $stm->bind_param("iissssi", $this->id_funcionario, $this->idade, $this->endereco, $this->telefone, $this->genero, $this->estado_civil, $this->id);
        return $stm->execute();
    }

    public function delete() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        $SQL = "DELETE FROM perfis WHERE id = ?";
        if ($prepareSQL = $conexao->prepare($SQL)) {
            $prepareSQL->bind_param("i", $this->id);
            if ($prepareSQL->execute()) {
                $prepareSQL->close();
                return true;
            } else {
                echo "Erro na execução da consulta: " . $prepareSQL->error;
                return false;
            }
        } else {
            echo "Erro na preparação da consulta: " . $conexao->error;
            return false;
        }
    }
}
?>
