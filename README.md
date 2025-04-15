# API de Gestão de Funcionários

## Descrição

O projeto consiste em um modelo básico de site de gestão de empresa, em que é possivel criar, deletar e atualizar: funcionarios, seus respectivos perfil's e historico de cargo, e departamentos.

## Instrução de como usar

### Instalação 

Para poder rodar o projeto em sua máquina: 

    https://www.apachefriends.org/pt_br/index.html -> Instale o XAMPP ou algum outro software que simule permite servidor web local.

    https://dev.mysql.com/downloads/workbench/ -> Instale o MySQL para o Banco de Dados.

    https://insomnia.rest/download -> Instale o Insomnia ou algum outro software para realizar as requisições HTTP (opcional).

    https://code.visualstudio.com/ -> Instale o Visual Studio Code para a visualização e edição do código (opcional).

### Primeiros Passos

Com tudo instalado, podemos começar os preparativos.

### Passo 1: Configurando o servidor

    Abra o XAMPP e ligue o "Apache" e "MySQL".

### observação 1.1
    De preferência à utilize a porta "localhost:8080" no Apache, para isso, clique em "Config" do Apache -> procure por "Listen 80" e altere para "Listen 8080".

### Passo 2: Criando o Banco de dados

    Para criar o banco de dados, você deve abrir o "MySQL Workbench" -> Clique em "+" ao lado de MySQL Connections.

#### 2.1

    De um nome para a sua ligação, caso deseja personalizar sua ligação, siga o passo 4. -> Clique em "OK".

Sua ligação ja deve estar pronta, agora você precia criar o seu banco de dados.

#### 2.2

    Abra o arquivo "banco_teste_estagio_cod" na raiz do projeto e cole na sua ligação.

#### observação 2.2.1

    Não deve haver nenhum outro banco de dados com o nome "teste_estagio_api_php", cajo exista, é necessários realizar algumas mudanças no código. 

#### observação 2.2.1

    Um funcionario inicial será criado para uma melhor experiência do usuario, caso não queira que ele seja criado automaticamente, basta apagar/comentar a linha 49: "insert into funcionarios (nome, email, cpf, cargo, salario, data_contratacao) values ('ADM', 'adm@example.com', '12345678901', 'administrador', 1000, '2025-04-15');"

#### 2.3

Clique no  ![alt text](image.png) , o banco de dados por fim será gerado.

### Passo 3: Funcionamento do site

Para ter contato com o site, basta abrir algum navegador como o chrome e digitar a seguinte URL:

http://localhost:8080/index.HTML

#### observação 3.1

    Caso esteja utilizando outra porta (XAMPP -> APACHE -> PORTS), utilize: http://localhost:(porta)/index.HTML

Aqui você ja deve estar vendo o visual do site.

### Passo 4: Login

Para fazer o primeiro Login, utilize "adm@example.com" como email e "12345678901" como CPF.

#### observação 4.1: Caso tenha tirado a linha 49 do SQL

    Para realizar o primeiro login, você precisa de um email e CPF, porém o banco está vazio.

##### observação 4.1.1: Inserir via SQL

    Para inserir um primeiro funcionario, você deve inserir uma linha de "insert" na tabela "funcionarios", sendo o departamento e o funcionario_supervidor *NULL*, use o seguinte comando como exemplo:

     insert into funcionarios (nome, email, cpf, cargo, salario, data_contratacao) values ('ADM', 'adm@example.com', '12345678901', 'administrador', 1000, '2025-04-15');

##### observação 4.1.2: Inserir via requisição HTTP (Insomnia)

    Para realizar as requisições HTTP via Insomnia ou algum outro software de preferência.

##### 4.1.2.1

    Abra o arquivo "requisicoes_HTTP"

##### 4.1.2.2

    Acesse: /funcionarios

##### 4.1.2.3

    Procure pelo arquivo com o Metodo "POST" -> /funcionarios 

##### 4.1.2.4: Informações para a execução do método HTTP

    Método: Post
    URL: http://localhost:8080/funcionarios

    Formato do JSON (body):

        {
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data_contratacao": "2025-04-15",
            "departamento": null,
            "funcionario_supervisor": null
        }
 
## Informações de funcionamento

### Endpoints e requisições

#### /funcionarios

