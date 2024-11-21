<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
</head>

<body>
    <div class="container my-4">

        <?php
        session_start();  
        if (isset($_SESSION['mensagem_sucesso'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['mensagem_sucesso'] . "</div>";

            unset($_SESSION['mensagem_sucesso']);
        }

        if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'corretor') {
            header('Location: login.php');
            exit();
        }

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
$stmt->bind_param("s", $email); // "s" indica que estamos vinculando um valor de tipo string
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

// Consulta para verificar os imóveis do funcionário no carrinho (consultas preparadas para evitar SQL Injection)
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
$stmt->bind_param("i", $funcionariosId); // "i" indica que estamos vinculando um valor de tipo inteiro (ID do funcionário)
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
        <form method="GET">
            <button type="submit" class="btn btn-primary" name="visualizar" value="1">Visualizar Meus Imóveis</button>
            <button type="submit" class="btn btn-success" name="adicionar" value="1">Adicionar Novo Imóvel</button>
            <a href="src/controller/logoutcontroller.php">Logout</a>
        </form>

        <?php

if (!isset($_SESSION['email'])) {
    echo "Usuário não autenticado.";
    exit();
}

$email = $_SESSION['email'];

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cianman';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("SELECT id FROM funcionarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $funcionariosId = $row['id']; 
} else {
    echo "Nenhum funcionário encontrado com o e-mail fornecido.";
}

$stmt->close();

        // Função para visualizar imóveis
        function visualizarImoveis($mysqli, $funcionariosId)
        {
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
                    //     echo "<input type='hidden' name='id' value='imovel' oninput='imovelDetalhes()'>";
                    // Supondo que você tenha um array de imóveis e queira tornar o id do imóvel clicável
 


                    //    echo "<input type='hidden' name= 'id' value= $imovel'[id]' oninput='imovelDetalhes()'>";
                    if (!empty($imovel['url'])) {
                        $fotos = explode(',', $imovel['url']);
                    //     foreach ($fotos as $url) {
                    //         echo "<img src='" . htmlspecialchars($url) . "' alt='Foto do Imóvel' style='width: 50px; margin: 5px;'><br>";
                    //     }
                     }
              //      foreach ($imovel as $imoveis) {
                        // Criar um link para visualizar os detalhes do imóvel
                        echo "<a href='detalhesImovel.php?id=" . $imovel['id'] . "'>";
                       // echo "<input type='hidden' name='id' value='" . $imovel['id'] . "' oninput='imovelDetalhes()'>";
                       foreach ($fotos as $url){
                        echo "<img src='" . htmlspecialchars($url) . "' alt='Foto do Imóvel' style='width: 150px; height: 150px; margin: 5px;'><br>";}
                        echo "</a>";
                //    }
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

        // Função para adicionar imóvel
        function adicionarImovel($mysqli, $funcionariosId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
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

                // Tratamento de upload de imagens

                /*
if (isset($_FILES['fotos'])) {
        foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
            $fileName = uniqid() . '_' . basename($_FILES['fotos']['name'][$index]);
            $filePath = 'uploads/' . $fileName;
            $fileType = mime_content_type($tmpName);
            
            if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                if (move_uploaded_file($tmpName, $filePath)) {
                    $descricao = $_POST['descricao'][$index];  // Obtém a descrição da imagem
                    // Agora insira as imagens e descrições no banco de dados (tabela imagens)
                    $stmt = $mysqli->prepare("INSERT INTO imagens (imovelId, url, descricao) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $imovelId, $filePath, $descricao);
                    $stmt->execute();
                }
            }
        }
    }
                */
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
                        $_SESSION['mensagem_sucesso'] = "Imóvel adicionado com sucesso!";

                        // Redireciona para a página corretorImoveis.php
                        header("Location: corretorImoveis.php");
                        exit();



                        // echo "<div class='alert alert-success'>Imóvel adicionado com sucesso!</div>";
                        //header("Refresh: 2; url=corretorImoveis.php");
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao adicionar imóvel: " . $stmt->error . "</div>";
                    }

                    header('location:../../corretorImoveis.php');

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
                <!--
                <div class="mb-3">
        <label for="numImagens" class="form-label">Número de Imagens</label>
        <select class="form-control" id="numImagens" name="numImagens" onchange="gerarCamposImagens()" required>
            <option value="0">Selecione o número de imagens</option>
            <?php
            // Exibe as opções de 1 até 7
            /*for ($i = 1; $i <= 7; $i++) {
                echo "<option value='$i'>$i</option>";
            }*/
            ?>
        </select>
        -->
            </form>
        <?php
            adicionarImovel($mysqli, $funcionariosId);
        }

        $mysqli->close();
        ?>
        <!--
        <script>
    // Função para gerar os campos de upload das imagens com base na escolha
    function gerarCamposImagens() {
        let numImagens = document.getElementById('numImagens').value;
        let container = document.getElementById('imagensContainer');
        container.innerHTML = ''; // Limpa os campos de imagens anteriores

        // Gera os campos de upload de imagens
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

            // Campo para descrição da imagem
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

    -->

    </div>
</body>

</html>
