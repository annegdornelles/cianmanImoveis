<?php

    $host = 'localhost';
    $user = 'root';
    $pwd = '';//caso abra nos computadores do ctism, é necessario colocar senha 12345
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    // Verifica se a conexão foi bem-sucedida
    if ($mysqli->connect_error) {
        die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
    }