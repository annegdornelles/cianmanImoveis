<?php

session_start(); 

require_once __DIR__ . '\..\model\conexaomysql.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../../login.php');
    exit;
}

// Recupera o email da sessão
$email = $_SESSION['email'];

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Busca o CPF do cliente com base no email
$queryCliente = "SELECT cpf FROM clientes WHERE email = ?";
$stmtCliente = $mysqli->prepare($queryCliente);
$stmtCliente->bind_param('s', $email);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

if ($resultCliente->num_rows > 0) {
    $cliente = $resultCliente->fetch_assoc();
    $clienteCpf = $cliente['cpf'];
} else {
    echo "Cliente não encontrado.";
    exit;
}

// Resto do código...


if ($_POST && isset($_POST['id'])) {
    $imovelId = (int) $_POST['id'];

    $queryFavorito = "SELECT * FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
    $stmtFavorito = $mysqli->prepare($queryFavorito);
    $stmtFavorito->bind_param('si', $clienteCpf, $imovelId);
    $stmtFavorito->execute();
    $resultFavorito = $stmtFavorito->get_result();

    if ($resultFavorito->num_rows > 0) {
        header('location:../../imovel.php?id='.$imovelId.'&cod=100&favoritado=true');
        exit;
    } else {
        $_SESSION['imovel'] = $imovelId;
        $queryInsert = "INSERT INTO favoritos (clienteCpf, imovelId) VALUES (?, ?)";
        $stmtInsert = $mysqli->prepare($queryInsert);
        $stmtInsert->bind_param('si', $clienteCpf, $imovelId);

        if ($stmtInsert->execute()) {
            header('location:../../imovel.php?id='.$imovelId.'&cod=300&favoritado=true');
            exit;
        } else {
            echo "Erro ao adicionar aos favoritos.";
            exit;
        }
    }

    header('Location: ../../listaFavoritos.php');
    exit;
}

if (isset($_GET['remover']) && isset($_GET['id'])) {
    $imovelId = (int) $_GET['id'];

    $queryRemover = "DELETE FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
    $stmtRemover = $mysqli->prepare($queryRemover);
    $stmtRemover->bind_param('si', $clienteCpf, $imovelId);

    if ($stmtRemover->execute()) {
        echo "Imóvel removido dos favoritos!";
    } else {
        echo "Erro ao remover dos favoritos.";
    }

    // Redireciona para a página de favoritos após a remoção
    header('Location: ../../listaFavoritos.php');
    exit;
}

$mysqli->close();
?>
