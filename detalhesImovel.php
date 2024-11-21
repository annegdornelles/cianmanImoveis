<?php
$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'cianman';

// Conectar ao banco de dados
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Consulta ao banco de dados para obter os detalhes do imóvel
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM imoveis WHERE id = $id";
$result = $mysqli->query($query);

// Verifica se o imóvel foi encontrado
if ($result && $result->num_rows > 0) {
    $imovel = $result->fetch_assoc();
    $url = $imovel['url'];
} else {
    echo "<div class='alert alert-warning'>Imóvel não encontrado.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/css/styleDetalhesImovel.css" type="text/css" rel="stylesheet">
    <style>
        /* .button-container {
            display: flex;
            gap: 10px;
        }
        .btn-editar {
            background-color: #4a148c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-editar:hover {
            background-color: rgba(242, 96, 255, 0.634);
        }
        .btn-danger {
            padding: 10px 20px;
            border-radius: 5px;
        }
        strong {
            color: #4a148c;
        }
        img {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin: 20px 0;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #4a148c;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        .container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        } */
    </style>
</head>
<body>
    <div class="container">
        <h1>DETALHES DO IMÓVEL</h1>
        <div class="fotoImovel" >
        <img src="<?php echo htmlspecialchars($url); ?>" alt="Foto do Imóvel">
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
