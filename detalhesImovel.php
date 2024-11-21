<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

// Conectar ao banco de dados
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Obtém o ID do imóvel da URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consulta para pegar os dados do imóvel
$query_imovel = "SELECT * FROM imoveis WHERE id = $id";
$result_imovel = $mysqli->query($query_imovel);

// Verifica se o imóvel foi encontrado
if ($result_imovel && $result_imovel->num_rows > 0) {
    $imovel = $result_imovel->fetch_assoc();
} else {
    echo "<div class='alert alert-warning'>Imóvel não encontrado.</div>";
    exit;
}
// Consulta para pegar as imagens associadas ao imóvel
$query_imagens = "SELECT link, descricao FROM imagens WHERE imovelId = $id";
$result_imagens = $mysqli->query($query_imagens);

// Verifica se as imagens foram encontradas
$imagens = [];
if ($result_imagens && $result_imagens->num_rows > 0) {
    while ($row = $result_imagens->fetch_assoc()) {
        $imagens[] = $row;  // Armazena cada imagem no array
    }
} else {
    $imagens = [];  // Nenhuma imagem encontrada
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- JS do Bootstrap (certifique-se de incluir o JS do Bootstrap no final do body) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="src/css/styleDetalhesImovel.css" type="text/css" rel="stylesheet">
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
</head>
<body>
    <div class="container">
    <a class="arrow" href="index.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
        <div class="fotoImovel">
        <h1>DETALHES DO IMÓVEL</h1>

        <!-- Carrossel de Imagens -->
        <div id="carrosselImagens" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $active = 'active';  // Para garantir que a primeira imagem seja exibida por padrão
                foreach ($imagens as $imagem) { 
                ?>
                    <div class="carousel-item <?php echo $active; ?>">
                    <img src="<?php echo htmlspecialchars($imagem['link']); ?>" alt="<?php echo htmlspecialchars($imagem['descricao']); ?>">
                    </div>
                    <?php 
                    // Após a primeira imagem, remova a classe "active" para que as imagens subsequentes não tenham a classe duplicada
                    $active = ''; 
                } 
                ?>
            </div>
            <!-- Controles do Carrossel -->
           <!-- Controles do Carrossel -->
<button class="carousel-control-prev" type="button" data-bs-target="#carrosselImagens" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carrosselImagens" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>

            </div>
        </div><div class="info">
        <p><strong>Bairro:</strong> <?php echo htmlspecialchars($imovel['bairro']); ?></p>
        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($imovel['cidade']); ?></p>
        <p><strong>CEP:</strong> <?php echo htmlspecialchars($imovel['cep']); ?></p>
        <p><strong>Tamanho:</strong> <?php echo htmlspecialchars($imovel['tamanho']); ?> m²</p>
        <p><strong>Quartos:</strong> <?php echo htmlspecialchars($imovel['numQuartos']); ?></p>
        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($imovel['tipo']); ?></p>
        <p><strong>Compra/Aluguel:</strong> <?php echo htmlspecialchars($imovel['compraAluga']); ?></p>
        <p><strong>Valor:</strong> R$ <?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>

        <div class="button-container">
            <form method="POST" action="src/controller/editarImovelController.php">
                <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
                <button type="submit" class="btn btn-editar">Editar Imóvel</button>
                
            </form>
            <form method="POST" action="src/controller/excluirImovelController.php">
                <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
                <button type="submit" class="btn btn-danger">Excluir Imóvel</button>
            </form>
        </div>
        </div>
    </div>
</body>
</html>
