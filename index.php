<?php

session_start();

/*quando for editar perfil:<a href="editar.php?id=<?php echo $id; ?>" class="btn btn-primary" value="editar">Editar</a>*/

$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}


$aluguelQuery = "
    SELECT i.id, i.valor, i.cidade, 
           (SELECT link
            FROM imagens 
            WHERE imagens.imovelId = i.id 
            LIMIT 1) AS caminho_imagem
    FROM imoveis i
    WHERE i.compraAluga = 'aluguel'
";
$aluguelResult = $mysqli->query($aluguelQuery);

$compraQuery = "
    SELECT i.id, i.valor, i.cidade, 
           (SELECT link 
            FROM imagens 
            WHERE imagens.imovelId = i.id 
            LIMIT 1) AS caminho_imagem
    FROM imoveis i
    WHERE i.compraAluga = 'compra'
";
$compraResult = $mysqli->query($compraQuery);

$santaMariaQuery = "
    SELECT i.id, i.valor, i.cidade, 
           (SELECT link
            FROM imagens 
            WHERE imagens.imovelId = i.id 
            LIMIT 1) AS caminho_imagem
    FROM imoveis i
    WHERE i.cidade = 'Santa Maria'
";
$santaMariaResult = $mysqli->query($santaMariaQuery);


//$mysqli->close();


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cidade'])) {
    $bairrosPorCidade = [
        'Porto Alegre' => ['Centro', 'Bom Jesus', 'Farrapos', 'Cidade Baixa'],
        'Santa Maria' => ['Centro', 'Camobi', 'Itarare', 'Pains'],
        'Caxias do Sul' => ['Centro', 'Sao Jose', 'Nossa Senhora da Saude', 'Cidade Nova'],
        'Pelotas' => ['Centro', 'Areal', 'Laranjal', 'Sao Goncalo'],
        'Canoas' => ['Centro', 'Marechal Rondon', 'Harmonia', 'Parque Universitario de Canoas'],
    ];

    $cidade = $_GET['cidade'];
   
    if (array_key_exists($cidade, $bairrosPorCidade)) {
        foreach ($bairrosPorCidade[$cidade] as $bairro) {
            echo "<option value=\"$bairro\">$bairro</option>";
        }
    }
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Cianman Imóveis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="src/css/styleindex.css">

    <style>
     .card {
            width: 200px; /* Define a largura do card */
            height: 200px; /* Define a altura do card para ser quadrado */
            margin: 10px; /* Espaço entre os cards */
        }
    

     
    </style>
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
            <img src="src/img/cianman.jpg" alt="Logo" style="width: 70px; height: auto; text-align:center;">
        </a>
        <div class="nav-right">
            <a class="nav-link" href="cadastro.php">Cadastro</a>
            <a class="nav-link" href="login.php">Login</a>
            <?php
            if (isset($_SESSION['email'])){
               echo "<a class='nav-link' href='src/controller/logoutController.php'>Logout</a>";
            }
            ?>
        </div>
    </nav>
</header>

<main class="container">
    <?php

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT nome FROM clientes WHERE email = '$email'";
    $resultado = mysqli_query($mysqli, $query);

    if ($resultado) {
        $linha = mysqli_fetch_assoc($resultado);
        $nome = $linha['nome'];
        echo '<h1>Seja bem-vindo(a), ' . $nome . '</h1>';
    }}

    ?>
    <form method="POST" action="src/controller/filtroController.php">
        <div class="container">
            <div class="row justify-content-center align-items-center g-2">
                <div class="col">
                    <div class="mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <select class="form-select form-select-lg" name="cidade" id="cidade" onchange="selecionarBairros()">
                            <option value="" selected>Selecione a cidade...</option>
                            <option value="Porto Alegre">Porto Alegre</option>
                            <option value="Santa Maria">Santa Maria</option>
                            <option value="Caxias do Sul">Caxias do Sul</option>
                            <option value="Pelotas">Pelotas</option>
                            <option value="Canoas">Canoas</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <select class="form-select form-select-lg" name="bairro" id="bairro">
                            <option value="" selected>Selecione o bairro...</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="quartos" class="form-label">Quartos</label>
                        <select class="form-select form-select-lg" name="quartos" id="quartos">
                            <option value="" selected>Selecione número de quartos...</option>
                            <option value="1">1 quarto</option>
                            <option value="2">2 quartos</option>
                            <option value="3">3 quartos</option>
                            <option value="4">4 quartos</option>
                            <option value="5">5 ou mais quartos</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Imóvel:</label>
                        <select class="form-select form-select-lg" name="tipo" id="tipo">
                            <option value="" selected>Selecione o tipo de imóvel...</option>
                            <option value="salaComercial">Sala comercial</option>
                            <option value="casaResidencial">Casa residencial</option>
                            <option value="aptoResidencial">Apartamento residencial</option>
                            <option value="kitnet">KitNet</option>
                            <option value="Terreno">Terreno</option>
                        </select>
                    </div>
                </div>
            </div>
            <h5>Você prefere:</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="compraoualuga" id="compra" value="compra"/>
                <label class="form-check-label" for="compra">Compra</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="compraoualuga" id="aluguel" value="aluguel"/>
                <label class="form-check-label" for="aluguel">Aluguel</label>
            </div>
            <div class="btn-filtro-container">
                <input type="submit" class="btn btn-primary" value="Enviar">
            </div>
        </div>
    </form>
