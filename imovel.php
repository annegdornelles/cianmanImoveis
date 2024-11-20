<!--mostra as informações do imovel com base no id passado via url-->

<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM imoveis WHERE id = $id";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $imovel = $result->fetch_assoc();
} else {
    echo "Imóvel não encontrado.";
    exit;
}

$favoritado = isset($_GET['favoritado']) && $_GET['favoritado'] == 'true';

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
    border: none; 
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
    height:500px;
}

    .card-body {
        display: flex;
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
<body>

<div class="container my-4">
<a class="arrow" href="index.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
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
        <button type="submit" id="cart-button-1" class="btn btn-primary">
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
    
    

}

        ?>
    </div>

   <!-- <script>
       document.addEventListener('DOMContentLoaded', () => {
    const cartButtons = document.querySelectorAll('[id^="cart-button"]');
    const favoriteButtons = document.querySelectorAll('[id^="heart-button"]');

    const initializeButtonState = (buttons, storageKeyPrefix) => {
        buttons.forEach(button => {
            const buttonId = button.id;
            const savedState = localStorage.getItem(storageKeyPrefix + buttonId); // Recupera o estado salvo

            // Se o estado salvo for 'true', aplica a classe 'clicked'
            if (savedState === 'true') {
                button.classList.add('clicked');
            }

            // Quando o botão for clicado, alterna a classe 'clicked' e armazena o novo estado
            button.addEventListener('click', () => {
                button.classList.toggle('clicked');
                const isClicked = button.classList.contains('clicked');
                localStorage.setItem(storageKeyPrefix + buttonId, isClicked); // Armazena o estado
                console.log(`Button ${buttonId} is ${isClicked ? 'clicked' : 'unclicked'}`);
            });
        });
    };

    // Inicializa os estados dos botões
    initializeButtonState(cartButtons, 'cart-');
    initializeButtonState(favoriteButtons, 'heart-');
});


    </script>-->
</div>
</body>
</html>