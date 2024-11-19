<?php


    $host = 'localhost';
    $user = 'root';
    $pwd = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    if ($mysqli->connect_error) {
        die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
    }

    return $mysqli;

    foreach ($fotos as $url){
        echo "<img src='" . htmlspecialchars($url) . "' alt='Foto do Imóvel' style='width: 400px; height: 300px; margin: 5px;'> <a Alterar imagem></a><br>";}
        echo "Bairro: " . htmlspecialchars($imovel['bairro']) . "<a Alterar bairro></a><br>";
        echo "Cidade: " . htmlspecialchars($imovel['cidade']) . "<a Alterar cidade></a><br>";
        echo "CEP: " . htmlspecialchars($imovel['cep']) . "<a Alterar CEP></a><br>";
        echo "Tamanho: " . htmlspecialchars($imovel['tamanho']) . " m² <a Alterar tamanho></a><br>";
        echo "Quartos: " . htmlspecialchars($imovel['numQuartos']) . "<a Alterar nº de quartos></a><br>";
        echo "Tipo: " . htmlspecialchars($imovel['tipo']) . "<a Alterar tipo></a><br>";
        echo "Compra/Aluguel: " . htmlspecialchars($imovel['compraAluga']) . "<a Alterar></a><br>";
        echo "Valor: R$ " . number_format($imovel['valor'], 2, ',', '.') . "<a <?php UPDATE imoveis SET bairro = 'novoBairro' WHERE funcionariosId = ? ?>> alterar valor</a><br>";

?>
