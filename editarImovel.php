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

function obterImagensImovel($id) {
    $mysqli = conectarBanco();
    $stmt = $mysqli->prepare("SELECT * FROM imagens WHERE imovelId = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $imagens = [];
    
    while ($row = $result->fetch_assoc()) {
        $imagens[] = $row;
    }

    $stmt->close();
    $mysqli->close();

    return $imagens;
}

function atualizarDadosImovel($campo, $valor, $id) {
    $mysqli = conectarBanco();
    $stmt = $mysqli->prepare("UPDATE imoveis SET $campo = ? WHERE id = ?");
    $stmt->bind_param("si", $valor, $id);
    $stmt->execute();
    
    $stmt->close();
    $mysqli->close();
}

function salvarImagens($imovelId, $imagens, $descricoes) {
    $mysqli = conectarBanco();

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // loop para processar as imagens enviadas
    foreach ($imagens['tmp_name'] as $index => $tmpName) {
        $fileName = uniqid() . '_' . basename($imagens['name'][$index]);
        $filePath = $uploadDir . $fileName;

        $fileType = mime_content_type($tmpName);
        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            if (move_uploaded_file($tmpName, $filePath)) {
                
                $descricao = $descricoes[$index];
                $stmt = $mysqli->prepare("INSERT INTO imagens (imovelId, descricao, link) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $imovelId, $descricao, $filePath);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<p>Erro ao mover o arquivo {$fileName}.</p>";
            }
        } else {
            echo "<p>Arquivo {$fileName} tem um formato inválido.</p>";
        }
    }

    $mysqli->close();
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_POST['salvar'])) {

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

        if (isset($_FILES['imagens']) && $_FILES['imagens']['error'][0] === UPLOAD_ERR_OK) {
            salvarImagens($id, $_FILES['imagens'], $_POST['descricao']);
        }

        $_SESSION['sucesso'] = true;
    }

    $imovel = obterDadosImovel($id);
    $imagens = obterImagensImovel($id);
} else {
    echo "<p>ID do imóvel não foi fornecido!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imóvel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="src/css/stylecadastro.css">
</head>
<style>
 .fa-arrow-left{
    color:#5e2b5c;
    font-size: 30px;
    text-align: left;
}

.fa-arrow-left:hover{
    color:#2e1b4e;
    font-size: 35px;
}

.arrow{
    text-align: left;
}
 
</style>
<body>

<div class="container mt-5">
<a class="arrow" href="corretorImoveis.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
    <h2>Editar Imóvel</h2>

    <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
        <div class="alert alert-success" role="alert">
            Imóvel atualizado com sucesso!
        </div>
        <a href="corretorImoveis.php" style="text-align:center;">Volte para a página inicial</a>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>
    
    <form action="editarImovel.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($imovel['id']); ?>" />

        <div class="mb-3">
            <label for="bairro" class="form-label">Bairro:</label>
            <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo htmlspecialchars($imovel['bairro']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="cidade" class="form-label">Cidade:</label>
            <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($imovel['cidade']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="cep" class="form-label">CEP:</label>
            <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($imovel['cep']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="tamanho" class="form-label">Tamanho (m²):</label>
            <input type="number" class="form-control" id="tamanho" name="tamanho" value="<?php echo htmlspecialchars($imovel['tamanho']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="numQuartos" class="form-label">Número de Quartos:</label>
            <input type="number" class="form-control" id="numQuartos" name="numQuartos" min="0" value="<?php echo htmlspecialchars($imovel['numQuartos']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="Casa" <?php if ($imovel['tipo'] == 'Casa') echo 'selected'; ?>>Casa</option>
                <option value="Apartamento" <?php if ($imovel['tipo'] == 'Apartamento') echo 'selected'; ?>>Apartamento</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="compraAluga" class="form-label">Compra ou Aluguel:</label>
            <select class="form-control" id="compraAluga" name="compraAluga" required>
                <option value="Compra" <?php if ($imovel['compraAluga'] == 'Compra') echo 'selected'; ?>>Compra</option>
                <option value="Aluguel" <?php if ($imovel['compraAluga'] == 'Aluguel') echo 'selected'; ?>>Aluguel</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor:</label>
            <input type="number" class="form-control" id="valor" name="valor" value="<?php echo htmlspecialchars($imovel['valor']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="imagens" class="form-label">Imagens do Imóvel:</label>
            <input type="file" class="form-control" id="imagens" name="imagens[]" multiple>
        </div>

        <div class="mb-3">
            <label for="descricaoImagens" class="form-label">Descrição das Imagens:</label>
            <textarea class="form-control" id="descricaoImagens" name="descricao[]" rows="3" placeholder="Insira uma descrição para cada imagem."></textarea>
        </div>
        
        <div class="mb-3">
            <h4>Imagens Atuais:</h4>
            <div class="row">
                <?php foreach ($imagens as $imagem): ?>
                    <div class="col-3">
                        <img src="<?php echo $imagem['link']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($imagem['descricao']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <button type="submit" name="salvar" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
