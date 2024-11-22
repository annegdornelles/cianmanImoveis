<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imóveis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/css/styleCorretorImoveis.css" type="text/css" link rel="stylesheet">
    <nav>
</head>

<body>
    <div class="container my-4">

        <?php
        session_start();  // Inicia a sessão
        if (isset($_SESSION['mensagem_sucesso'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['mensagem_sucesso'] . "</div>";

            // Remove a mensagem da sessão após exibi-la para não mostrar em recarregamentos futuros
            unset($_SESSION['mensagem_sucesso']);
        }

        // Verifica se o usuário está logado e é um corretor
        if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'corretor') {
            header('Location: login.php');
            exit();
        }

        // Conexão com o banco de dados
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'cianman';
        $mysqli = new mysqli($host, $user, $password, $database);

        // Verifica a conexão com o banco
        if ($mysqli->connect_error) {
            die('Erro de conexão: ' . $mysqli->connect_error);
        }

       
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $query = "SELECT nome FROM funcionarios WHERE email = '$email'";
            $resultado = mysqli_query($mysqli, $query);

            if ($resultado) {
                $linha = mysqli_fetch_assoc($resultado);
                $nome = $linha['nome'];
                echo '<h1>Seja bem-vindo(a), corretor(a) ' . $nome . '</h1>';
            }
        }
        $stmt = $mysqli->prepare("SELECT id FROM funcionarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $funcionariosId = $row['id']; // ID do funcionário correspondente ao e-mail
        } else {
            echo "Nenhum funcionário encontrado com o e-mail fornecido.";
            exit();
        }
        
        $stmt->close();
        
       //verifica imoveis do carrinho
        $query = "
            SELECT 
            carrinho.imovelId, 
            imovel.cidade AS cidadeImovel, 
            imovel.logradouro AS enderecoImovel, 
            imovel.funcionariosId,
            carrinho.clienteCpf,
            cliente.nome AS nomeCliente,
            cliente.email AS emailCliente
        FROM carrinho
        INNER JOIN imoveis imovel ON carrinho.imovelId = imovel.id
        INNER JOIN clientes cliente ON carrinho.clienteCpf = cliente.cpf
        WHERE imovel.funcionariosId = ? ";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $funcionariosId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Exibe um aviso para cada imóvel adicionado ao carrinho
            while ($row = $result->fetch_assoc()) {
                $nomeCliente = htmlspecialchars($row['nomeCliente']);
                $emailCliente = htmlspecialchars($row['emailCliente']);
                $cidadeImovel = htmlspecialchars($row['cidadeImovel']);
                $enderecoImovel = htmlspecialchars($row['enderecoImovel']);
                
                echo "<div class='alert alert-info'>";
                echo "O(a) cliente <strong>$nomeCliente</strong>, de e-mail <strong>$emailCliente</strong> adicionou ao carrinho o imóvel localizado em <strong>$cidadeImovel</strong>, endereço <strong>$enderecoImovel</strong>.";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum imóvel foi adicionado ao carrinho por seus clientes.</p>";
        }
        
        $stmt->close();
        $mysqli->close();
        ?>
        <a class='nav-link' href='src/controller/logoutController.php'>Logout</a>
        </nav>
        <form method="GET">
        <div class="container">
            <button type="submit" class="btn btn-view" name="visualizar" value="1">Visualizar Meus Imóveis</button>
            <button type="submit" class="btn btn-add" name="adicionar" value="1">Adicionar Novo Imóvel</button>
            </div>
    </form>
        <?php

        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'cianman';

        $mysqli = new mysqli($host, $user, $password, $database);

        if ($mysqli->connect_error) {
            die('Erro de conexão: ' . $mysqli->connect_error);
        }

        // Variável de ID do corretor
        //$funcionariosId = 1;

        // Função para visualizar imóveis
        function visualizarImoveis($mysqli, $funcionariosId) {
        echo "<div class='flex-container'";

            $query = "
                SELECT 
                    imoveis.id,
                    imoveis.logradouro,
                    imoveis.numCasa,
                    imoveis.bairro,
                    imoveis.cidade,
                    imoveis.cep,
                    imoveis.tamanho,
                    imoveis.numQuartos,
                    imoveis.tipo,
                    imoveis.compraAluga,
                    imoveis.valor,
                    (SELECT imagens.link FROM imagens WHERE imagens.imovelId = imoveis.id ORDER BY imagens.id ASC LIMIT 1) AS imagem
                FROM 
                    imoveis
                WHERE 
                    imoveis.funcionariosId = ?
            ";
        
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $funcionariosId);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($imovel = $result->fetch_assoc()) {
                 echo "<div class='card'>";
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($imovel['logradouro']) . ", " . htmlspecialchars($imovel['numCasa']) . "</strong><br>";
        
                    // Exibindo apenas a primeira imagem
                    if (!empty($imovel['imagem'])) {
                        echo "<img src='" . htmlspecialchars($imovel['imagem']) . "' alt='Foto do Imóvel' style='width: 150px; height: 150px; margin: 5px;'><br>";
                    }
        
                    // Exibindo os outros dados do imóvel
         echo "<div class='card-body'>";
                    echo "<h5 class='card-title'><strong>" . htmlspecialchars($imovel['logradouro']) . "</strong>, " . htmlspecialchars($imovel['numCasa']) . "</h5>";
                    echo "<p class='card-text'><strong>Bairro:</strong> " . htmlspecialchars($imovel['bairro']) . "</p>";
                    echo "<p class='card-text'><strong>Cidade:</strong> " . htmlspecialchars($imovel['cidade']) . "</p>";
                    echo "<p class='card-text'><strong>Valor:</strong> R$ " . number_format($imovel['valor'], 2, ',', '.') . "</p>";
                    echo "<a href='detalhesImovel.php?id=" . $imovel['id'] . "' class='btn btn-detalhes'>Ver detalhes</a>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";

                echo "</ul>";
            } else {
                echo "<div class='alert alert-warning'>Nenhum imóvel encontrado para este corretor.</div>";
            }
        }
                  

        // Função para adicionar imóvel
        function adicionarImovel($mysqli, $funcionariosId)
        {
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

        if (empty($logradouro) || empty($numCasa) || empty($bairro) || empty($cidade) || empty($cep) || empty($valor)) {
            echo "<div class='alert alert-danger'>Todos os campos são obrigatórios.</div>";
        } else {
            // Inserir o imóvel na tabela 'imoveis'
            $stmt = $mysqli->prepare("INSERT INTO imoveis (funcionariosId, logradouro, numCasa, bairro, cidade, cep, tamanho, numQuartos, tipo, compraAluga, valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isissdissss", $funcionariosId, $logradouro, $numCasa, $bairro, $cidade, $cep, $tamanho, $numQuartos, $tipo, $compraAluga, $valor);

            if ($stmt->execute()) {
               
                $imovelId = $mysqli->insert_id;

                echo "<div class='alert alert-success'>Imóvel adicionado com sucesso! ID: $imovelId</div>";

                if (isset($_FILES['fotos'])) {
                    foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                        if (!empty($tmpName) && is_uploaded_file($tmpName)) {
                            $fileName = uniqid() . '_' . basename($_FILES['fotos']['name'][$index]);
                            $filePath = 'uploads/' . $fileName;
                            $fileType = mime_content_type($tmpName);

                            if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                                if (move_uploaded_file($tmpName, $filePath)) {
                                    $descricao = isset($_POST['descricao'][$index]) ? $_POST['descricao'][$index] : "";
                                    // Inserir imagem associada ao imovelId
                                    $stmtImg = $mysqli->prepare("INSERT INTO imagens (imovelId, link, descricao) VALUES (?, ?, ?)");
                                    $stmtImg->bind_param("iss", $imovelId, $filePath, $descricao);
                                    if (!$stmtImg->execute()) {
                                        echo "Erro ao inserir imagem: " . $stmtImg->error;
                                    }
                                }
                            } else {
                                echo "Arquivo inválido: $fileName. Somente imagens JPEG, PNG ou GIF são permitidas.";
                            }
                        } else {
                            echo "Erro: Arquivo não enviado ou inválido.";
                        }
                    }
                }

                $_SESSION['mensagem_sucesso'] = "Imóvel e imagens adicionados com sucesso!";
                exit();
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
        <div class="ad">
            <h2>Adicionar Novo Imóvel</h2>
            <form method="POST" class="my-3" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="logradouro" class="form-label">Logradouro:</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                </div>
                <div class="mb-3">
                    <label for="numCasa" class="form-label">Número da Casa:</label>
                    <input type="text" class="form-control" id="numCasa" name="numCasa" required>
                </div>
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro:</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade:</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP:</label>
                    <input type="text" class="form-control" id="cep" name="cep" required>
                </div>
                <div class="mb-3">
                    <label for="tamanho" class="form-label">Tamanho (m²):</label>
                    <input type="number" class="form-control" id="tamanho" name="tamanho" required>
                </div>
                <div class="mb-3">
                    <label for="numQuartos" class="form-label">Número de Quartos:</label>
                    <input type="number" class="form-control" id="numQuartos" name="numQuartos" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo:</label>
                    <input type="text" class="form-control" id="tipo" name="tipo" required>
                </div>
                <div class="mb-3">
                    <label for="compraAluga" class="form-label">Compra ou Aluguel:</label>
                    <select class="form-control" id="compraAluga" name="compraAluga" required>
                        <option value="Compra">Compra</option>
                        <option value="Aluguel">Aluguel</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor:</label>
                    <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                </div>
                <!--PARTE COM A TABELA IMAGEM-->
                <div class="mb-3">
        <label for="numImagens" class="form-label">Número de Imagens</label>
        <select class="form-control" id="numImagens" name="numImagens" onchange="gerarCamposImagens()" required>
            <option value="0">Selecione o número de imagens</option>
            <?php
            for ($i = 1; $i <= 7; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>
    </div>
    <!-- Container para os campos de upload das imagens -->
    <div id="imagensContainer"></div>
                <button type="submit" class="btn btn-success" name="adicionar">Salvar Imóvel</button>
            </form>

        </div>
            <script>
    // Função para gerar os campos de upload das imagens com base na escolha
    function gerarCamposImagens() {
        let numImagens = document.getElementById('numImagens').value;
        let container = document.getElementById('imagensContainer');
        container.innerHTML = ''; // Limpa os campos de imagens anteriores

        for (let i = 1; i <= numImagens; i++) {
            let div = document.createElement('div');
            div.classList.add('mb-3');

            // Campo de upload de imagem
            let fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'fotos[]';
            fileInput.classList.add('form-control');
            fileInput.accept = 'image/*';
            div.appendChild(fileInput);

            // DEscreve a imagem
            let labelDesc = document.createElement('label');
            labelDesc.classList.add('form-label');
            labelDesc.innerText = 'Descrição da Imagem ' + i;
            div.appendChild(labelDesc);

            let textArea = document.createElement('textarea');
            textArea.name = 'descricao[]';
            textArea.classList.add('form-control');
            div.appendChild(textArea);

            container.appendChild(div);
        }
    }
</script>
        <?php
            adicionarImovel($mysqli, $funcionariosId);
        }

        $mysqli->close();
        ?>

    </div>
</body>

</html>
