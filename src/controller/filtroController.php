<?php

<<<<<<< HEAD
   if($_POST){
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $tipo = $_POST['tipo'];
    $quartos = $_POST['quartos'];
    $compraAluga = $_POST['compraoualuga'];
    
    $host = 'localhost';
    $user = 'root';
    $password = '12345';
    $database = 'cianman';
=======
if ($_POST) {
    $cidade = isset($_GET['cidade']) ? $_GET['cidade'] : null;
    $bairro = isset($_GET['bairro']) ? $_GET['bairro'] : null;
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
    $quartos = isset($_GET['quartos']) ? $_GET['quartos'] : null;
    $compraAluga = isset($_GET['compraoualuga']) ? $_GET['compraoualuga'] : null;
>>>>>>> 19faa84b5fe2e0f2424994a78097f69f2d9b6c3c

    header("location:../../filtro.php?cidade=$cidade&bairro=$bairro&tipo=$tipo&quartos=$quartos&compraoualuga=$compraAluga");
    exit();
}
