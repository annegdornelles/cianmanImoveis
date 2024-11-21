<?php
session_start();

function conectarBanco() {
    $host = 'localhost';
    $user = 'root';
    $pwd = '12345';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    if ($mysqli->connect_error) {
        die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
    }

    return $mysqli;
}

function obterDadosImovel($id) {
    $mysqli = conectarBanco();

    $stmt = $mysqli->prepare("SELECT * FROM imoveis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $imovel = $result->fetch_assoc();

    $stmt->close();
    $mysqli->close();

    return $imovel;
}

function atualizarDadosImovel($campo, $valor, $id) {
    $mysqli = conectarBanco();

    $stmt = $mysqli->prepare("UPDATE imoveis SET $campo = ? WHERE id = ?");
    $stmt->bind_param("si", $valor, $id);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

    return true;
}

function salvarFotos($id, $fotos) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fotosCaminho = [];
    foreach ($fotos['tmp_name'] as $index => $tmpName) {
        $fileName = uniqid() . '_' . basename($fotos['name'][$index]);
        $filePath = $uploadDir . $fileName;
        $fileType = mime_content_type($tmpName);

        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            if (move_uploaded_file($tmpName, $filePath)) {
                $fotosCaminho[] = 'uploads/' . $fileName;
            } else {
                echo "<p>Erro ao mover o arquivo {$fileName}.</p>";
            }
        } else {
            echo "<p>Arquivo {$fileName} tem um formato inválido.</p>";
        }
    }

    if (!empty($fotosCaminho)) {
        $mysqli = conectarBanco();
        $fotosString = implode(',', $fotosCaminho);
        $stmt = $mysqli->prepare("UPDATE imoveis SET url = ? WHERE id = ?");
        $stmt->bind_param("si", $fotosString, $id);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}

// if (!isset($_SESSION['id'])) {
//     header("Location: ../../index.php");
//     exit;
// }

$id = $_SESSION['id'];
$imovel = obterDadosImovel($id);

if ($_POST) {
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $cep = $_POST['cep'];
    $tamanho = $_POST['tamanho'];
    $numQuartos = $_POST['numQuartos'];
    $tipo = $_POST['tipo'];
    $compraAluga = $_POST['compraAluga'];
    $valor = $_POST['valor'];

    atualizarDadosImovel('bairro', $bairro, $id);
    atualizarDadosImovel('cidade', $cidade, $id);
    atualizarDadosImovel('cep', $cep, $id);
    atualizarDadosImovel('tamanho', $tamanho, $id);
    atualizarDadosImovel('numQuartos', $numQuartos, $id);
    atualizarDadosImovel('tipo', $tipo, $id);
    atualizarDadosImovel('compraAluga', $compraAluga, $id);
    atualizarDadosImovel('valor', $valor, $id);

    if (isset($_FILES['fotos']) && $_FILES['fotos']['error'][0] === UPLOAD_ERR_OK) {
        salvarFotos($id, $_FILES['fotos']);
    }

    header("Location: ../../detalhesImovel?id=$id&cod=300");
    exit;
}
?>
