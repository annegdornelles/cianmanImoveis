<?php
session_start(); // Inicia a sessão

// Verifica se a sessão de favoritos já foi criada; caso contrário, cria um array vazio
if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = array();
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário
    $id = (int)$_POST['id'];
    $preco = (float)$_POST['valor'];
    $url = (string)$_POST['url'];

    // Verifica se o imóvel já está nos favoritos
    $encontrado = false;
    foreach ($_SESSION['favoritos'] as $favorito) {
        if ($favorito['id'] == $id) {
            $encontrado = true;
            echo "Este imóvel já está nos seus favoritos.<br>";
            break;
        }
    }

    // Se o imóvel não estiver nos favoritos, adiciona-o
    if (!$encontrado) {
        $_SESSION['favoritos'][] = [
            'id' => $id,
            'valor' => $preco,
            'url' => $url,
        ];
        echo "Imóvel $id foi adicionado aos favoritos!<br>";
    }
}
?>

<!-- Link para visualizar os favoritos -->
<a href="favoritar.php?visualizar=true">Visualizar Lista de Favoritos</a>

<?php
// Exibe a lista de favoritos se o parâmetro visualizar for passado
if (isset($_GET['visualizar']) && $_GET['visualizar'] == 'true') {
    if (!empty($_SESSION['favoritos'])) {
        echo "<h2>Lista de Favoritos:</h2><ul>";
        foreach ($_SESSION['favoritos'] as $imovel) {
            echo "<li>Imóvel ID: " . htmlspecialchars($imovel['id']) . " - Valor: R$" . number_format($imovel['valor'], 2, ',', '.') . 
     "<br><img src='" . $imovel['url'] . "' alt='Imagem do Imóvel' width='100' height='75'></li>";
        }
        echo "</ul>";
    } else {
        echo "Você não possui imóveis favoritos.";
    }
}
?>
