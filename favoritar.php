<?php
session_start(); // Inicia a sessão

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); // Redireciona para o login se o usuário não estiver logado
    exit;
}

$email = $_SESSION['email'];
$mysqli = new mysqli($host, $user, $password, $database);

// Verifica a conexão
if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Obtém o CPF do cliente com base no e-mail da sessão
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

// Verifica se o formulário foi enviado para adicionar aos favoritos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $imovelId = (int) $_POST['id'];

    // Verifica se o imóvel já está nos favoritos do cliente
    $queryFavorito = "SELECT * FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
    $stmtFavorito = $mysqli->prepare($queryFavorito);
    $stmtFavorito->bind_param('si', $clienteCpf, $imovelId);
    $stmtFavorito->execute();
    $resultFavorito = $stmtFavorito->get_result();

    if ($resultFavorito->num_rows > 0) {
        // Imóvel já está nos favoritos
        echo "Este imóvel já está nos seus favoritos.";
    } else {
        // Adiciona o imóvel aos favoritos
        $queryInsert = "INSERT INTO favoritos (clienteCpf, imovelId) VALUES (?, ?)";
        $stmtInsert = $mysqli->prepare($queryInsert);
        $stmtInsert->bind_param('si', $clienteCpf, $imovelId);

        if ($stmtInsert->execute()) {
            echo "Imóvel adicionado aos favoritos!";
        } else {
            echo "Erro ao adicionar aos favoritos.";
        }
    }

    // Redireciona para a página de favoritos após a ação
    header('Location: listaFavoritos.php');
    exit;
}

// Remove um imóvel dos favoritos
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
    header('Location: listaFavoritos.php');
    exit;
}

$mysqli->close();
?>
