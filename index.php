<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Consulta para obter as URLs das imagens
$query = "SELECT id, url FROM imoveis";
$result = $mysqli->query($query);

$images = [];
while ($imovel = $result->fetch_assoc()) {//retorna do resultado da consulta mysqli como array associativo. cada linha da tabela fica em um array associativo
    $images[] = $imovel;
}
$chunkedImages = array_chunk($images, 3);//divide o array em partes menores. meio q torna uma matriz com 3 colunas(numero especificado) e a quantidade de linhas necessarias ate acabar os elementos

$mysqli->close();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cidade'])) {
    $bairrosPorCidade = [
        'poa' => ['Centro', 'Bom Jesus', 'Farrapos', 'Cidade Baixa'],
        'sm' => ['Centro', 'Camobi', 'Itarare', 'Pains'],
        'caxias' => ['Centro', 'Sao Jose', 'Nossa Senhora da Saude', 'Cidade Nova'],
        'pelotas' => ['Centro', 'Areal', 'Laranjal', 'Sao Goncalo'],
        'canoas' => ['Centro', 'Marechal Rondon', 'Harmonia', 'Parque Universitario de Canoas'],
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
    <link rel="stylesheet" type="text/css" href="src/css/styleindex.css">
</head>

</head>

<body>
<header>
    <nav class="nav justify-content-between">
        <a class="nav-link nav-left" href="perfilUsuario.php" aria-current="page">.</a>
        <span class="nav-link text-center">Cianman Imóveis</span>
        <div class="nav-right">
            <a class="nav-link" href="cadastro.php">Cadastro</a>
            <a class="nav-link" href="login.php">Login</a>
        </div>
    </nav>
</header>

<main>
    <form method="POST" action="">
        <div class="container">
            <div class="row justify-content-center align-items-center g-2">
                <div class="col">
                    <div class="mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <select class="form-select form-select-lg" name="cidade" id="cidade" onchange="selecionarBairros()">
                            <option selected>Selecione a cidade...</option>
                            <option value="poa">Porto Alegre</option>
                            <option value="sm">Santa Maria</option>
                            <option value="caxias">Caxias do Sul</option>
                            <option value="pelotas">Pelotas</option>
                            <option value="canoas">Canoas</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <select class="form-select form-select-lg" name="bairro" id="bairro">
                            <option selected>Selecione o bairro...</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="quartos" class="form-label">Quartos</label>
                        <select class="form-select form-select-lg" name="quartos" id="quartos">
                            <option selected>Selecione número de quartos...</option>
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
                            <option selected>Selecione o tipo de imóvel...</option>
                            <option value="casaComercial">Casa comercial</option>
                            <option value="aptoComercial">Apartamento comercial</option>
                            <option value="casaResidencial">Casa residencial</option>
                            <option value="aptoResidencial">Apartamento residencial</option>
                            <option value="kitnet">KitNet</option>
                        </select>
                    </div>
                </div>
            </div>
            <h5>Você prefere:</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="compraoualuga" id="compra" />
                <label class="form-check-label" for="compra">Compra</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="compraoualuga" id="aluguel" />
                <label class="form-check-label" for="aluguel">Aluguel</label>
            </div>
            <div class="btn-filtro-container">
            <input type="submit" class="btn btn-primary" value="Enviar">
            </div>
        </div>
    </form>

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

</main>

<script>
    function selecionarBairros() {
        const cidade = document.getElementById("cidade").value;

        if (cidade) {
            fetch(`?cidade=${cidade}`)//faz requisicao http pro servidor buscar o arquivo selecionarBairros.php
                .then(response => response.text())//then lida com a resposta do feth transformando em php dnv
                .then(data => {
                    document.getElementById("bairro").innerHTML = '<option value="">Selecione o bairro...</option>' + data;
                })//.then() processa o texto recebido, chamando uma função que usa o data (conteúdo HTML da resposta) para atualizar o select de bairros.
                .catch(error => console.error("Erro ao carregar bairros:", error));
        } else {
            document.getElementById("bairro").innerHTML = '<option value="">Selecione o bairro...</option>';
        }
    }
</script>

</body>
</html>