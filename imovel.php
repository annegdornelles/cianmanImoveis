<!--mostra as informações do imovel com base no id passado via url-->

<?php
$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Obtém o ID do imóvel a partir do parâmetro URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM imoveis WHERE id = $id";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $imovel = $result->fetch_assoc();
} else {
    echo "Imóvel não encontrado.";
    exit;
}

$mysqli->close();
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <title>Detalhes do Imóvel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  
    <link rel="stylesheet" type="text/css" href="src/css/styleimovel.css">
</head>
<style>
    .card-img-top{
        width:600px;
        align-items: center;
        height:500px;
    }
    .buttons-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        .buttons-container button {
            flex: 1; 
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            height: 100%;
        }

</style>
<body>
    
<nav>
    <nav
        class="nav justify-content-center  "
    >
    
</nav>
<<<<<<< HEAD

=======
<form method="POST" action="src/controller/carrinhoController.php">
    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
    <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
</form>
<form method="POST" action="src/controller/favoritosController.php">
    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
    <button type="submit" class="btn btn-primary">Adicionar ao favoritos</button>
</form>
>>>>>>> 1f9fd3acc8d5978ca65b91b2d02ae9f3199b0c94

<div class="container my-4">
    <h2>Detalhes do Imóvel</h2>

    <div class="card">
        <img src="<?php echo $imovel['url']; ?>" class="card-img-top" alt="Imagem do Imóvel">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($imovel['tipo']); ?></h5>
            <p class="card-text">
                <strong>Valor:</strong> R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?><br>
                <strong>Cidade:</strong> <?php echo htmlspecialchars($imovel['cidade']); ?><br>
                <strong>Bairro:</strong> <?php echo htmlspecialchars($imovel['bairro']); ?><br>
                <strong>Logradouro:</strong> <?php echo htmlspecialchars($imovel['logradouro']); ?><br>
                <strong>Tipo de Transação:</strong> <?php echo htmlspecialchars($imovel['compraAluga']); ?><br>
                <strong>Quartos:</strong> <?php echo htmlspecialchars($imovel['numQuartos']); ?>
            </p>

            <div class="buttons-container">
                <form method="POST" action="src/controller/carrinhoController.php">
                    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
                    <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                </form>
                <form method="POST" action="favoritar.php">
                    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
                    <button type="submit" class="btn btn-secondary">Adicionar aos Favoritos</button>
                </form>
            </div>
            <a href="index.php" class="btn btn-primary">Voltar</a>
            <form>
        </div>
    </div>
</div>
</body>
</html>
