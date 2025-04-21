<?php
session_start();
require_once __DIR__ . '\src\model\conexaomysql.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

// Buscar CPF do cliente
$queryCliente = "SELECT cpf FROM clientes WHERE email = ?";
$stmtCliente = $mysqli->prepare($queryCliente);
$stmtCliente->bind_param('s', $email);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

if ($resultCliente->num_rows === 0) {
    echo "Cliente não encontrado.";
    exit;
}
$cliente = $resultCliente->fetch_assoc();
$clienteCpf = $cliente['cpf'];

// Verifica se existe pedido
$pedidoStatusMsg = '';
$queryPedidoId = "SELECT id FROM pedidos WHERE clienteCpf = ? ORDER BY id DESC LIMIT 1";
$stmtPedidoId = $mysqli->prepare($queryPedidoId);
$stmtPedidoId->bind_param('s', $clienteCpf);
$stmtPedidoId->execute();
$resultPedidoId = $stmtPedidoId->get_result();

$pedidoSolicitado = false;
$pedidoAceito = false;
$pedidoRecusado = false;

if ($resultPedidoId->num_rows > 0) {
    $pedido = $resultPedidoId->fetch_assoc();
    $pedidoId = $pedido['id'];

    // Verificar status de todos os itens
    $queryItens = "SELECT status FROM itenspedido WHERE pedidoId = ?";
    $stmtItens = $mysqli->prepare($queryItens);
    $stmtItens->bind_param('i', $pedidoId);
    $stmtItens->execute();
    $resultItens = $stmtItens->get_result();

    $todosAceitos = true;
    $algumRecusado = false;
    $algumSolicitado = false;

    while ($item = $resultItens->fetch_assoc()) {
        if ($item['status'] === 'recusado') {
            $algumRecusado = true;
            $todosAceitos = false;
        } elseif ($item['status'] === 'solicitado') {
            $algumSolicitado = true;
            $todosAceitos = false;
        }
    }

    if ($todosAceitos && !$algumSolicitado && !$algumRecusado) {
        $pedidoAceito = true;
        $pedidoStatusMsg = "pedido aceito";

        // Zerar carrinho se aceito
        $queryLimpaCarrinho = "DELETE FROM carrinho WHERE clienteCpf = ?";
        $stmtLimpa = $mysqli->prepare($queryLimpaCarrinho);
        $stmtLimpa->bind_param('s', $clienteCpf);
        $stmtLimpa->execute();
    } elseif ($algumRecusado && !$algumSolicitado) {
        $pedidoRecusado = true;
        $pedidoStatusMsg = "pedido negado";
    } elseif ($algumSolicitado) {
        $pedidoSolicitado = true;
    }
}

// Remoção de item do carrinho
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

// Calcular valor total
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

$valorTotal = 0;
if ($resultTotal->num_rows > 0) {
    $total = $resultTotal->fetch_assoc();
    $valorTotal = $total['total'];
}

// Buscar imóveis do carrinho
$queryImoveis = "
    SELECT i.*, 
       (SELECT img.link FROM imagens img WHERE img.imovelId = i.id LIMIT 1) AS imagem
    FROM carrinho c
    JOIN imoveis i ON c.imovelId = i.id
    WHERE c.clienteCpf = ?
";
$stmtImoveis = $mysqli->prepare($queryImoveis);
$stmtImoveis->bind_param('s', $clienteCpf);
$stmtImoveis->execute();
$resultImoveis = $stmtImoveis->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" type="text/css" href="src/css/stylefavoritar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .fa-arrow-left {
            color: #5e2b5c;
            font-size: 30px;
            text-align: left;
        }

        .fa-arrow-left:hover {
            color: #2e1b4e;
            font-size: 35px;
        }

        .arrow {
            text-align: left;
        }

        .container, .card {
            align-items: center;
        }
    </style>
</head>
<body>
    <main>
        <a class="arrow" href="index.php">
            <i class="fa-solid fa-arrow-left fa-lg"></i>
        </a>
        <h1 style="text-align: center;">Carrinho de Compras</h1>

        <?php if ($pedidoAceito): ?>
            <div class="alert alert-success text-center"><?= strtoupper($pedidoStatusMsg) ?></div>
        <?php elseif ($pedidoRecusado): ?>
            <div class="alert alert-danger text-center"><?= strtoupper($pedidoStatusMsg) ?></div>
        <?php endif; ?>

        <?php if ($resultImoveis->num_rows > 0): ?>
            <div class="container">
                <form method="POST" action="src/controller/compraController.php">
                    <?php while ($imovel = $resultImoveis->fetch_assoc()): 
                        $imagem = !empty($imovel['imagem']) ? htmlspecialchars($imovel['imagem']) : 'imagens/padrao.png';
                    ?>
                        <div class="card" style="margin: 15px; padding: 15px; border: 1px solid #ddd;">
                            <img src="<?= $imagem ?>" alt="imagem do imovel" style="width: 100%; height: auto; max-height: 200px; object-fit: cover;">
                            <h5 class="card-title"><?= htmlspecialchars($imovel['tipo']) ?></h5>
                            <p class="card-text">
                                <strong>Valor:</strong> R$<?= number_format($imovel['valor'], 2, ',', '.') ?><br>
                                <strong>Cidade:</strong> <?= htmlspecialchars($imovel['cidade']) ?><br>
                                <strong>Bairro:</strong> <?= htmlspecialchars($imovel['bairro']) ?><br>
                                <strong>Logradouro:</strong> <?= htmlspecialchars($imovel['logradouro']) ?><br>
                            </p>
                            <a href="carrinho.php?remover=true&id=<?= $imovel['id'] ?>" class="btn btn-danger">Remover</a>
                        </div>
                    <?php endwhile; ?>

                    <div style="margin: 20px; text-align: center;">
                        <h4>Valor total no carrinho: R$ <?= number_format($valorTotal, 2, ',', '.') ?></h4>

                        <?php if ($pedidoSolicitado): ?>
                            <p class="btn btn-success disabled">Pedido Solicitado</p>
                        <?php else: ?>
                            <input type="submit" name="finalizarCompra" value="Solicitar Pedido" class="btn btn-primary">
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin: 20px;">Você não possui imóveis em seu carrinho.</div>
        <?php endif; ?>
    </main>
</body>
</html>

<?php $mysqli->close(); ?>
