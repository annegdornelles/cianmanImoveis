<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

if ($_POST) {
    $id = $_POST['id'];
    echo 'ATENÇÃO! Ao apertar o botão o imóvel será excluído permanentemente!';
    echo "<form method='POST'><input type='hidden' name='excluir' value='excluir'><input type='hidden' name='id' value='$id'><button type='submit' class='btn btn-danger'>EXCLUIR</button></form>";
}

if (isset($_POST['excluir']) && isset($_POST['id'])) {
    $imovelId = (int) $_POST['id'];

    // 1. Excluir as imagens associadas ao imóvel
    $queryExcluirImagens = "DELETE FROM imagens WHERE imovelId = ?";
    $stmtExcluirImagens = $mysqli->prepare($queryExcluirImagens);
    $stmtExcluirImagens->bind_param('i', $imovelId);
    if (!$stmtExcluirImagens->execute()) {
        echo "Erro ao excluir as imagens.";
    }

    // 2. Excluir o imóvel
    $queryExcluirImovel = "DELETE FROM imoveis WHERE id = ?";
    $stmtExcluirImovel = $mysqli->prepare($queryExcluirImovel);
    $stmtExcluirImovel->bind_param('i', $imovelId);

    if ($stmtExcluirImovel->execute()) {
        echo "Imóvel excluído com sucesso!";
    } else {
        echo "Erro ao excluir o imóvel.";
    }

    // Redireciona após a exclusão
    header('Location: ../../corretorImoveis.php');
    exit;
}

//COLOCAR AVISO

$mysqli->close();

?>
