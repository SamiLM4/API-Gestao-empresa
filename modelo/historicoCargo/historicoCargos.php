<?php
require_once "modelo/conexoes/Banco.php";

class HistoricoCargo {
    private $id;
    private $id_funcionario;
    private $cargos_anteriores;
    private $cargo_atual;
    private $data_alteracao;

    public function jsonSerialize() {
        $objetoResposta = new stdClass();
        $objetoResposta->id = $this->id;
        $objetoResposta->id_funcionario = $this->id_funcionario;
        $objetoResposta->cargos_anteriores = $this->cargos_anteriores;
        $objetoResposta->cargo_atual = $this->cargo_atual;
        $objetoResposta->data_alteracao = $this->data_alteracao;
        return $objetoResposta;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getId_funcionario() { return $this->id_funcionario; }
    public function setId_funcionario($id_funcionario) { $this->id_funcionario = $id_funcionario; }

    public function getCargos_anteriores() { return $this->cargos_anteriores; }
    public function setCargos_anteriores($cargos_anteriores) { $this->cargos_anteriores = $cargos_anteriores; }

    public function getCargo_atual() { return $this->cargo_atual; }
    public function setCargo_atual($cargo_atual) { $this->cargo_atual = $cargo_atual; }

    public function getData_alteracao() { return $this->data_alteracao; }
    public function setData_alteracao($data_alteracao) { $this->data_alteracao = $data_alteracao; }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'id_funcionario' => $this->getId_funcionario(),
            'cargos_anteriores' => $this->getCargos_anteriores(),
            'cargo_atual' => $this->getCargo_atual(),
            'data_alteracao' => $this->getData_alteracao(),
        ];
    }

    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO historico_cargos (id_funcionario, cargos_anteriores, cargo_atual, data_alteracao) VALUES (?, ?, ?, ?);");
        if ($stmt === false) return false;

        $stmt->bind_param("isss", $this->id_funcionario, $this->cargos_anteriores, $this->cargo_atual, $this->data_alteracao);
        return $stmt->execute();
    }

    public function read() {
        $meuBanco = new Banco();
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM historico_cargos");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) return null;

        $vetorHistoricos = array();
        while ($tupla = $resultado->fetch_object()) {
            $hist = new HistoricoCargo();
            $hist->setId($tupla->id);
            $hist->setId_funcionario($tupla->id_funcionario);
            $hist->setCargos_anteriores($tupla->cargos_anteriores);
            $hist->setCargo_atual($tupla->cargo_atual);
            $hist->setData_alteracao($tupla->data_alteracao);
            $vetorHistoricos[] = $hist;
        }

        return $vetorHistoricos;
    }

    public function readID() {
        $meuBanco = new Banco();
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM historico_cargos WHERE id = ?");
        $stm->bind_param("i", $this->id);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) return null;

        $linha = $resultado->fetch_object();
        $hist = new HistoricoCargo();
        $hist->setId($linha->id);
        $hist->setId_funcionario($linha->id_funcionario);
        $hist->setCargos_anteriores($linha->cargos_anteriores);
        $hist->setCargo_atual($linha->cargo_atual);
        $hist->setData_alteracao($linha->data_alteracao);

        return $hist;
    }

    public function update() {
        $meuBanco = new Banco();
        $sql = "UPDATE historico_cargos SET id_funcionario = ?, cargos_anteriores = ?, cargo_atual = ?, data_alteracao = ? WHERE id = ?";
        $stm = $meuBanco->getConexao()->prepare($sql);
        if ($stm === false) return false;

        $stm->bind_param("isssi", $this->id_funcionario, $this->cargos_anteriores, $this->cargo_atual, $this->data_alteracao, $this->id);
        return $stm->execute();
    }

    public function delete() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $SQL = "DELETE FROM historico_cargos WHERE id = ?";
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
