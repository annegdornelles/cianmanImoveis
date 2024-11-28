<?php

require_once __DIR__ . '\..\model\conexaomysql.php';

if ($_POST) {
    $id = $_POST['id'];
    echo "
    <div style='background-color: #ffcccc; color: #990000; padding: 20px; border: 2px solid #990000; border-radius: 5px; margin-bottom: 20px; font-family: Arial, sans-serif;'>
        <strong>ATENÇÃO!</strong> Ao apertar o botão, o imóvel será <strong>excluído permanentemente!</strong>
    </div>
    <form method='POST'>
        <input type='hidden' name='excluir' value='excluir'>
        <input type='hidden' name='id' value='$id'>
        <button type='submit' class='btn btn-danger' style='background-color: #990000; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;'>
            EXCLUIR
        </button>
    </form>";
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

    header('Location: ../../corretorImoveis.php');
    exit;
}

$mysqli->close();

?>
