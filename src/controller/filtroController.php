<?php

   if($_POST){
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $tipo = $_POST['tipo'];
    $quartos = $_POST['quartos'];
    $compraAluga = $_POST['compraoualuga'];
    
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_errno) {
        echo 'Erro ao conectar no banco de dados: ' . $mysqli->connect_error;
        return 0;
    }

    $sql = 'SELECT * FROM imoveis WHERE cidade = "' . $cidade . '" AND bairro = "' . $bairro . '"AND tipo = "'.$tipo.'" AND quartos ="'.$quartos.'" AND compraAluga = "'.$compraAluga;'"';
    $result = $mysqli->query($sql);


   }


?>