##### Método post - Cadastro /funcionarios

    URL: (http://localhost:8080/funcionarios)

    JSON:   {
                "nome": "exemplo",
                "email": "exemplo@example.com",
                "cpf": "12345678901",
                "cargo": "estagiario",
                "salario": 1000,
                "data_contratacao": "2025-05-30",
                "departamento": null,
                "funcionario_supervisor": null
            }
    
    Sem necessidade de TOKEN 

###### Método post - RETORNO /funcionarios:

    cod: 200

    {
        "cod": 201,
        "msg": "Funcionario cadastrado com sucesso!",
        "Funcionario": {}
    }

##### Método post - Login /funcionarios

    URL: (http://localhost:8080/funcionarios/login)

    JSON:   {
                "email": "exemplo@example.com",
                "cpf": "12345678901"
            }
    
    Sem necessidade de TOKEN 

###### RETORNO /funcionarios:

    cod: 200

    {
        "cod": 200,
        "msg": "Login realizado com sucesso!!!",
        "Aluno": {
            "id": 1,
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data da contratação": "2025-04-15",
            "departamento": null,
            "id do supervisor": null
        },
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsInN1YiI6ImFjZXNzb19zaXN0ZW1hIiwiaWF0IjoxNzQ0NzQzNTYzLCJleHAiOjE3NDczMzU1NjMsIm5iZiI6MTc0NDc0MzU2MywianRpIjoiZGJjMDdjYzEyYWUxNDNiZDlkNmMzOWY2NDgzNDk4ZTMiLCJpZCI6MSwibm9tZSI6IkFETSIsImVtYWlsIjoiYWRtQGV4YW1wbGUuY29tIn0.ar3gKwEx1x7EbH3eq6c7E2xP3GZ0Vm9z5eu7srGPtuA"
    }

##### Método Get - Read ALL /funcionarios

    URL: (http://localhost:8080/funcionarios)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /funcionarios:

    cod: 200

    [
        {
            "id": 1,
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data_contratacao": "2025-04-15",
            "departamento": null,
            "funcionario_supervisor": null
        }
    ]

##### Método Get - Read By ID /funcionarios

    URL: (http://localhost:8080/funcionarios/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /funcionarios:

    cod: 200

    {
        "cod": 200,
        "msg": "funcionario encontrado",
        "funcionario": {
            "id": 1,
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data da contratacao": "2025-04-15",
            "departamento": null,
            "supervisor": null
        }
    }

###### Método PUT - UPDATE /funcionarios

    URL: (http://localhost:8080/funcionarios)

    JSON: 

        {
            "id": 1,
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data da contratacao": "2025-04-15",
            "departamento": null,
            "supervisor": null
        }

    Headers['Authorization'] Bearer (token) 

###### RETORNO /funcionarios:

    cod: 200

    {
        "cod": 200,
        "msg": "funcionario encontrado",
        "funcionario": {
            "id": 1,
            "nome": "ADM",
            "email": "adm@example.com",
            "cpf": "12345678901",
            "cargo": "administrador",
            "salario": 1000,
            "data da contratacao": "2025-04-15",
            "departamento": null,
            "supervisor": null
        }
    }
            
##### Método DELETE - DELETE /funcionarios

    URL: (http://localhost:8080/funcionarios/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /funcionarios:

    cod: 200

    {
        "mensagem": "funcionario excluído com sucesso."
    }

#### /departamentos

##### Método post - Cadastro /departamentos

    URL: (http://localhost:8080/departamentos)

    JSON: 
            {
                "nome_departamento": "rh",
                "orcamento": 1000,
                "localizacao": "Rua 123 n 123",
                "data_criacao": "2025-05-30"
            }
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /departamentos:

    cod: 200

    {
        "cod": 201,
        "msg": "Departamento cadastrado com sucesso.",
        "Departamento": {}
    }

##### Método Get - Read ALL /departamentos

    URL: (http://localhost:8080/departamentos)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /departamentos:

    cod: 200

    [
        {
            "id": 2,
            "nome_departamento": "rh",
            "orcamento": "1000.00",
            "localizacao": "Rua 123 n 123",
            "data_criacao": "2025-05-30"
        }
    ]

##### Método Get - Read By ID /departamentos

    URL: (http://localhost:8080/departamentos/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /departamentos:

    cod: 200

    {
        "cod": 200,
        "msg": "departamento encontrado",
        "departamento": {
            "id": 1,
            "nome_departamento": "rh",
            "orcamento": "1000.00",
            "localizacao": "Rua 123 n 123",
            "data_criacao": "2025-05-30"
        }
    }

###### Método PUT - UPDATE /departamentos

    URL: (http://localhost:8080/departamentos)

    JSON: 

    {
        "id": 1,
        "nome_departamento": "RH",
        "orcamento": 1000,
        "localizacao": "Rua 123 n 123",
        "data_criacao": "2025-05-30"
    }

    Headers['Authorization'] Bearer (token) 

###### RETORNO /departamentos:

    cod: 200

    {
        "cod": 4,
        "msg": "Atualizado com sucesso!!!",
        "Departamento": {}
    }
                
##### Método DELETE - DELETE /departamentos

    URL: (http://localhost:8080/departamentos/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO:

    cod: 204

#### /perfis

##### Método post - Cadastro /perfis

    URL: (http://localhost:8080/perfis)

    JSON: 
            {
                "id_funcionario": 3,
                "idade": 30,
                "endereco": "Rua Exemplo, 123",
                "telefone": 1234567890.00,
                "genero": "Masculino",
                "estado_civil": "Solteiro"
            }
                
    Headers['Authorization'] Bearer (token) 

###### RETORNO /perfis:

    cod: 200

    {
        "cod": 201,
        "msg": "Perfil cadastrado com sucesso!",
        "perfil": {}
    }

##### Método Get - Read ALL /perfis

    URL: (http://localhost:8080/perfis)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /perfis:

    cod: 200

    {
        "cod": 200,
        "msg": "Perfis encontrados.",
        "perfis": [
            {
                "id": 2,
                "id_funcionario": 1,
                "idade": 30,
                "endereco": "Rua Exemplo, 123",
                "telefone": "1234567890",
                "genero": "Masculino",
                "estado_civil": "Solteiro"
            }
        ]
    }

##### Método Get - Read By ID /perfis

    URL: (http://localhost:8080/perfis/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /perfis:

    cod: 200

    {
        "cod": 200,
        "msg": "Perfil encontrado",
        "perfil": {
            "id": 2,
            "id_funcionario": 1,
            "idade": 30,
            "endereco": "Rua Exemplo, 123",
            "telefone": "1234567890",
            "genero": "Masculino",
            "estado_civil": "Solteiro"
        }
    }

###### Método PUT - UPDATE /perfis

    URL: (http://localhost:8080/perfis)

    JSON: 

        {
            "id": 1,
            "id_funcionario": 1,
            "idade": 50,
            "endereco": "Rua Exemplo, 123",
            "telefone": "1234567890",
            "genero": "Macho",
            "estado_civil": "Casado"
        }


    Headers['Authorization'] Bearer (token) 

###### RETORNO /perfis:

    cod: 200

    {
        "cod": 4,
        "msg": "Perfil atualizado com sucesso!",
        "perfil": {
            "id": "1",
            "id_funcionario": "1",
            "idade": "50",
            "endereco": "Rua Exemplo, 123",
            "telefone": "1234567890",
            "genero": "Macho",
            "estado_civil": "Casado"
        }
    }
                
##### Método DELETE - DELETE /perfis

    URL: (http://localhost:8080/perfis/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /perfis:

    cod: 204

#### /historicoCargos

##### Método post - Cadastro /historicoCargos

    URL: (http://localhost:8080/historicoCargos)

    JSON: 
            {
                "id_funcionario": 1,
                "cargos_anteriores": "Auxiliar, Analista Jr",
                "cargo_atual": "Analista Pleno",
                "data_alteracao": "2025-04-14"
            }

                
    Headers['Authorization'] Bearer (token) 

###### RETORNO /historicoCargos:

    cod: 200

    {
        "cod": 201,
        "msg": "Histórico de cargo cadastrado com sucesso!",
        "historico": {}
    }

##### Método Get - Read ALL /historicoCargos

    URL: (http://localhost:8080/historicoCargos)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /historicoCargos:

    cod: 200

    {
        "cod": 200,
        "msg": "Histórico de cargos encontrado.",
        "historicoCargos": [
            {
                "id": 1,
                "id_funcionario": 1,
                "cargos_anteriores": "Auxiliar, Analista Jr",
                "cargo_atual": "Analista Pleno",
                "data_alteracao": "2025-04-14"
            }
        ]
    }

##### Método Get - Read By ID /historicoCargos

    URL: (http://localhost:8080/historicoCargos/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /historicoCargos:

    cod: 200

    {
        "cod": 200,
        "msg": "Histórico de cargo encontrado.",
        "historico": {
            "id": 1,
            "id_funcionario": 1,
            "cargos_anteriores": "Auxiliar, Analista Jr",
            "cargo_atual": "Analista Pleno",
            "data_alteracao": "2025-04-14"
        }
    }

###### Método PUT - UPDATE /historicoCargos

    URL: (http://localhost:8080/historicoCargos)

    JSON: 

    {
        "id": 1,
        "id_funcionario": 1,
        "cargos_anteriores": "Estagiário, Assistente",
        "cargo_atual": "Analista",
        "data_alteracao": "2025-04-14"
    }



    Headers['Authorization'] Bearer (token) 

###### RETORNO /historicoCargos:

    cod: 200

    {
        "cod": 4,
        "msg": "Histórico de cargo atualizado com sucesso!",
        "historico": {
            "id": "1",
            "id_funcionario": "1",
            "cargos_anteriores": "Estagiário, Assistente",
            "cargo_atual": "Analista",
            "data_alteracao": "2025-04-14"
        }
    }
                
##### Método DELETE - DELETE /historicoCargos

    URL: (http://localhost:8080/historicoCargos/id)
    
    Headers['Authorization'] Bearer (token) 

###### RETORNO /historicoCargos:

    cod: 204

#### /dados

##### Método post /dados

    URL: (http://localhost:8080/dados)

    JSON: 
        {
            "departamento": "Recursos Humanos"
        }

                
    Headers['Authorization'] Bearer (token) 

###### RETORNO /dados: 

    cod: 200

    {
        "msg": "Dados Departamento: Recursos Humanos",
        "media_idade": 20,
        "media_salario": "5.000,00",
        "total_funcionarios": "1"
    }

## FLUXO E FUNCIONAMENTOS 

### Como me localizar no Back-end

#### Arquivos de Controle

Todos os controles estão dentro da pasta -> ./controle, dentro dessa pasta, você encontrará outras pastas, sendo cada uma denominada com seu respectivos Endpoints, ou seja, se deseja acessar o controle de Create de departamento, você deve acessar -> ./controle/departamento e então você encontrará o documento "departamentosCadastrar".

#### Arquivos de Modelo

Os arquivos que fazem a conexão com o banco, ou seja, os modelos, podem ser encontrados dentro da pasta -> ./modelo, dentro dessa pasta, você podera encontrar as pastas que contem as classes de cada tabela, por exemplo, se deseja acessar a parte que insere o create na tabela de departamento: ./modelo/departamento e então você achara o arquivo que contem todas as operações do departamento, estando separadas por métodos dentro do arquivo "departamentos.php".

#### Arquivo de rotas

O Arquivo principal que direciona as rotas para os arquivos pode ser encontrado na raiz do projeto.

### Como me localizar no Front-end

O Arquivo da primeira tela (index.html) pode ser encontrado na raiz do projeto, porém, todos os outros arquivos relacionados ao front, podem ser encontrados na pasta -> ./html, por exemplo, se você deseja acessar o arquivo de HTML da pagina da tabela de departamento, -> ./html e então você poderá ver o arquivo (departamento.html)

### FLUXO DO BACK-END

Roteador -> Controle -> Modelo -> retorna a resposta ao controle -> retorna o JSON de resposta

### FLUXO DO FRONT-END

index -> paginaMain -> arquivos das páginas

## ALGUMAS OBSERVAÇÕES SOBRE O PROJETO

- O projeto não contém os conceitos de Middware, as verificações estão sendo feitas dentro de seus respectivos controles.

- Conteito de Token JWT.

### Ferramentas utilizadas

- HTML/CSS para o visual
- JS para a comunicação do front-end com o Back-end
- PHP para a API (Back-end)

### Sobre o arquivo dados.php

O a rota ./dados com o método post está sendo utilizado para a obtenção de algumas estatísticas básicas a respeito das tabelas, sendo elas -> Idade média dentro de um departamento, Salário médio dentro de um departamento e a quantidade de funcionários dentro de um departtamento. Contúdo, abre brechas para um possivel melhoramento de filtros.