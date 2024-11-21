<?php
session_start();

function conectarBanco() {
    $host = 'localhost';
    $user = 'root';
    $pwd = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    if ($mysqli->connect_error) {
        die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
    }

    return $mysqli;
}

function obterDadosUsuario($email) {
    $mysqli = conectarBanco();

    $stmt = $mysqli->prepare("SELECT * FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $stmt->close();
    $mysqli->close();

    return $usuario;
}

function atualizarDadosUsuario($campo, $valor, $email) {
    $mysqli = conectarBanco();

    $sql = "UPDATE clientes SET $campo = ? WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $valor, $email);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    return true;
}

if (!isset($_SESSION['email'])) {
    header('Location: ../../login.php');
    exit;
}

$email = $_SESSION['email'];

$cliente = obterDadosUsuario($email);

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de que todos os campos do formulário estão sendo processados corretamente
    $nome = $_POST['nome'];
    $emailNovo = $_POST['email'];  // Caso queira atualizar o email também
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $dataNasc = $_POST['dataNasc'];
    $senha = $_POST['senha'];

    // Atualize cada campo individualmente no banco de dados
    atualizarDadosUsuario('nome', $nome, $email);
    atualizarDadosUsuario('email', $emailNovo, $email);
    atualizarDadosUsuario('cpf', $cpf, $email);
    atualizarDadosUsuario('telefone', $telefone, $email);
    atualizarDadosUsuario('cep', $cep, $email);
    atualizarDadosUsuario('dataNasc', $dataNasc, $email);
    atualizarDadosUsuario('senha', $senha, $email);

    // Atualize os dados do cliente na sessão
    $_SESSION['cliente'] = obterDadosUsuario($emailNovo);

    // Redirecione para a página de perfil com código de sucesso
    header('Location: ../../perfilUsuario?cod=300');
    exit; // Stop further script execution
}
?>
