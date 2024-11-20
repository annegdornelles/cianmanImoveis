<?php
session_start(); // Inicia a sessão para acessar variáveis armazenadas

if (!isset($_SESSION['email'])) {
    echo "Usuário não autenticado.";
    exit();
}

// Recupera o e-mail da sessão
$email = $_SESSION['email'];

// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

// Consulta para buscar o ID do funcionário com base no e-mail
$stmt = $mysqli->prepare("SELECT id FROM funcionarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $funcionariosId = $row['id']; // ID do funcionário correspondente ao e-mail
    echo "Funcionário logado! ID: " . $funcionariosId;
} else {
    echo "Nenhum funcionário encontrado com o e-mail fornecido.";
}

$stmt->close();
$mysqli->close();
?>
