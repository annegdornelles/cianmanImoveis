<?php

$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'cianman';


$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}
$stmt->execute();

if($_POST){
    $id = $_POST['id'];
    echo 'ATENÇÃO! Ao apertar o botão o imóvel será excluído permanentemente!';
    echo "<form method='GET'><input type='hidden' name='excluir' value='excluir'><button type='submit' class='btn btn-danger'>EXCLUIR</button></form>";

    if (isset($_POST['excluir']) && isset($_POST['id'])) {
        $imovelId = (int) $_POST['id'];
    
        $queryExcluir = "DELETE FROM imoveis WHERE imovelId = ?";
        $stmtExcluir = $mysqli->prepare($queryExcluir);
        $stmtExcluir->bind_param('si', $imovelId);
    
        if ($stmtRemover->execute()) {
            echo "Imóvel excluído!";
        } else {
            echo "Erro ao excluir.";
        }
    
        header('Location: corretorImoveis.php');
        exit;
    }
}

    $mysqli->close();

?>
