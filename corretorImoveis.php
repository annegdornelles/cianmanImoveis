<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1>Lista de Imóveis</h1>

        <form method="GET">
            <button type="submit" class="btn btn-primary" name="visualizar" value="1">Visualizar Meus Imóveis</button>
            <button type="submit" class="btn btn-success" name="adicionar" value="1">Adicionar Novo Imóvel</button>
        </form>

        <?php
      
        $host = 'localhost';
        $user = 'root';
        $password = '12345';
        $database = 'cianman';

        $mysqli = new mysqli($host, $user, $password, $database);

        if ($mysqli->connect_error) {
            die('Erro de conexão: ' . $mysqli->connect_error);
        }

        $funcionariosId = 1;

        function visualizarImoveis($mysqli, $funcionariosId) {
            $query = "SELECT * FROM imoveis WHERE funcionariosId = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $funcionariosId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($imovel = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($imovel['logradouro']) . ", " . htmlspecialchars($imovel['numCasa']) . "</strong><br>";
                    if (!empty($imovel['fotos'])) {
                        $fotos = explode(',', $imovel['fotos']);
                        foreach ($fotos as $foto) {
                            echo "<img src='" . htmlspecialchars($foto) . "' alt='Foto do Imóvel' style='width: 150px; margin: 5px;'><br>";
                        }
                    }
                    echo "Bairro: " . htmlspecialchars($imovel['bairro']) . "<br>";
                    echo "Cidade: " . htmlspecialchars($imovel['cidade']) . "<br>";
                    echo "CEP: " . htmlspecialchars($imovel['cep']) . "<br>";
                    echo "Tamanho: " . htmlspecialchars($imovel['tamanho']) . " m²<br>";
                    echo "Quartos: " . htmlspecialchars($imovel['numQuartos']) . "<br>";
                    echo "Tipo: " . htmlspecialchars($imovel['tipo']) . "<br>";
                    echo "Compra/Aluguel: " . htmlspecialchars($imovel['compraAluga']) . "<br>";
                    echo "Valor: R$ " . number_format($imovel['valor'], 2, ',', '.') . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<div class='alert alert-warning'>Nenhum imóvel encontrado para este corretor.</div>";
            }
        }

        function adicionarImovel($mysqli, $funcionariosId) {
            if ($_POST && isset($_POST['adicionar'])) {
                $logradouro = $_POST['logradouro'];
                $numCasa = $_POST['numCasa'];
                $bairro = $_POST['bairro'];
                $cidade = $_POST['cidade'];
                $cep = $_POST['cep'];
                $tamanho = $_POST['tamanho'];
                $numQuartos = $_POST['numQuartos'];
                $tipo = $_POST['tipo'];
                $compraAluga = $_POST['compraAluga'];
                $valor = $_POST['valor'];

      /*          echo "<pre>";
var_dump($_POST['tipo']);
echo "</pre>";*/

                // Tratamento de upload de imagens
                $fotos = [];
                if (isset($_FILES['fotos']) && $_FILES['fotos']['error'][0] == 0) {
                    $uploadDir = __DIR__ . '/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                        $fileName = uniqid() . '_' . basename($_FILES['fotos']['name'][$index]);
                        $filePath = $uploadDir . $fileName;

                        $fileType = mime_content_type($tmpName);
                        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                            if (move_uploaded_file($tmpName, $filePath)) {
                                $fotos[] = 'uploads/' . $fileName;
                            }
                        }
                    }
                }

                $fotosPath = implode(',', $fotos);

                if (empty($logradouro) || empty($numCasa) || empty($bairro) || empty($cidade) || empty($cep) || empty($valor)) {
                    echo "<div class='alert alert-danger'>Todos os campos são obrigatórios.</div>";
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO imoveis (funcionariosId, logradouro, numCasa, bairro, cidade, cep, tamanho, numQuartos, tipo, compraAluga, valor, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssssdiisss", $funcionariosId, $logradouro, $numCasa, $bairro, $cidade, $cep, $tamanho, $numQuartos, $tipo, $compraAluga, $valor, $fotosPath);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Imóvel adicionado com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao adicionar imóvel: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                }
            }
        }

        // Lógica principal
        if (isset($_GET['visualizar']) && $_GET['visualizar'] == '1') {
            visualizarImoveis($mysqli, $funcionariosId);
        } elseif (isset($_GET['adicionar']) && $_GET['adicionar'] == '1') {
            ?>
            <h2>Adicionar Novo Imóvel</h2>
            <form method="POST" class="my-3" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="logradouro" class="form-label">Logradouro</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                </div>
                <div class="mb-3">
                    <label for="numCasa" class="form-label">Número da Casa</label>
                    <input type="text" class="form-control" id="numCasa" name="numCasa" required>
                </div>
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" required>
                </div>
                <div class="mb-3">
                    <label for="tamanho" class="form-label">Tamanho (m²)</label>
                    <input type="number" class="form-control" id="tamanho" name="tamanho" required>
                </div>
                <div class="mb-3">
                    <label for="numQuartos" class="form-label">Número de Quartos</label>
                    <input type="number" class="form-control" id="numQuartos" name="numQuartos" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" class="form-control" id="tipo" name="tipo" required>
                </div>
                <div class="mb-3">
                    <label for="compraAluga" class="form-label">Compra ou Aluguel</label>
                    <select class="form-control" id="compraAluga" name="compraAluga" required>
                        <option value="Compra">Compra</option>
                        <option value="Aluguel">Aluguel</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                </div>
                <div class="mb-3">
                    <label for="fotos" class="form-label">Fotos</label>
                    <input type="file" class="form-control" id="fotos" name="fotos[]" multiple>
                </div>
                <button type="submit" class="btn btn-success" name="adicionar">Salvar Imóvel</button>
            </form>
            <?php
            adicionarImovel($mysqli, $funcionariosId);
        }

        $mysqli->close();
        ?>

    </div>
</body>
</html>
