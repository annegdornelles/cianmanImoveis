<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Galeria de Imóveis</title>
</head>
<body>
<div class="container my-5">

    <?php
    // Conexão ao banco de dados
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_error) {
        die('Erro de conexão: ' . $mysqli->connect_error);
    }

    $query = "SELECT id, url FROM imoveis";
    $result = $mysqli->query($query);

    $images = [];
    while ($imovel = $result->fetch_assoc()) {//retorna do resultado da consulta mysqli como array associativo. cada linha da tabela fica em um array associativo
        $images[] = $imovel;
    }
    $chunkedImages = array_chunk($images, 3);//divide o array em partes menores. meio q torna uma matriz com 3 colunas(numero especificado) e a quantidade de linhas necessarias ate acabar os elementos

    $mysqli->close();
    ?>

    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $activeClass = 'active';
            foreach ($chunkedImages as $group) {
                echo "<div class='carousel-item $activeClass'>";
                echo "<div class='row'>";

                foreach ($group as $imovel) {
                    echo "<div class='col-md-4'>";
                    echo "<a href='imovel.php?id=" . $imovel['id'] . "'>";
                    echo "<img src='" . $imovel['url'] . "' class='d-block w-100' alt='Imagem do Imóvel' style='height: 200px; object-fit: cover;'>";
                    echo "</a>";
                    echo "</div>";
                }

                echo "</div>";
                echo "</div>";
                $activeClass = '';
            }
            ?>
        </div>
        <!-- Controles do carrossel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
