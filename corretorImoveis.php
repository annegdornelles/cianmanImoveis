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

        <!-- Botão para visualizar imóveis -->
        <form method="GET">
            <button type="submit" class="btn btn-primary" name="visualizar" value="1">Visualizar Imóveis</button>
        </form>

        <!-- Botão para exibir o formulário de adicionar imóvel -->
        <button class="btn btn-success my-3" onclick="document.getElementById('formAdicionar').style.display='block'">Adicionar Novo Imóvel</button>

        <?php
        // Conexão com o banco de dados
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'cianman';

        $mysqli = new mysqli($host, $user, $password, $database);

        if ($mysqli->connect_error) {
            die('Erro de conexão: ' . $mysqli->connect_error);
        }

        // Função para exibir os imóveis
        function visualizarImoveis($mysqli) {
            $query = 'SELECT id, url, cidade, bairro, valor FROM imoveis WHERE funcionariosId="'.$funcionarioId.'"';
            $result = $mysqli->query($query);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($imovel = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($imovel['cidade']) . "</strong><br>";
                    echo "Descrição: " . htmlspecialchars($imovel['bairro']) . "<br>";
                    echo "Preço: R$ " . number_format($imovel['valor'], 2, ',', '.') . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "Nenhum imóvel encontrado.";
            }
        }

        // Função para adicionar um novo imóvel
        function adicionarImovel($mysqli) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
                $cidade = $_POST['cidade'];
                $descricao = $_POST['bairro'];
                $preco = $_POST['valor'];

                // Validação básica
                if (empty($titulo) || empty($descricao) || empty($preco)) {
                    echo "<div class='alert alert-danger'>Todos os campos são obrigatórios.</div>";
                } else {
                    // Insere o imóvel no banco
                    $stmt = $mysqli->prepare("INSERT INTO imoveis (cidade, bairro, valor) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssd", $titulo, $descricao, $preco);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Imóvel adicionado com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao adicionar imóvel: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                }
            }
        }

        // Verifica se o botão de visualizar foi clicado
        if (isset($_GET['visualizar']) && $_GET['visualizar'] == '1') {
            visualizarImoveis($mysqli);
        }

        // Chama a função de adicionar imóvel
        adicionarImovel($mysqli);

        // Fecha a conexão com o banco
        $mysqli->close();
        ?>

        <!-- Formulário de adicionar imóvel -->
        <div id="formAdicionar" style="display: none;">
            <h2>Adicionar Novo Imóvel</h2>
            <form method="POST" class="my-3">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="preco" class="form-label">Preço (R$)</label>
                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-success" name="adicionar">Salvar Imóvel</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('formAdicionar').style.display='none'">Cancelar</button>
            </form>
        </div>
    </div>
</body>
</html>