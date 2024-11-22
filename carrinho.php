<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de compras</title>
</head>
<body>
    
</body>
</html><link rel="stylesheet" type="text/css" href="src/css/stylefavoritar.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="src/css/stylefavoritar.css">
<style>
   .fa-arrow-left{
    color:#5e2b5c;
    font-size: 30px;
    text-align: left;
}

.fa-arrow-left:hover{
    color:#2e1b4e;
    font-size: 35px;
}

.arrow{
    text-align: left;
}

.container,.card{
    align-items: center;
}
        

</style>

<body>

    <main>
    <a class="arrow" href="index.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
    <h1 style="text-align: center;">Carrinho de Compras</h1>

<?php
session_start();

// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
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

// Verifica se o imóvel deve ser removido do carrinho
if (isset($_GET['remover']) && isset($_GET['id'])) {
    $imovelId = (int) $_GET['id'];

    $queryRemover = "DELETE FROM carrinho WHERE clienteCpf = ? AND imovelId = ?";
    $stmtRemover = $mysqli->prepare($queryRemover);
    $stmtRemover->bind_param('si', $clienteCpf, $imovelId);

    if ($stmtRemover->execute()) {
        header('Location: carrinho.php');
        exit;
    } else {
        echo "Erro ao remover do carrinho.";
    }
}

// Calcula o valor total dos imóveis no carrinho
$queryTotal = "
    SELECT SUM(i.valor) AS total
    FROM carrinho c
    JOIN imoveis i ON c.imovelId = i.id
    WHERE c.clienteCpf = ?
";
$stmtTotal = $mysqli->prepare($queryTotal);
$stmtTotal->bind_param('s', $clienteCpf);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();

$valorTotal = 0; // Valor inicial
if ($resultTotal->num_rows > 0) {
    $total = $resultTotal->fetch_assoc();
    $valorTotal = $total['total'];
}

// Obtém os imóveis no carrinho e suas respectivas imagens
$queryImoveis = "
    SELECT i.*, 
       (SELECT img.link
        FROM imagens img 
        WHERE img.imovelId = i.id 
        LIMIT 1) AS imagem
FROM carrinho c
JOIN imoveis i ON c.imovelId = i.id
WHERE c.clienteCpf = ?

";
$stmtImoveis = $mysqli->prepare($queryImoveis);
$stmtImoveis->bind_param('s', $clienteCpf);
$stmtImoveis->execute();
$resultImoveis = $stmtImoveis->get_result();

if ($resultImoveis->num_rows > 0) {
    echo '<div class="container">';
    while ($imovel = $resultImoveis->fetch_assoc()) {
        // Verifica se há uma imagem associada ao imóvel
        $imagem = !empty($imovel['imagem']) ? htmlspecialchars($imovel['imagem']) : 'imagens/padrao.png';

        echo '
        <div class="card" style="margin: 15px; padding: 15px; border: 1px solid #ddd;">
            <img src="' . $imagem . '" alt="imagem do imovel" style="width: 100%; height: auto; max-height: 200px; object-fit: cover;">
            <h5 class="card-title">' . htmlspecialchars($imovel['tipo']) . '</h5>
            <p class="card-text">
                <strong>Valor:</strong> R$' . number_format($imovel['valor'], 2, ',', '.') . '<br>
                <strong>Cidade:</strong> ' . htmlspecialchars($imovel['cidade']) . '<br>
                <strong>Bairro:</strong> ' . htmlspecialchars($imovel['bairro']) . '<br>
                <strong>Logradouro:</strong> ' . htmlspecialchars($imovel['logradouro']) . '<br>
            </p>
            <a href="carrinho.php?remover=true&id=' . $imovel['id'] . '" class="btn btn-danger">Remover</a>
        </div>';
    }
    echo '</div>';

    // Exibe o valor total
    echo '<div style="margin: 20px; text-align: center;">
            <h4>Valor total no carrinho: R$ ' . number_format($valorTotal, 2, ',', '.') . '</h4>
          </div>';
} else {
    echo '<div style="text-align: center; margin: 20px;">Você não possui imóveis em seu carrinho.</div>';
}

$mysqli->close();
?>
