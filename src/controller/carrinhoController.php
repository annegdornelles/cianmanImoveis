<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

if (!isset($_SESSION['email'])) {
    header('Location: ../../login.php');
    exit;
}

$email = $_SESSION['email'];
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Obtém o CPF do cliente baseado no e-mail da sessão
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

    // Verifica se o imóvel está nos favoritos
    $queryFavorito = "SELECT * FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
    $stmtFavorito = $mysqli->prepare($queryFavorito);
    $stmtFavorito->bind_param('si', $clienteCpf, $imovelId);
    $stmtFavorito->execute();
    $resultFavorito = $stmtFavorito->get_result();

    if ($resultFavorito->num_rows === 0) {
        // Se o imóvel não está nos favoritos, redireciona para a página do imóvel com cod=123
        header("Location: ../../imovel.php?cod=123&id=$imovelId");
        exit;
    }

    // Verifica se o imóvel já está no carrinho
    $queryCarrinho = "SELECT * FROM carrinho WHERE clienteCpf = ? AND imovelId = ?";
    $stmtCarrinho = $mysqli->prepare($queryCarrinho);
    $stmtCarrinho->bind_param('si', $clienteCpf, $imovelId);
    $stmtCarrinho->execute();
    $resultCarrinho = $stmtCarrinho->get_result();

    if ($resultCarrinho->num_rows > 0) {
        echo "Este imóvel já está no carrinho de compras.";
    } else {
        // Adiciona o imóvel ao carrinho
        $queryInsert = "INSERT INTO carrinho (clienteCpf, imovelId) VALUES (?, ?)";
        $stmtInsert = $mysqli->prepare($queryInsert);
        $stmtInsert->bind_param('si', $clienteCpf, $imovelId);

        if ($stmtInsert->execute()) {
            echo "Imóvel adicionado ao carrinho de compras!";
        } else {
            echo "Erro ao adicionar ao carrinho.";
        }
    }

    header('Location: ../../carrinho.php');
    exit;
}

// Remove imóvel do carrinho
if (isset($_GET['remover']) && isset($_GET['id'])) {
    $imovelId = (int) $_GET['id'];

    $queryRemover = "DELETE FROM carrinho WHERE clienteCpf = ? AND imovelId = ?";
    $stmtRemover = $mysqli->prepare($queryRemover);
    $stmtRemover->bind_param('si', $clienteCpf, $imovelId);

    if ($stmtRemover->execute()) {
        echo "Imóvel removido do carrinho!";
    } else {
        echo "Erro ao remover do carrinho.";
    }

    header('Location: ../../carrinho.php');
    exit;
}

$mysqli->close();
?>
