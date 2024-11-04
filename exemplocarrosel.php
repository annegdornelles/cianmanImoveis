<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Carrosseis de Imóveis</title>
</head>
<body>
<div class="container my-5">

    <?php
    // Conecte-se ao banco de dados
    include 'db_connection.php';

    // Quantidade de carrosseis e itens por carrossel
    $totalCarrosseis = 5;
    $itensPorCarrossel = 7;

    // Loop para gerar cinco carrosseis
    for ($i = 1; $i <= $totalCarrosseis; $i++) {
        echo "<div id='carouselExample$i' class='carousel slide mb-4' data-bs-ride='carousel'>";
        echo "<div class='carousel-inner'>";

        // Consulta ao banco de dados para buscar imóveis (com offset para evitar repetição)
        $offset = ($i - 1) * $itensPorCarrossel;
        $query = "SELECT id, imagem FROM imoveis LIMIT $itensPorCarrossel OFFSET $offset";
        $result = mysqli_query($conn, $query);

        $activeClass = 'active';
        while ($imovel = mysqli_fetch_assoc($result)) {
            echo "<div class='carousel-item $activeClass'>";
            echo "<a href='imovel.php?id=" . $imovel['id'] . "'>";
            echo "<img src='" . $imovel['imagem'] . "' class='d-block w-100' alt='Imagem do Imóvel'>";
            echo "</a>";
            echo "</div>";
            $activeClass = ''; // Apenas o primeiro item será "ativo"
        }

        echo "</div>";
        echo "<button class='carousel-control-prev' type='button' data-bs-target='#carouselExample$i' data-bs-slide='prev'>";
        echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
        echo "<span class='visually-hidden'>Anterior</span>";
        echo "</button>";
        echo "<button class='carousel-control-next' type='button' data-bs-target='#carouselExample$i' data-bs-slide='next'>";
        echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
        echo "<span class='visually-hidden'>Próximo</span>";
        echo "</button>";
        echo "</div>";
    }
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
