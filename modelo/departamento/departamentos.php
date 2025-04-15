<?php
require_once "modelo/conexoes/Banco.php";

class Departamentos
{
    private $id;
    private $nome_departamento;
    private $orcamento;
    private $localizacao;
    private $data_criacao;






    public function jsonSerialize()
    {

        $objetoResposta = new stdClass();

        $objetoResposta->id = $this->id;
        $objetoResposta->nome_departamento = $this->nome_departamento;
        $objetoResposta->orcamento = $this->orcamento;
        $objetoResposta->localizacao = $this->localizacao;
        $objetoResposta->data_criacao = $this->data_criacao;


        return $objetoResposta;
    }



    public function getid()
    {
        return $this->id;
    }

    public function getnome_departamento()
    {
        return $this->nome_departamento;
    }

    public function getorcamento()
    {
        return $this->orcamento;
    }

    public function getlocalizacao()
    {
        return $this->localizacao;
    }

    public function getdata_criacao()
    {
        return $this->data_criacao;
    }


    public function setid($id)
    {
        $this->id = $id;
    }

    public function setnome_departamento($nome_departamento)
    {
        $this->nome_departamento = $nome_departamento;
    }

    public function setorcamento($orcamento)
    {
        $this->orcamento = $orcamento;
    }

    public function setlocalizacao($localizacao)
    {
        $this->localizacao = $localizacao;
    }

    public function setdata_criacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
    }




    public function toArray()
    {
        return [
            'id' => $this->getid(),
            'nome_departamento' => $this->getnome_departamento(),
            'orcamento' => $this->getorcamento(),
            'localizacao' => $this->getlocalizacao(),
            'data_criacao' => $this->getdata_criacao()

        ];
    }


    public function cadastrar()
    {

        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO departamentos (nome_departamento, orcamento, localizacao, data_criacao) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("sdss", $this->nome_departamento, $this->orcamento, $this->localizacao, $this->data_criacao);

        return $stmt->execute();
    }

    public function read()
    {

        $meuBanco = new Banco();

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM departamentos");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }


        $vetorDepartamentos = array();

        while ($tupla = $resultado->fetch_object()) {

            $departamentos = new Departamentos();

            $departamentos->setid($tupla->id);
            $departamentos->setnome_departamento($tupla->nome_departamento);
            $departamentos->setorcamento($tupla->orcamento);
            $departamentos->setlocalizacao($tupla->localizacao);
            $departamentos->setdata_criacao($tupla->data_criacao);

            $vetorDepartamentos[] = $departamentos;
        }

        return $vetorDepartamentos;
    }

    public function readID()
    {
        $meuBanco = new Banco();
        $id = $this->id;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM departamentos WHERE id = ?");
        $stm->bind_param("i", $id);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        $linha = $resultado->fetch_object();
        $Departamento = new Departamentos();


        $Departamento->setid($linha->id);
        $Departamento->setnome_departamento($linha->nome_departamento);
        $Departamento->setorcamento($linha->orcamento);
        $Departamento->setlocalizacao($linha->localizacao);
        $Departamento->setdata_criacao($linha->data_criacao);


        return $Departamento;
    }

    public function update()
    {
        $meuBanco = new Banco();
        $sql = "UPDATE departamentos SET nome_departamento=?,orcamento=?, localizacao=? WHERE  id = ? ";
        $stm = $meuBanco->getConexao()->prepare($sql);

        if ($stm === false) {

            return false;
        }


        $stm->bind_param("sdsi", $this->nome_departamento, $this->orcamento, $this->localizacao, $this->id);

        $resultado = $stm->execute();

        return $resultado;
    }

    public function delete()
    {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();


        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }


        $SQL = "DELETE FROM departamentos WHERE id = ?;";


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