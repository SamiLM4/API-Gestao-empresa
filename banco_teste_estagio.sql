create database teste_estagio_api_php;
use teste_estagio_api_php;

CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_departamento VARCHAR(100) NOT NULL,
    orcamento DECIMAL(10,2),
    localizacao text NOT NULL,
    data_criacao date NOT NULL
);

CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf varchar(50) not null,
    cargo VARCHAR(255) NOT NULL,
    salario int,
    data_contratacao date not null,
    
    departamento int null,
    FOREIGN KEY (departamento) REFERENCES departamentos(id),
    
    funcionario_supervisor int NULL,
    FOREIGN KEY (funcionario_supervisor) REFERENCES funcionarios(id)
);

CREATE TABLE perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_funcionario int NOT NULL,
    idade int NOT NULL,
    endereco text NOT NULL,
    telefone varchar(100) not null, 
    genero varchar(100),
    estado_civil varchar(50),
    FOREIGN KEY (id_funcionario) REFERENCES funcionarios(id)
);

CREATE TABLE historico_cargos (
	id int auto_increment primary key,
    id_funcionario int NOT NULL,
    cargos_anteriores text,
    cargo_atual text, 
    data_alteracao date not null,
    
	FOREIGN KEY historico_cargos(id_funcionario) REFERENCES funcionarios(id)
);

insert into funcionarios (nome, email, cpf, cargo, salario, data_contratacao) values ('ADM', 'adm@example.com', '1234578901', 'ADM', 1000, '2025-04-15');

/*
	
select * from funcionarios;
select * from perfis;
select * from departamentos;
select * from historico_cargos;

# INSERTS

-- Inserindo departamento
INSERT INTO departamentos (nome_departamento, orcamento, localizacao, data_criacao)
VALUES ('Tecnologia', 500000.00, 'Rua João Numero 100', '2022-01-10');

-- Inserindo funcionário
INSERT INTO funcionarios (nome, email, cpf, cargo, salario, data_contratacao, departamento, funcionario_supervisor)
VALUES ('Ana Silva', 'ana.silva@email.com', 12345678900, 'Desenvolvedora', 7000, '2023-03-15', 1, NULL);

-- Inserindo perfil para esse funcionário
INSERT INTO perfis (id_funcionario, idade, endereco, telefone, genero, estado_civil)
VALUES (1, 28,'Rua das Flores, 123', 11987654321, 'Feminino', 'Solteira');

-- Inserindo histórico de cargo
INSERT INTO historico_cargos (id_funcionario, cargos_anteriores, cargo_atual, data_alteracao)
VALUES (1, 'Estagiária, Júnior', 'Desenvolvedora', '2024-04-01');

# UPDATES

-- Atualizando cargo e salário de Ana Silva
UPDATE funcionarios
SET cargo = 'Desenvolvedora Pleno', salario = 8500
WHERE id = 1;

UPDATE funcionarios SET nome = "Luiz", email = "murilo@example.com", cpf = "12345678901", cargo = "estagiario", salario = 1000, data_contratacao =  "2025-05-30", departamento = 1, funcionario_supervisor = null WHERE id = 9;

-- Registrando essa mudança no histórico
INSERT INTO historico_cargos (id_funcionario, cargos_anteriores, cargo_atual, data_alteracao)
VALUES (1, 'Estagiária, Júnior, Desenvolvedora', 'Desenvolvedora Pleno', CURDATE());

# DELETE 

-- Primeiro apaga dados das tabelas dependentes
DELETE FROM perfis where id_funcionario = 1;
DELETE FROM historico_cargos where id_funcionario = 1;

-- Depois apaga os funcionários
DELETE FROM funcionarios where id = 8;

-- Por fim, apaga os departamentos
DELETE FROM departamentos where id = 1;

##########
INSERT INTO perfis (id_funcionario, idade, endereco, telefone, genero, estado_civil) VALUES (1, 16, "rua 123", "123456", "normal", "solteiro");
INSERT INTO funcionarios (nome, email, cpf, cargo, salario, data_contratacao, departamento, funcionario_supervisor) VALUES ('Murilo', 'murilo@gmail.com', '123456789', 'estagiario', 1000, '2025-05-30', 1, null);
*/
#drop database teste_estagio_api_php;