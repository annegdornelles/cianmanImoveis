<?php

function usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc) {
    
require_once __DIR__.'\model\conexaomysql.php';
    // Usando prepared statement para evitar SQL Injection
    $stmt = $mysqli->prepare("INSERT INTO clientes (cpf, nome, cep, email, dataNasc, senha, telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Verifica se a preparação do statement foi bem-sucedida
    if ($stmt) {
        // Vincula os parâmetros aos placeholders
        $stmt->bind_param("sssssss", $cpf, $nome, $cep, $email, $dataNasc, $senha, $telefone);

        // Executa a query
        if ($stmt->execute()) {
            header('location:../../cadastro.php?cod=300');
            // Obtendo o ID do último registro inserido
            $id = $stmt->insert_id;

            
        } else {
            echo "Erro ao cadastrar cliente: " . $stmt->error;
            $id = null;
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "Erro na preparação do statement: " . $mysqli->error;
        $id = null;
    }

    // Fecha a conexão
    $mysqli->close();

    return $id;
}

// Verifica se há dados enviados via POST antes de chamar a função
if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $cep = $_POST['cep'];
    $senha = $_POST['senha'];
    $dataNasc = $_POST['dataNasc'];

    // Chama a função e passa os parâmetros
    $id = usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc);

    if ($id) {
        echo "ID do cliente cadastrado: " . $id;
        header('location:../../login.php');
    }
}

   

?>
