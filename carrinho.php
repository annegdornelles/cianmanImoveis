<?php
session_start(); // Inicia a sessão

// Verifica se o carrinho já foi criado na sessão; caso contrário, cria um array vazio
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Exibe os dados recebidos para verificar se estão corretos
    
    // Recupera os dados do formulário e define um valor padrão para 'url'
    $id = $_POST['id'];
    $preco = $_POST['valor'];
    $url = isset($_POST['url']) ? $_POST['url'] : ''; // Verifica se a URL existe
}


// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário e define um valor padrão para 'url'
    $id = $_POST['id'];
    $preco = $_POST['valor'];
    $url = isset($_POST['url']) ? $_POST['url'] : ''; // Verifica se a URL existe

    // Verifica se o produto já está no carrinho
    $encontrado = false;
    foreach ($_SESSION['carrinho'] as &$produto) {
        if ($produto['id'] == $id) {
            $encontrado = true;
            break;
        }
    }

    // Se o produto não estiver no carrinho, adiciona-o
    if (!$encontrado) {
        $_SESSION['carrinho'][] = [
            'id' => $id,
            'preco' => $preco,
            'url' => $url,
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
    // Reindexa o array após a remoção
    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
}

// Calcular o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $produto) {
    $total += $produto['preco'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<header>
    <h1>Carrinho de Compras</h1>
</header>

<main>

    <?php if (count($_SESSION['carrinho']) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $produto): ?>
                    <tr>
                        <td>
                            <?php if (!empty($produto['url'])): ?>
                                <td><img src="<?php echo htmlspecialchars($produto['url']); ?>" alt="Imagem do Imóvel" width="100" height="75"></td>
                            <?php else: ?>
                                <span>Imagem não disponível</span>
                            <?php endif; ?>
                        </td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td><a href="?remover=<?php echo $produto['id']; ?>">Remover</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <strong>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
        </div>

        <form action="finalizar.php" method="post">
            <button type="submit">Finalizar Compra</button>
        </form>

        <a href="index.php">Voltar</a>

    <?php else: ?>
        <p>Seu carrinho está vazio!</p>
        <a href="index.php">Voltar</a>
    <?php endif; ?>

</main>

<footer>
    <p>&copy; 2024 Loja Exemplo - Todos os direitos reservados</p>
</footer>

</body>
</html>
