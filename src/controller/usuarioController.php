<?php

function usuarioInsert($nome, $status) {
    require_once './cadastroController.php';

    $host = 'localhost';
    $user = 'root';
    $password = '';
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
    $host = 'localhost';
    $user = 'root';
    $password = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);
    $numRows = 0;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        $sql = 'SELECT * FROM usuarios WHERE email = "' . $email . '" AND senha = "' . $senha . '"';
        $result = $mysqli->query($sql);

        $numRows = $result->num_rows; // Obtém o número de registros selecionados
        $mysqli->close();
    }

    return $numRows;
}

function usersUpdate($id, $nome, $cep, $email, $dataNasc, $senha, $telefone) {
    $host = 'localhost';
    $user = 'root';
    $password = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);
    $total = 0;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
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
    }

    return $total;
}
?>
