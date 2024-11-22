<?php

$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : null;
$bairro = isset($_GET['bairro']) ? $_GET['bairro'] : null;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
$quartos = isset($_GET['quartos']) ? $_GET['quartos'] : null;
$compraAluga = isset($_GET['compraoualuga']) ? $_GET['compraoualuga'] : null;

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_errno) {
    echo 'Erro ao conectar no banco de dados: ' . $mysqli->connect_error;
    exit();
}

$sql = "SELECT * FROM imoveis WHERE 1=1"; 

if ($cidade) {
    $sql .= " AND cidade = '$cidade'";
}
if ($bairro) {
    $sql .= " AND bairro = '$bairro'";
}
if ($tipo) {
    $sql .= " AND tipo = '$tipo'";
}
if ($quartos) {
    $sql .= " AND numQuartos = '$quartos'";
}
if ($compraAluga) {
    $sql .= " AND compraAluga = '$compraAluga'";
}

// Executa a consulta
$result = $mysqli->query($sql);

if (!$result) {
    echo 'Erro na consulta: ' . $mysqli->error;
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Imóveis Filtrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="src/css/stylefiltro.css">
</head>
<body>

<header>
<nav class="nav justify-content-between" style="display: flex; align-items: center;">
    <a class="nav-link" href="carrinho.php">
        <i class="fa-solid fa-cart-shopping fa-lg" style="color: white; width: 40px; text-align: center;"></i>
    </a>
    <a class="nav-link" href="listaFavoritos.php">
        <i class="fa-solid fa-heart fa-lg" style="color: white; width: 40px; text-align: center;"></i>
    </a>
    <?php if (isset($_SESSION['email'])){
    echo "<a class='nav-link' href='perfilUsuario.php'><i class='fa-solid fa-user fa-lg' style='color: white; width: 40px; text-align: center;'></i></a>";
    }
    ?>
    <a class="nav-link text-center" href="index.php">
        <img src="src/img/property.jpg" alt="Logo" style="width: 100px; height: auto; text-align:center;">
    </a>
    <div class="nav-right">
        <a class="nav-link text-white" href="cadastro.php">Cadastro</a>
        <a class="nav-link text-white" href="login.php">Login</a>
        <?php
        if (isset($_SESSION['email'])){
           echo "<a class='nav-link' href='src/controller/logoutController.php'>Logout</a>";
        }
        ?>
    </div>
</nav>

</header>

<main>
    <div class="container">
        <h1>Imóveis Encontrados</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="row mt-4">
                <?php while ($imovel = $result->fetch_assoc()): ?>
                    <?php
                    // Obtém a primeira imagem associada ao imóvel
                    $queryImagem = "
                        SELECT img.link, img.descricao
                        FROM imagens img 
                        WHERE img.imovelId = ?
                        LIMIT 1
                    ";
                    $stmtImagem = $mysqli->prepare($queryImagem);
                    $stmtImagem->bind_param('i', $imovel['id']);
                    $stmtImagem->execute();
                    $resultImagem = $stmtImagem->get_result();
                    $imagem = $resultImagem->fetch_assoc();

                    // Verifica se há uma imagem associada ao imóvel
                    $imagemLink = isset($imagem['link']) ? $imagem['link'] : 'imagens/padrao.png';
                    $descricaoImagem = isset($imagem['descricao']) ? $imagem['descricao'] : 'Imagem não disponível';
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="imovel.php?id=<?php echo $imovel['id']; ?>">
                                <img src="<?php echo $imagemLink; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($descricaoImagem); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($imovel['tipo']); ?></h5>
                                <p class="card-text">
                                    <strong>Cidade:</strong> <?php echo htmlspecialchars($imovel['cidade']); ?><br>
                                    <strong>Bairro:</strong> <?php echo htmlspecialchars($imovel['bairro']); ?><br>
                                    <strong>Quartos:</strong> <?php echo $imovel['numQuartos']; ?><br>
                                    <strong>Preço:</strong> R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhum imóvel encontrado para os filtros aplicados.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <!-- Footer -->
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
$mysqli->close();
?>
