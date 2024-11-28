<?php
session_start();

require_once __DIR__ . '\..\model\conexaomysql.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../../login.php');
    exit;
}

$email = $_SESSION['email'];

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

if ($_POST && isset($_POST['id'])) {
    $imovelId = (int) $_POST['id'];

    $queryFavorito = "SELECT * FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
    $stmtFavorito = $mysqli->prepare($queryFavorito);
    $stmtFavorito->bind_param('si', $clienteCpf, $imovelId);
    $stmtFavorito->execute();
    $resultFavorito = $stmtFavorito->get_result();

    if ($resultFavorito->num_rows === 0) {
        header("Location: ../../imovel.php?cod=123&id=$imovelId&favoritado=true&carrinho=true");
        exit;
    }

    $queryCarrinho = "SELECT * FROM carrinho WHERE clienteCpf = ? AND imovelId = ?";
    $stmtCarrinho = $mysqli->prepare($queryCarrinho);
    $stmtCarrinho->bind_param('si', $clienteCpf, $imovelId);
    $stmtCarrinho->execute();
    $resultCarrinho = $stmtCarrinho->get_result();

    if ($resultCarrinho->num_rows > 0) {
        header("location:../../imovel.php?cod=122&carrinho=true&id=$imovelId&favoritado=true");
        exit;
    } else {
        // Adiciona o imóvel ao carrinho
        $queryInsert = "INSERT INTO carrinho (clienteCpf, imovelId) VALUES (?, ?)";
        $stmtInsert = $mysqli->prepare($queryInsert);
        $stmtInsert->bind_param('si', $clienteCpf, $imovelId);

        if ($stmtInsert->execute()) {
           header("location:../../imovel.php?cod=301&carrinho=true&id=$imovelId&favoritado=true");
           exit;
        } else {
            echo "Erro ao adicionar ao carrinho.";
        }
    }

    header('Location: ../../carrinho.php');
    exit;
}

if (isset($_GET['remover']) && isset($_GET['id'])) {
    $imovelId = (int) $_GET['id'];

    $queryRemover = "DELETE FROM carrinho WHERE clienteCpf = ? AND imovelId = ?";
    $stmtRemover = $mysqli->prepare($queryRemover);
    $stmtRemover->bind_param('si', $clienteCpf, $imovelId);

    if ($stmtRemover->execute()) {
        header("location:../../imovel.php?cod=124&id=$imovelId");//removido do carrinho
    } else {
        echo "Erro ao remover do carrinho.";
    }

    header('Location: ../../carrinho.php');
    exit;
}

$mysqli->close();
?>