</div>

<div class="container my-4">
    <h2>Imóveis para Aluguel</h2>
    <div class="row">
        <?php while ($imovel = $aluguelResult->fetch_assoc()): ?>
            <div class="col-md-2 mb-4">
                <a href="imovel.php?id=<?php echo $imovel['id']; ?>">
                    <img src="<?php echo $imovel['caminho_imagem']; ?>" class="img-fluid" alt="Imagem do Imóvel">
                </a>
                <p>R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>
                <p>Cidade: <?php echo htmlspecialchars($imovel['cidade']);?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Imóveis para Compra</h2>
    <div class="row">
        <?php while ($imovel = $compraResult->fetch_assoc()): ?>
            <div class="col-md-2 mb-4">
                <a href="imovel.php?id=<?php echo $imovel['id']; ?>">
                    <img src="<?php echo $imovel['caminho_imagem']; ?>" class="img-fluid" alt="Imagem do Imóvel">
                </a>
                <p>R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>
                <p>Cidade: <?php echo htmlspecialchars($imovel['cidade']);?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Imóveis em Santa Maria</h2>
    <div class="row">
        <?php while ($imovel = $santaMariaResult->fetch_assoc()): ?>
            <div class="col-md-2 mb-4">
                <a href="imovel.php?id=<?php echo $imovel['id']; ?>">
                    <img src="<?php echo $imovel['caminho_imagem']; ?>" class="img-fluid" alt="Imagem do Imóvel">
                </a>
                <p>R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>
                <p>Cidade: <?php echo htmlspecialchars($imovel['cidade']);?></p>
            </div>
        <?php endwhile; ?>
    </div>



    <div class="row my-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sobre Nós</h5>
                    <p class="card-text">Somos a Cianman Imóveis, dedicados a ajudar você a encontrar
                 o imóvel dos seus sonhos. </p>
              
                </div>
           
        </div>
    </div>
</div>

<div class="card" style="width: 18rem;">
    <div class="card-body" style="text-align:center">
       
        <p class="card-text">Entre em contato conosco através das redes sociais!</p>
        <a href="https://wa.me/5511999999999" target="_blank" class="mx-2">
            <i class="fab fa-whatsapp" style="font-size: 2rem; color: #25D366;"></i>
        </a>
        <a href="https://www.instagram.com/seu_perfil" target="_blank" class="mx-2">
            <i class="fab fa-instagram" style="font-size: 2rem; color: #C13584;"></i>
        </a>
        <a href="https://www.facebook.com/seu_perfil" target="_blank" class="mx-2">
            <i class="fab fa-facebook-f" style="font-size: 2rem; color: #3b5998;"></i>
        </a>
    </div>
     </div>
</div>
<script>
    function selecionarBairros() {
        const cidade = document.getElementById("cidade").value;

        if (cidade) {
            fetch(`<?php echo $_SERVER['PHP_SELF']; ?>?cidade=${cidade}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("bairro").innerHTML = '<option value="">Selecione o bairro...</option>' + data;
                })
                .catch(error => console.error("Erro ao carregar bairros:", error));
        } else {
            document.getElementById("bairro").innerHTML = '<option value="">Selecione o bairro...</option>';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-AKQa/c4hP2zR4cIDr5odXUBCpRxdXOxA1Xnx0ekWTf9BS6p+NCRyTozS6eT33NLa" crossorigin="anonymous"></script>
</body>
</html>
