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
            $_SESSION['role'] = 'corretor'; 
            header('location:../../corretorImoveis.php'); 
            exit();
        } else {
            header('location:../../login.php?cod=300');
            exit();
        }
    } else {
        header('location:../../login.php?cod=171'); 
        exit();
    }
}

function usersLogin($email, $senha) {
    require_once __DIR__ . '\..\model\conexaomysql.php';

    if (str_ends_with($email, '@cianman.com')) {
        $table = 'funcionarios'; 
    } else {
        $table = 'clientes';
    }

    $sql = 'SELECT * FROM ' . $table . ' WHERE email="' . $mysqli->real_escape_string($email) . '" AND senha="' .$senha . '"';

    $result = $mysqli->query($sql);

    $mysqli->close();

    if ($result && $result->num_rows > 0) {
        return $result->num_rows;
    } else {
        return 0;
    }
}
?>
