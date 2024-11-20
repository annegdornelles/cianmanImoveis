<link rel="stylesheet" type="text/css" href="src/css/stylefavoritar.css">
<?php
session_start(); 

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
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

$queryFavoritos = "SELECT i.* FROM favoritos f JOIN imoveis i ON f.imovelId = i.id WHERE f.clienteCpf = ?";
$stmtFavoritos = $mysqli->prepare($queryFavoritos);
$stmtFavoritos->bind_param('s', $clienteCpf);
$stmtFavoritos->execute();
$resultFavoritos = $stmtFavoritos->get_result();

if ($resultFavoritos->num_rows > 0) {
    echo '<div class="container">';
    while ($imovel = $resultFavoritos->fetch_assoc()) {
        echo '
            <div class="card" style="display: inline-block; width: 300px; margin: 10px;">
                <img src="' . htmlspecialchars($imovel['url']) . '" class="card-img-top" alt="Imagem do Imóvel">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($imovel['tipo']) . '</h5>
                    <p class="card-text">
                        <strong>Valor:</strong> R$' . number_format($imovel['valor'], 2, ',', '.') . '<br>
                        <strong>Cidade:</strong> ' . htmlspecialchars($imovel['cidade']) . '<br>
                        <strong>Bairro:</strong> ' . htmlspecialchars($imovel['bairro']) . '
                    </p>
                    <!-- Link para remover o imóvel dos favoritos -->
                    <a href="listaFavoritos.php?remover=true&id=' . $imovel['id'] . '" class="btn btn-danger">Remover</a>
                </div>
            </div>
        ';
    }
    echo '</div>';
} else {
    echo "Você não possui imóveis favoritos.";
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

    // Redireciona para a mesma página após a remoção para atualizar a lista
    header('Location: listaFavoritos.php');
    exit;
}

$mysqli->close();
?>
