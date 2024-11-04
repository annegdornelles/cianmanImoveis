<?php
    if($_POST) {
        require_once './Controller.php';

        //$users = usersLoadAll();

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        //foreach($users as $key => $value) {
            //if($value['email'] == $email && $value['password'] == $password) {
            if(usersLogin($email,$senha)>0){ 
                @session_start();
                $_SESSION['login'] = $email;
                header('location:\index.php');
                exit();
            }else{
                header('location:\index.php?cod=171');
            }
        //}
    }

function usersLogin(){
            $host = 'localhost';
            $user = 'root';
            $password = '12345';
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
        
?>