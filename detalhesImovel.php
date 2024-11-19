<?php
        $host = 'localhost';
        $user = 'root';
        $password = '12345';
        $database = 'cianman';
        $mysqli = new mysqli($host, $user, $password, $database);

        if ($mysqli->connect_error) {
            die('Erro de conexão: ' . $mysqli->connect_error);
        }

    foreach ($fotos as $url){
        echo "<img src='" . htmlspecialchars($url) . "' alt='Foto do Imóvel' style='width: 400px; height: 300px; margin: 5px;'><br>";}
        echo "Bairro: " . htmlspecialchars($imovel['bairro']) . "<br>";
        echo "Cidade: " . htmlspecialchars($imovel['cidade']) . "<br>";
        echo "CEP: " . htmlspecialchars($imovel['cep']) . "<br>";
        echo "Tamanho: " . htmlspecialchars($imovel['tamanho']) . " m²<br>";
        echo "Quartos: " . htmlspecialchars($imovel['numQuartos']) . "<br>";
        echo "Tipo: " . htmlspecialchars($imovel['tipo']) . "<br>";
        echo "Compra/Aluguel: " . htmlspecialchars($imovel['compraAluga']) . "<br>";
        echo "Valor: R$ " . number_format($imovel['valor'], 2, ',', '.') . "<br>";
        ?>
        
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Imóvel - detalhes</title>
    </head>
    <body>
            
    </body>
    </html>
    <form method="POST" action="src/controller/editarImovelController.php">
    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
    <button type="submit" class="btn btn-primary">Editar imóvel</button>
    
    <form method="POST" action="src/controller/ExcluirImovelController.php">
    <input type="hidden" name="id" value="<?php echo $imovel['id']; ?>">
    <button type="submit" class="btn btn-primary">Excluir imóvel</button>
    
</form>
