<?php

session_start();

if ($_POST) {

    //$users = usersLoadAll();

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se a função de login retorna mais de 0
    if (usersLogin($email, $senha) > 0) { 
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;

        // Verifica se o e-mail termina com "@cianman.com"
        if (str_ends_with($email, '@cianman.com')) {
            $_SESSION['role'] = 'corretor'; // Identifica como corretor
            header('location:../../corretorImoveis.php'); 
            exit();
        } else {
            header('location:../../login.php?cod=300'); // Redireciona para a página padrão
            exit();
        }
    } else {
        // Redireciona com erro se as credenciais estiverem incorretas
        header('location:../../login.php?cod=171'); // Credenciais inválidas
        exit();
    }
}

function usersLogin($email, $senha) {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    // Cria a conexão com o banco de dados
    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_errno) {
        echo 'Erro ao conectar no banco de dados: ' . $mysqli->connect_error;
        return 0;
    }

    // Define o nome da tabela com base no domínio do email
    if (str_ends_with($email, '@cianman.com')) {
        $table = 'funcionarios'; // Se for corretor
    } else {
        $table = 'clientes'; // Se for cliente
    }

    // Monta a consulta SQL
    $sql = 'SELECT * FROM ' . $table . ' WHERE email="' . $mysqli->real_escape_string($email) . '" AND senha="' . $mysqli->real_escape_string($senha) . '"';

    $result = $mysqli->query($sql);

    // Fecha a conexão
    $mysqli->close();

    if ($result && $result->num_rows > 0) {
        return $result->num_rows; // Retorna o número de registros selecionados pela consulta SQL
    } else {
        return 0; // Caso não encontre nenhum registro
    }
}
?>
