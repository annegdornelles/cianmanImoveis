<?php
session_start();
require_once __DIR__ . '/../model/conexaomysql.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../../login.php');
    exit;
}

$email = $_SESSION['email'];

// Buscar CPF do cliente logado
$queryCliente = "SELECT cpf FROM clientes WHERE email = ?";
$stmtCliente = $mysqli->prepare($queryCliente);
$stmtCliente->bind_param('s', $email);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

if ($resultCliente->num_rows === 0) {
    die("Cliente não encontrado.");
}
$cliente = $resultCliente->fetch_assoc();
$clienteCpf = $cliente['cpf'];

// 1. Criar novo pedido
$queryPedido = "INSERT INTO pedidos (clienteCpf) VALUES (?)";
$stmtPedido = $mysqli->prepare($queryPedido);
$stmtPedido->bind_param('s', $clienteCpf);
$stmtPedido->execute();
$pedidoId = $stmtPedido->insert_id;

// 2. Buscar imóveis do carrinho
$queryImoveis = "
    SELECT i.id AS imovelId, i.funcionariosId AS corretorId
    FROM carrinho c
    JOIN imoveis i ON c.imovelId = i.id
    WHERE c.clienteCpf = ?
";
$stmtImoveis = $mysqli->prepare($queryImoveis);
$stmtImoveis->bind_param('s', $clienteCpf);
$stmtImoveis->execute();
$resultImoveis = $stmtImoveis->get_result();

// 3. Inserir itensPedido
$queryItem = "INSERT INTO itensPedido (pedidoId, imovelId, corretorId) VALUES (?, ?, ?)";
$stmtItem = $mysqli->prepare($queryItem);

while ($row = $resultImoveis->fetch_assoc()) {
    $stmtItem->bind_param('iii', $pedidoId, $row['imovelId'], $row['corretorId']);
    $stmtItem->execute();
}

// 4. Limpar carrinho
/*$queryLimpar = "DELETE FROM carrinho WHERE clienteCpf = ?";
$stmtLimpar = $mysqli->prepare($queryLimpar);
$stmtLimpar->bind_param('s', $clienteCpf);
$stmtLimpar->execute();*/

// 5. Redirecionar para página de confirmação
header('Location: ../../carrinho.php?pedido=solicitado');
exit;
?>
