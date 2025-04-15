<?php
require_once "modelo/conexoes/Banco.php";

class Funcionarios {
        private $id;
        private $nome;
        private $email;
        private $cpf;
        private $cargo;
        private $salario;
        private $data_contratacao;
        private $departamento;
        private $funcionario_supervisor;

        

        
        public function jsonSerialize()
        {
            
            $objetoResposta = new stdClass();
            
            $objetoResposta->id = $this->id;
            $objetoResposta->nome = $this->nome;
            $objetoResposta->email = $this->email;
            $objetoResposta->cpf = $this->cpf;
            $objetoResposta->cargo = $this->cargo;
            $objetoResposta->salario = $this->salario;
            $objetoResposta->data_contratacao = $this->data_contratacao;
            $objetoResposta->departamento = $this->departamento;
            $objetoResposta->funcionario_supervisor = $this->funcionario_supervisor;

            
            return $objetoResposta;
        }

        public function getid() {
            return $this->id;
        }

        public function setid($id) {
            $this->id = $id;
        }
    
        public function getnome() {
            return $this->nome;
        }

        public function setnome($nome) {
            $this->nome = $nome;
        }
    
        public function getemail() {
            return $this->email;
        }

        public function setemail($email) {
            $this->email = $email;
        }
    
        public function getcpf() {
            return $this->cpf;
        }

        public function setcpf($cpf) {
            $this->cpf = $cpf;
        }
    
        public function getcargo() {
            return $this->cargo;
        }

        public function setcargo($cargo) {
            $this->cargo = $cargo;
        }
    
        public function getsalario() {
            return $this->salario;
        }

        public function setsalario($salario) {
            $this->salario = $salario;
        }
    

        public function getdata_contratacao() {
            return $this->data_contratacao;
        }

        public function setdata_contratacao($data_contratacao) {
            $this->data_contratacao = $data_contratacao;
        }
    

        public function getdepartamento() {
            return $this->departamento;
        }

        public function setdepartamento($departamento) {
            $this->departamento = $departamento;
        }
    

        public function getfuncionario_supervisor() {
            return $this->funcionario_supervisor;
        }

        public function setfuncionario_supervisor($funcionario_supervisor) {
            $this->funcionario_supervisor = $funcionario_supervisor;
        }

        
        public function toArray() {
            return [
                'id' => $this->getid(),
                'nome' => $this->getnome(),
                'email' => $this->getemail(),
                'cpf' => $this->getcpf(),
                'cargo' => $this->getcargo(),
                'salario' => $this->getsalario(),
                'data_contratacao' => $this->getdata_contratacao(),
                'departamento' => $this->getdepartamento(),
                'funcionario_supervisor' => $this->getfuncionario_supervisor()
            ];
        }
        

