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

// Consulta ao banco de dados para obter os detalhes do imóvel (ajuste conforme sua tabela e critérios)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Corrigido: Usando a variável diretamente na consulta sem prepare, pois não há necessidade de bind_param
$query = "SELECT * FROM imoveis WHERE id = $id";
$result = $mysqli->query($query);

// Verifique se o imóvel foi encontrado
if ($result && $result->num_rows > 0) {
    $imovel = $result->fetch_assoc();
    $url = $imovel['url'];  // Certifique-se de que a URL da foto está sendo recuperada corretamente
} else {
    echo "Imóvel não encontrado.";
    exit;
}

?>


    <style>
        .button-container {
            display: flex;
            gap: 10px; /* Espaço entre os botões */
        }

        .button-container form {
             margin: 0;
        }

    </style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes - imóvel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/controller/styleDetalhesImovel.php" type="text/css" rel="stylesheet" >

</head>
<body>
    

    <h1> DETALHES DO IMÓVEL</h1>
    <img src="<?php echo htmlspecialchars($url); ?>" alt="Foto do Imóvel" style="width: 400px; height: 300px; margin: 5px;"><br>
    <p><strong>Bairro:</strong> <?php echo htmlspecialchars($imovel['bairro']); ?></p>
    <p><strong> Cidade: </strong><?php echo htmlspecialchars($imovel['cidade']); ?></p>
    <p><strong>CEP: </strong><?php echo htmlspecialchars($imovel['cep']); ?></p>
    <p><strong>Tamanho: </strong><?php echo htmlspecialchars($imovel['tamanho']); ?> m²</p>
    <p> <strong>Quartos:</strong> <?php echo htmlspecialchars($imovel['numQuartos']); ?></p>
    <p><strong>Tipo:</strong>  <?php echo htmlspecialchars($imovel['tipo']); ?></p>
    <p><strong>Compra/Aluguel:</strong>  <?php echo htmlspecialchars($imovel['compraAluga']); ?></p>
    <p><strong> Valor: </strong> R$ <?php echo number_format($imovel['valor'], 2, ',', '.'); ?></p>
    
    <div class="button-container">

    <form method="POST" action="src/controller/editarImovelController.php">
        <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
        <button type="submit" class="btn btn-editar">Editar imóvel</button>
    </form>

    <form method="POST" action="src/controller/excluirImovelController.php">
    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
    <button type="submit" class="btn btn-danger">Excluir imóvel</button>
</form>
    </div>

    </body>
</html>


