<?php
session_start(); // Inicia a sessão

// Verifica se o carrinho já foi criado na sessão, senão, cria um array vazio
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();
}

// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário
    $id = $_POST['id'];
    $preco = $_POST['valor'];

    // Verifica se o produto já está no carrinho
    $encontrado = false;
    foreach ($_SESSION['carrinho'] as &$produto) {
        if ($produto['id'] == $id) {
            $encontrado = true;
            break;
        }
    }

    // Se o produto não estiver no carrinho, adiciona ele
    if (!$encontrado) {
        $_SESSION['carrinho'][] = [
            'id' => $id,
            'valor' => $preco,
        ];
    }
}

// Remover item do carrinho
if (isset($_GET['remover'])) {
    $id_remover = $_GET['remover'];
    foreach ($_SESSION['carrinho'] as $key => $produto) {
        if ($produto['id'] == $id_remover) {
            unset($_SESSION['carrinho'][$key]);
            break;
        }
    }
    // Reindexa o array após remoção
    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
}

// Calcular o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $produto) {
    $total = $produto['preco'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        header {
            background-color: #2e1b4e;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-bottom: 30px;
        }

        header h1 {
            margin: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2e1b4e;
            color: white;
        }

        .btn-remover {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-remover:hover {
            background-color: #c0392b;
        }

        .total {
            margin-top: 20px;
            font-size: 1.2rem;
            text-align: right;
        }

        .btn-finalizar {
            background-color: #2e1b4e;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-finalizar:hover {
            background-color: #1b1239;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            background-color: #2e1b4e;
            color: white;
            padding: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Carrinho de compras</h1>
</header>

<main>

    <?php if (count($_SESSION['carrinho']) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $produto): ?>
                    <tr>
                        <td><?php echo $produto['nome']; ?></td>
                        <td>R$ <?php echo $produto['preco']; ?></td>
                        <td>
                            <a href="?remover=<?php echo $produto['id']; ?>" class="btn-remover">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <strong>Total: R$ <?php echo $total; ?></strong>
        </div>

        <form action="finalizar.php" method="post">
            <button type="submit" class="btn-finalizar">Finalizar Compra</button>
        </form>

    <?php else: ?>
        <p>Seu carrinho está vazio!</p>
    <?php endif; ?>

</main>

<footer>
    <p>&copy; 2024 Loja Exemplo - Todos os direitos reservados</p>
</footer>

</body>
</html>
