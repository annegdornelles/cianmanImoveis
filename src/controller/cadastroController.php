<?php

function usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc) {

    
    require_once __DIR__ . '\..\model\conexaomysql.php';

    $stmt = $mysqli->prepare("INSERT INTO clientes (cpf, nome, cep, email, dataNasc, senha, telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
       
        $stmt->bind_param("sssssss", $cpf, $nome, $cep, $email, $dataNasc, $senha, $telefone);

        if ($stmt->execute()) {
            header('location:../../cadastro.php?cod=300');
            $id = $stmt->insert_id;

            
        } else {
            echo "Erro ao cadastrar cliente: " . $stmt->error;
            $id = null;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação do statement: " . $mysqli->error;
        $id = null;
    }

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
