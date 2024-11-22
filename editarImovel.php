<?php
session_start();

// Função para conectar ao banco de dados
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

// Função para obter os dados do imóvel
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

// Função para obter as imagens relacionadas ao imóvel
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

// Função para atualizar os dados do imóvel
function atualizarDadosImovel($campo, $valor, $id) {
    $mysqli = conectarBanco();
    $stmt = $mysqli->prepare("UPDATE imoveis SET $campo = ? WHERE id = ?");
    $stmt->bind_param("si", $valor, $id);
    $stmt->execute();
    
    $stmt->close();
    $mysqli->close();
}

// Função para salvar as novas imagens no banco de dados
function salvarImagens($imovelId, $imagens) {
    $mysqli = conectarBanco();

    // Caminho para salvar as imagens
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Loop para processar as imagens enviadas
    foreach ($imagens['tmp_name'] as $index => $tmpName) {
        $fileName = uniqid() . '_' . basename($imagens['name'][$index]);
        $filePath = $uploadDir . $fileName;

        // Validação do tipo de arquivo
        $fileType = mime_content_type($tmpName);
        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            if (move_uploaded_file($tmpName, $filePath)) {
                // Inserir caminho da imagem no banco de dados
                $stmt = $mysqli->prepare("INSERT INTO imagens (imovelId, descricao) VALUES (?, ?)");
                $stmt->bind_param("is", $imovelId, $filePath);
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

// Verifica se o ID do imóvel foi passado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Verifica se o formulário foi enviado
    if (isset($_POST['salvar'])) {
        // Obtém os dados do formulário
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $cep = $_POST['cep'];
        $tamanho = $_POST['tamanho'];
        $numQuartos = $_POST['numQuartos'];
        $tipo = $_POST['tipo'];
        $compraAluga = $_POST['compraAluga'];
        $valor = $_POST['valor'];

        // Atualiza os dados do imóvel no banco de dados
        atualizarDadosImovel('bairro', $bairro, $id);
        atualizarDadosImovel('cidade', $cidade, $id);
        atualizarDadosImovel('cep', $cep, $id);
        atualizarDadosImovel('tamanho', $tamanho, $id);
        atualizarDadosImovel('numQuartos', $numQuartos, $id);
        atualizarDadosImovel('tipo', $tipo, $id);
        atualizarDadosImovel('compraAluga', $compraAluga, $id);
        atualizarDadosImovel('valor', $valor, $id);

        // Verifica se foram enviadas novas imagens
        if (isset($_FILES['imagens']) && $_FILES['imagens']['error'][0] === UPLOAD_ERR_OK) {
            salvarImagens($id, $_FILES['imagens']);
        }

        // Mensagem de sucesso
        $_SESSION['sucesso'] = true; // Flag para exibir mensagem de sucesso
    }

    // Obtém os dados atuais do imóvel e imagens para preencher o formulário
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
    <link rel="stylesheet" type="text/css" href="src/css/stylecadastro.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Imóvel</h2>
    
    <!-- Exibir mensagem de sucesso -->
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
            <input type="number" class="form-control" id="numQuartos" name="numQuartos" value="<?php echo htmlspecialchars($imovel['numQuartos']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($imovel['tipo']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="compraAluga" class="form-label">Compra ou Aluga:</label>
            <select class="form-select" id="compraAluga" name="compraAluga" required>
                <option value="compra" <?php echo ($imovel['compraAluga'] == 'compra') ? 'selected' : ''; ?>>Compra</option>
                <option value="aluga" <?php echo ($imovel['compraAluga'] == 'aluga') ? 'selected' : ''; ?>>Aluga</option>
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

        <!-- Exibição das imagens existentes -->
        <div class="mb-3">
            <h4>Imagens Atuais:</h4>
            <div class="row">
                <?php foreach ($imagens as $imagem): ?>
                    <div class="col-3">
                        <img src="<?php echo $imagem['link']; ?>" class="img-fluid" alt="Imagem do imóvel">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" name="salvar" class="btn btn-primary">Salvar</button>
    </form>
</div>

</body>
</html>
