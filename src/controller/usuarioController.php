<?php

function usuarioInsert(){

    require_once './cadastroController.php';
    
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    //Cria uma variável para retornar o array da consulta.
    $result = null;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        //construi minha consulta sql e armazenei na var. $sql.
        //INSERT INTO `sgp`.`projetos` (`nome`, `status`) VALUES ('Projeto A', 'Ativo');
        $sql = 'INSERT INTO projetos VALUES (0,"' . $nome . '","' . $status . '")';
        //variavel do tipo array associativo que recebe o resultado da consulta sql.
        $result = $mysqli->query($sql);

        $id = $mysqli->insert_id; //retorna o ultimo registro inserido na base de dados.

        //fecha a conexão
        $mysqli->close();
    }

    return   $id;
}

function usersLogin(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    //Cria uma variável para retornar o array da consulta.
    $result = null;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        //construi minha consulta sql e armazenei na var. $sql.
        //$sql = 'SELECT * FROM sgp.usuarios where email="m@m" and senha="123"';
        $sql = 'SELECT * FROM usuarios WHERE email="'.$email.'" AND senha="'.$senha.'"';
        //variavel do tipo array associativo que recebe o resultado da consulta sql.
        $result = $mysqli->query($sql);

        //fecha a conexão
        $mysqli->close();
    }
    return $result->num_rows;//retorna o número de registros selecionados pela consulta sql.
}

function usersUpdate(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $password, $database);

    //Cria uma variável para retornar o array da consulta.
    $result = null;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        //construi minha consulta sql e armazenei na var. $sql.
        //$sql = 'SELECT * FROM sgp.usuarios where email="m@m" and senha="123"';
        $sql = 'UPDATE clientes SET nome="' . $nome . '",status="' . $status . '" WHERE id=' . $id;
        //variavel do tipo array associativo que recebe o resultado da consulta sql.
        $result = $mysqli->query($sql);

        //fecha a conexão
        $mysqli->close();

}

?>