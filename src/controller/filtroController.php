<?php

if ($_POST) {
    $cidade = isset($_GET['cidade']) ? $_GET['cidade'] : null;
    $bairro = isset($_GET['bairro']) ? $_GET['bairro'] : null;
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
    $quartos = isset($_GET['quartos']) ? $_GET['quartos'] : null;
    $compraAluga = isset($_GET['compraoualuga']) ? $_GET['compraoualuga'] : null;

    header("location:../../filtro.php?cidade=$cidade&bairro=$bairro&tipo=$tipo&quartos=$quartos&compraoualuga=$compraAluga");
    exit();
}
