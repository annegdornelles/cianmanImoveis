<?php

function usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc){

    $host = 'localhost';
    $user = 'root';
    $pwd = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    // Variável para retornar o ID do último registro inserido
    $id = null;

    if (mysqli_connect_errno()) {
        echo 'Erro ao conectar no banco de dados.';
    } else {
        echo 'Conexão realizada com sucesso.';
        // Construindo a consulta SQL
        $sql = 'INSERT INTO projetos VALUES (0,"' . $cpf . '","' . $nome . '","'. $cep . '","'. $email . '", "'.$dataNasc.'","'.$senha.'", "'. $telefone.'");';

        // Executando a consulta
        $mysqli->query($sql);

        // Obtendo o ID do último registro inserido
        $id = $mysqli->insert_id;

        // Fechando a conexão
        $mysqli->close();
    }

    return $id;
}

// Verifica se há dados enviados via POST antes de chamar a função
if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $cep = $_POST['cep'];
    $senha = $_POST['senha'];
    $dataNasc = $_POST['dataNasc'];

    // Chama a função e passa os parâmetros
    $id = usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc);
}
?>
