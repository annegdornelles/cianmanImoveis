<?php

   if($_POST){
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $tipo = $_POST['tipo'];
    $quartos = $_POST['quartos'];
    $compraAluga = $_POST['compraoualuga'];
    
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_errno) {
        echo 'Erro ao conectar no banco de dados: ' . $mysqli->connect_error;
        return 0;
    }

    $sql = 'SELECT * FROM imoveis WHERE cidade = "' . $cidade . '" AND bairro = "' . $bairro . '"AND tipo = "'.$tipo.'" AND quartos ="'.$quartos.'" AND compraAluga = "'.$compraAluga;'"';
    $result = $mysqli->query($sql);

    

   }


?>

<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
        <div class="row">
        <?php while ($imovel = $result->fetch_assoc()): ?>
            <div class="col-md-2 mb-4">
                <a href="imovel.php?id=<?php echo $imovel['id']; ?>">
                    <img src="<?php echo $imovel['url']; ?>" class="img-fluid" alt="Imagem do Imóvel">
                </a>
                <p>R$<?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>
                <h5 class="card-title"><?php echo htmlspecialchars($imovel['tipo'])?> '</h5>
                    <p class="card-text">
                        <strong>Cidade:</strong><?php echo htmlspecialchars($imovel['cidade'])?>'<br>
                        <strong>Bairro:</strong><?php echo htmlspecialchars($imovel['bairro'])?> '<br>
                    </p>
                    <a href="carrinho.php?remover=true&id=' . $imovel['id'] . '" class="btn btn-danger">Remover</a>
            </div>
        <?php endwhile; ?>
    </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
