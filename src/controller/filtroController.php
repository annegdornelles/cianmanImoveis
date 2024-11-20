<?php

   if($_POST){
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $tipo = $_POST['tipo'];
    $quartos = $_POST['quartos'];
    $compraAluga = $_POST['compraoualuga'];
    

    header("location:../../filtro.php?cidade=$cidade&bairro=$bairro&tipo=$tipo&quartos=$quartos&compraoualuga=$compraAluga");
    exit();
}
