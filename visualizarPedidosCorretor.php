<<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'corretor') {
    header('Location: login.php');
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

$email = $_SESSION['email'];

// Pega o ID do corretor logado
$stmt = $mysqli->prepare("SELECT id, nome FROM funcionarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Funcionário não encontrado.";
    exit();
}

$row = $result->fetch_assoc();
$funcionariosId = $row['id'];
$nomeCorretor = $row['nome'];
$stmt->close();

// Processamento da ação nos itens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['item_id'])) {
    $itemId = intval($_POST['item_id']);
    $acao = $_POST['acao'];

    if (in_array($acao, ['aceito', 'recusado'])) {
        $stmt = $mysqli->prepare("UPDATE itenspedido SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $acao, $itemId);
        $stmt->execute();
        $stmt->close();
    }
}

// Buscar pedidos e itens relacionados aos imóveis do corretor
$query = "
    SELECT 
        p.id AS pedidoId,
        p.status AS statusPedido,
        c.nome AS nomeCliente,
        c.email AS emailCliente,
        ip.id AS itemId,
        ip.status AS statusItem,
        i.cidade,
        i.logradouro
    FROM pedidos p
    INNER JOIN itenspedido ip ON p.id = ip.pedidoId
    INNER JOIN imoveis i ON ip.imovelId = i.id
    INNER JOIN clientes c ON p.clienteCpf = c.cpf
    WHERE i.funcionariosId = ?
    ORDER BY p.id DESC
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $funcionariosId);
$stmt->execute();
$result = $stmt->get_result();

// Agrupar por pedido
$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[$row['pedidoId']]['cliente'] = [
        'nome' => $row['nomeCliente'],
        'email' => $row['emailCliente']
    ];
    $pedidos[$row['pedidoId']]['itens'][] = [
        'itemId' => $row['itemId'],
        'logradouro' => $row['logradouro'],
        'cidade' => $row['cidade'],
        'status' => $row['statusItem']
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Recebidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<a class="arrow" href="corretorImoveis.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
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
        

</style>
<body class="container mt-5">
    <h2>Pedidos Recebidos</h2>

    <?php if (!empty($pedidos)): ?>
        <?php foreach ($pedidos as $pedidoId => $pedido): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Pedido #<?= $pedidoId ?></h5>
                    <p class="card-text">
                        Cliente: <strong><?= htmlspecialchars($pedido['cliente']['nome']) ?></strong><br>
                        E-mail: <?= htmlspecialchars($pedido['cliente']['email']) ?>
                    </p>

                    <h6>Itens do Pedido:</h6>
                    <?php foreach ($pedido['itens'] as $item): ?>
                        <div class="border rounded p-2 mb-2">
                            <p>
                                Imóvel: <?= htmlspecialchars($item['logradouro']) ?>, <?= htmlspecialchars($item['cidade']) ?><br>
                                Status: <strong><?= ucfirst($item['status']) ?></strong>
                            </p>

                            <?php if ($item['status'] === 'solicitado'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="item_id" value="<?= $item['itemId'] ?>">
                                    <button type="submit" name="acao" value="aceito" class="btn btn-success btn-sm">Aceitar</button>
                                    <button type="submit" name="acao" value="recusado" class="btn btn-danger btn-sm">Recusar</button>
                                </form>
                            <?php else: ?>
                                <p class="text-muted">Este item foi <?= $item['status'] ?>.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum pedido recebido ainda.</p>
    <?php endif; ?>
</body>
</html>