        public function cadastrar() {
            $meuBanco = new Banco();
            $conexao = $meuBanco->getConexao();

            $stmt = $conexao->prepare("INSERT INTO funcionarios (nome, email, cpf, cargo, salario, data_contratacao, departamento, funcionario_supervisor)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
            if ($stmt === false) {
                return false;
            }

            $funcionario_supervisor = is_numeric($this->funcionario_supervisor) ? $this->funcionario_supervisor : null;
            $departamento = is_numeric($this->departamento) ? $this->departamento : null;

            $stmt->bind_param("ssssisii", $this->nome, $this->email, $this->cpf, $this->cargo, $this->salario, $this->data_contratacao, $departamento, $funcionario_supervisor);

            return $stmt->execute();
        }

        public function read() {
            
            $meuBanco = new Banco();
            
            $stm = $meuBanco->getConexao()->prepare("SELECT * FROM funcionarios");
            $stm->execute();
            $resultado = $stm->get_result();

            if ($resultado->num_rows === 0) {
                return null; 
            }

            
            $vetorCursos = array();
            
            while ($tupla = $resultado->fetch_object()) {
                
                $funcionariodados = new Funcionarios();
                
                $funcionariodados->setid($tupla->id);
                $funcionariodados->setnome($tupla->nome);
                $funcionariodados->setemail($tupla->email);
                $funcionariodados->setcpf($tupla->cpf);
                $funcionariodados->setcargo($tupla->cargo);
                $funcionariodados->setsalario($tupla->salario);
                $funcionariodados->setdata_contratacao($tupla->data_contratacao);
                $funcionariodados->setdepartamento($tupla->departamento);
                $funcionariodados->setfuncionario_supervisor($tupla->funcionario_supervisor);

                $vetorCursos[] = $funcionariodados;
            }
            
            return $vetorCursos;
        }

        public function readID() {
            $meuBanco = new Banco();
            $id = $this->id;

            $stm = $meuBanco->getConexao()->prepare("SELECT * FROM funcionarios WHERE id = ?");
            $stm->bind_param("i", $this->id);
            $stm->execute();
            $resultado = $stm->get_result();

            if ($resultado->num_rows === 0) {
                return null; 
            }

            $linha = $resultado->fetch_object();
            $funcionariodados = new Funcionarios(); 

            
            $funcionariodados->setid($linha->id);
            $funcionariodados->setnome($linha->nome);
            $funcionariodados->setemail($linha->email);
            $funcionariodados->setcpf($linha->cpf);
            $funcionariodados->setcargo($linha->cargo);
            $funcionariodados->setsalario($linha->salario);
            $funcionariodados->setdata_contratacao($linha->data_contratacao);
            $funcionariodados->setdepartamento($linha->departamento);
            $funcionariodados->setfuncionario_supervisor($linha->funcionario_supervisor);

            return $funcionariodados; 
        }

        public function update() {
            $meuBanco = new Banco();
            $sql = "UPDATE funcionarios SET nome = ?, email = ?, cpf = ?, cargo = ?, salario = ?, data_contratacao = ?, departamento = ?, funcionario_supervisor = ? WHERE id = ?";
            $stm = $meuBanco->getConexao()->prepare($sql);

            if ($stm === false) {
                
                return false;
            }

            $funcionario_supervisor = is_numeric($this->funcionario_supervisor) ? $this->funcionario_supervisor : null;
            $departamento = is_numeric($this->departamento) ? $this->departamento : null;

            
            $stm->bind_param("ssssisiii", $this->nome, $this->email, $this->cpf, $this->cargo, $this->salario, $this->data_contratacao, $departamento, $funcionario_supervisor, $this->id);
            
            $resultado = $stm->execute();

            return $resultado;
        }

        public function delete() {
            $meuBanco= new Banco();
            $conexao = $meuBanco->getConexao();

            
            if ($conexao->connect_error) {
                die("Falha na conexão: " . $conexao->connect_error);
            }

            
            $SQL = "DELETE FROM funcionarios WHERE id = ?;"; 

            
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

        public function login() {
            $meuBanco = new Banco();
            $conexao = $meuBanco->getConexao();
            $SQL = "SELECT * FROM Funcionarios WHERE email = ? AND cpf = ?;";
            $prepareSQL = $conexao->prepare($SQL);
            $prepareSQL->bind_param("ss", $this->email, $this->cpf);
            $prepareSQL->execute();
            $matrizTupla = $prepareSQL->get_result();
        
            if ($tupla = $matrizTupla->fetch_object()) {
                $this->setid($tupla->id);
                $this->setnome($tupla->nome);
                $this->setemail($tupla->email);
                $this->setcpf($tupla->cpf);
                $this->setcargo($tupla->cargo);
                $this->setsalario($tupla->salario);
                $this->setdata_contratacao($tupla->data_contratacao);
                $this->setdepartamento($tupla->departamento);
                $this->setfuncionario_supervisor($tupla->funcionario_supervisor);
                return true;  
            }
        
            return false;  
        }

    }
?>
