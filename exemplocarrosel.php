<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$query = "SELECT id, url, valor FROM imoveis LIMIT 30";
$result = $mysqli->query($query);

$imoveis = [];
while ($imovel = $result->fetch_assoc()) {
    $imoveis[] = $imovel;
}

$chunkedImoveis = array_chunk($imoveis, 6); // divide array de imoveis em subarrays
$mysqli->close();
?>

<!doctype html>
<html lang="en">
<head>
    <title>Imóveis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<body>

<style>
        /* Padroniza o tamanho dos cards e imagens */
        .imovel-card {
            height: 300px;
            overflow: hidden;
            text-align: center;
        }

        .imovel-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .imovel-card .card-body {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

<div class="container mt-4">
    <?php foreach ($chunkedImoveis as $imovelGroup): ?>
        <div class="row mb-4">
            <?php foreach ($imovelGroup as $imovel): ?>
                <div class="col-md-4 mb-3">
                    <div class="card imovel-card">
                        <a href="imovel.php?id=<?= $imovel['id'] ?>">
                            <img src="<?= $imovel['url'] ?>" class="card-img-top" alt="Imagem do Imóvel">
                        </a>
                        <div class="card-body">
                            <p class="card-text">Valor: R$ <?= number_format($imovel['valor'], 2, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
