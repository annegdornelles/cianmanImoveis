<!--mostra as informações do imovel com base no id passado via url-->

<?php

 require_once __DIR__ . '\src\model\conexaomysql.php';
$imovelId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($imovelId == 0) {
    echo "ID inválido.";
    exit;
}

$query = "SELECT * FROM imoveis WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $imovelId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $imovel = $result->fetch_assoc();
} else {
    echo "Imóvel não encontrado.";
    exit;
}

$imagensQuery = "SELECT link, descricao FROM imagens WHERE imovelId = ?";
$stmt = $mysqli->prepare($imagensQuery);
$stmt->bind_param("i", $imovelId);
$stmt->execute();
$resultImagens = $stmt->get_result();

$favoritado = isset($_GET['favoritado']) && $_GET['favoritado'] == 'true';

$carrinho = isset($_GET['carrinho']) && $_GET['carrinho'] == 'true';

$mysqli->close();
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <title>Detalhes do Imóvel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="src/css/styleimovel.css">
</head>
<style>
body{
    background-color:#fb9cf857;
}


.buttons-container {
    display: flex; 
    flex-direction: row; 
    justify-content: flex-start; 
    align-items: center;
    gap: 10px; 
    margin-top: 20px; 
}

.buttons-container button {
    background: none; 
    padding: 0; 
    cursor: pointer; 
}

.buttons-container button i {
    font-size: 24px; 
    color: inherit; 
    text-align: center; 
    display: inline-block; 
    color: #5e2b5c; width: 40px; text-align: center;
}

.buttons-container button i:hover{
    color:#2e1b4e;
    width:45px;
    height:10px;
}

.cart-button.clicked {
    color: #2e1b4e;
}

.heart-button.clicked{
    color: #2e1b4e;
}

.card-img-top{
    width:600px;
    align-items: center;
    height:auto;
}

    .card-body {
        display: flex;
        margin-top: 10px;
        flex-direction: column;
        justify-content: space-between; 
        height: 100%;
    }

.heart-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.heart-button i {
    font-size: 24px;
    color: #5e2b5c;
}

.heart-button.clicked i {
    color: #2e1b4e; 
}

.heart-button i:hover {
    color: #2e1b4e;
    width: 45px;
    height: 10px;
}

.cart-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.cart-button i {
    font-size: 24px;
    color: #5e2b5c;
}

.cart-button.clicked i {
    color: #2e1b4e; 
}

.cart-button i:hover {
    color: #2e1b4e;
    width: 45px;
    height: 10px;
}

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

.carousel {
    position: relative;
    max-width: 500px;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 5px;
    position: center;
    height:500px;
}

.carousel-images {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel-images img {
    width: 100%;
    flex-shrink: 0;
}

.carousel-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    cursor: pointer;
    padding: 10px;
    font-size: 18px;
    border-radius: 50%;
}

.carousel-button.prev {
    left: 10px;
}

.carousel-button.next {
    right: 10px;
}

.carousel-button:hover {
    background-color: rgba(0, 0, 0, 0.8);
}



</style>
<body>

<div class="container my-4">
<a class="arrow" href="index.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
    <h2>DETALHES DO IMÓVEL</h2>

    <div class="card">
    <div class="carousel">
    <div class="carousel-images">
        <?php while ($imagem = $resultImagens->fetch_assoc()): ?>
            <img src="<?php echo $imagem['link']; ?>" 
                 alt="<?php echo htmlspecialchars($imagem['descricao']); ?>">
        <?php endwhile; ?>
    </div>
    <button class="carousel-button prev" onclick="moveCarousel(-1)">&#10094;</button>
    <button class="carousel-button next" onclick="moveCarousel(1)">&#10095;</button>
</div>

        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($imovel['tipo']); ?></h5>
            <p class="card-text">
                <strong>Valor:</strong> R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?><br>
                <strong>Cidade:</strong> <?php echo htmlspecialchars($imovel['cidade']); ?><br>
                <strong>Bairro:</strong> <?php echo htmlspecialchars($imovel['bairro']); ?><br>
                <strong>Tamanho:</strong> <?php echo htmlspecialchars($imovel['tamanho']); ?> m²<br>
                <strong>Logradouro:</strong> <?php echo htmlspecialchars($imovel['logradouro']); ?><br>
                <strong>Tipo de Transação:</strong> <?php echo htmlspecialchars($imovel['compraAluga']); ?><br>
                <strong>Quartos:</strong> <?php echo htmlspecialchars($imovel['numQuartos']); ?>
            </p>

            <div class="buttons-container">
    
    <form method="POST" action="src/controller/carrinhoController.php">
        <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
        <button type="submit" id="cart-button-1" class="cart-button <?php echo $carrinho ? 'clicked' : ''; ?>">
            <i class="fa-solid fa-cart-shopping fa-lg"></i>
        </button>
    </form>

    <form method="POST" action="src/controller/favoritosController.php">
        <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
        <button type="submit" class="heart-button <?php echo $favoritado ? 'clicked' : ''; ?>">
        <i class="fa-solid fa-heart fa-lg"></i>
        </button>
    </form>
</div>

        <?php
        

if (isset($_GET['cod'])){
    if ($_GET['cod']=='123'){
        echo '<div class="alert-warning" role="alert">
        Você precisa favoritar o imóvel antes de adicionar no carrinho.
        </div>';
    }
    if ($_GET['cod']=='100'){
        echo '<div class="alert-warning" role="alert">
        Imóvel já adicionado aos favoritos.
        </div>';
    }
    if ($_GET['cod']=='300'){
        echo '<div class="alert-success" role="alert">
        Imóvel adicionado aos favoritos com sucesso!
        </div>';
    }

    if ($_GET['cod']=='301'){
        echo '<div class="alert-success" role="alert">
        Imóvel adicionado ao carrinho com sucesso!
        </div>';
    }

    if ($_GET['cod']=='301'){
        echo '<div class="alert-danger" role="alert">
        Este móvel já está no carrinho de compras!
        </div>';
    }
    if ($_GET['cod']=='124'){
        echo '<div class="alert-danger" role="alert">
        Imóvel removido do carrinho de compras!
        </div>';
    }

}

        ?>
    </div>

    <script>
        let currentIndex = 0;

function moveCarousel(direction) {
    const carouselImages = document.querySelector('.carousel-images');
    const images = document.querySelectorAll('.carousel-images img');
    const totalImages = images.length;

    // Atualiza o índice da imagem
    currentIndex += direction;

    // Loop infinito do carrossel
    if (currentIndex < 0) {
        currentIndex = totalImages - 1;
    } else if (currentIndex >= totalImages) {
        currentIndex = 0;
    }

    // Move o carrossel
    const offset = -currentIndex * 100; // Mover 100% por imagem
    carouselImages.style.transform = `translateX(${offset}%)`;
}
</script>
</div>
</body>
</html>
