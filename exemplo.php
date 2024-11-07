<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

// Verifica a conexão com o banco de dados
if ($mysqli->connect_errno) {
    echo 'Erro ao conectar no banco de dados: ' . $mysqli->connect_error;
    exit();
}

// Consulta para obter o caminho da imagem
$sql = 'SELECT url FROM imoveis WHERE id = 1'; // Modifique o ID conforme necessário
$result = $mysqli->query($sql);

$img = null;
if ($result && $row = $result->fetch_assoc()) {
    $img = $row['url']; // Armazena o caminho da imagem
} else {
    echo 'Imagem não encontrada.';
}

// Fecha a conexão com o banco
$mysqli->close();
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Imagem do Imóvel</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS (Opcional) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    </head>

    <body>
        <main class="container mt-5">
            <?php if ($img): ?>
                <!-- Exibe a imagem -->
                <img src="<?php echo $img; ?>" class="img-fluid" alt="Imagem do Imóvel">
            <?php else: ?>
                <p>Imagem não encontrada.</p>
            <?php endif; ?>
        </main>

        <!-- Bootstrap JS (Opcional) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
