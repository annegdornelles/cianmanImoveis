<?php

function usuarioInsert($nome, $status) {
    require_once './cadastroController.php';

    $host = 'localhost';
    $user = 'root';
    $password = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);
    $id = null;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        $sql = 'INSERT INTO projetos VALUES (0, "' . $nome . '", "' . $status . '")';
        $result = $mysqli->query($sql);

        if ($result) {
            $id = $mysqli->insert_id; // Obtém o último ID inserido
        }

        $mysqli->close();
    }

    return $id;
}

function usersLogin($email, $senha) {
    require_once __DIR__ . '\..\model\conexaomysql.php';
    $numRows = 0;

        echo 'Conexão realizada com sucesso.';
        $sql = 'SELECT * FROM clientes WHERE email = "' . $email . '" AND senha = "' . $senha . '"';
        $result = $mysqli->query($sql);

        $numRows = $result->num_rows; 
        $mysqli->close();

    return $numRows;
}

function usersUpdate($id, $nome, $cep, $email, $dataNasc, $senha, $telefone) {
    
    require_once __DIR__ . '\..\model\conexaomysql.php';
    
    $total = 0;


        echo 'Conexão realizada com sucesso.';
        $sql = 'UPDATE clientes SET nome = "' . $nome . '", cep = "' . $cep . '", email = "' . $email . '", dataNasc = "' . $dataNasc . '", senha = "' . $senha . '", telefone = "' . $telefone . '" WHERE id = ' . $id;

        if ($mysqli->query($sql)) {
            $total = $mysqli->affected_rows;
            $mysqli->commit();
        } else {
            $mysqli->rollback();
            echo 'Erro ao atualizar registro.';
        }

        $mysqli->close();


    return $total;
}
?>
