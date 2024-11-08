<?php

function usuarioInsert($nome, $email, $telefone, $cpf, $cep, $senha, $dataNasc) {
    $host = 'localhost';
    $user = 'root';
    $pwd = '';
    $database = 'cianman';

    $mysqli = new mysqli($host, $user, $pwd, $database);

    // Verifica se a conexão foi bem-sucedida
    if ($mysqli->connect_error) {
        die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
    }

    // Usando prepared statement para evitar SQL Injection
    $stmt = $mysqli->prepare("INSERT INTO clientes (id, cpf, nome, cep, email, dataNasc, senha, telefone) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");

    // Verifica se a preparação do statement foi bem-sucedida
    if ($stmt) {
        // Vincula os parâmetros aos placeholders
        $stmt->bind_param("sssssss", $cpf, $nome, $cep, $email, $dataNasc, $senha, $telefone);

        // Executa a query
        if ($stmt->execute()) {
            echo "Cliente cadastrado com sucesso.";
            // Obtendo o ID do último registro inserido
            $id = $stmt->insert_id;
        } else {
            echo "Erro ao cadastrar cliente: " . $stmt->error;
            $id = null;
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "Erro na preparação do statement: " . $mysqli->error;
        $id = null;
    }

    // Fecha a conexão
    $mysqli->close();

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

    if ($id) {
        echo "ID do cliente cadastrado: " . $id;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>
    <h1>Perfil de Usuário</h1>

    <p><strong>Nome:</strong> <?php echo $cliente['nome']; ?> <button onclick="editar('nome')">Editar</button></p>
    <p><strong>Email:</strong> <?php echo $cliente['email']; ?> <button onclick="editar('email')">Editar</button></p>
    <p><strong>CPF:</strong> <?php echo $cliente['cpf']; ?> <button onclick="editar('cpf')">Editar</button></p>
    <p><strong>Telefone:</strong> <?php echo $cliente['telefone']; ?> <button onclick="editar('telefone')">Editar</button></p>
    <p><strong>CEP:</strong> <?php echo $cliente['cep']; ?> <button onclick="editar('cep')">Editar</button></p>
    <p><strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($cliente['dataNasc'])); ?> <button onclick="editar('dataNasc')">Editar</button></p>
    <p><strong>Senha:</strong> ****** <button onclick="editar('senha')">Editar</button></p>

    <!-- Formulários de edição, inicialmente escondidos -->
    <div id="editarNome" style="display:none;">
        <h2>Editar Nome</h2>
        <form method="POST">
            <input type="text" name="novo_nome" value="<?php echo $cliente['nome']; ?>" required>
            <button type="submit" name="editar_nome">Salvar</button>
        </form>
    </div>

    <div id="editarEmail" style="display:none;">
        <h2>Editar Email</h2>
        <form method="POST">
            <input type="email" name="novo_email" value="<?php echo $cliente['email']; ?>" required>
            <button type="submit" name="editar_email">Salvar</button>
        </form>
    </div>

    <div id="editarCpf" style="display:none;">
        <h2>Editar CPF</h2>
        <form method="POST">
            <input type="text" name="novo_cpf" value="<?php echo $cliente['cpf']; ?>" required>
            <button type="submit" name="editar_cpf">Salvar</button>
        </form>
    </div>

    <div id="editarTelefone" style="display:none;">
        <h2>Editar Telefone</h2>
        <form method="POST">
            <input type="text" name="novo_telefone" value="<?php echo $cliente['telefone']; ?>" required>
            <button type="submit" name="editar_telefone">Salvar</button>
        </form>
    </div>

    <div id="editarCep" style="display:none;">
        <h2>Editar CEP</h2>
        <form method="POST">
            <input type="text" name="novo_cep" value="<?php echo $cliente['cep']; ?>" required>
            <button type="submit" name="editar_cep">Salvar</button>
        </form>
    </div>

    <div id="editarDataNascimento" style="display:none;">
        <h2>Editar Data de Nascimento</h2>
        <form method="POST">
            <input type="date" name="nova_data_nascimento" value="<?php echo $cliente['dataNasc']; ?>" required>
            <button type="submit" name="editar_data_nascimento">Salvar</button>
        </form>
    </div>

    <div id="editarSenha" style="display:none;">
        <h2>Editar Senha</h2>
        <form method="POST">
            <input type="password" name="nova_senha" required>
            <button type="submit" name="editar_senha">Salvar</button>
        </form>
    </div>

    <script>
        function editarPerfil($nome,$email,$cpf,$telefone,$cep,$senha,$dataNasc) {
            // Esconde todos os formulários
            document.getElementById('editarNome').style.display = 'none';
            document.getElementById('editarEmail').style.display = 'none';
            document.getElementById('editarCpf').style.display = 'none';
            document.getElementById('editarTelefone').style.display = 'none';
            document.getElementById('editarCep').style.display = 'none';
            document.getElementById('editarDataNascimento').style.display = 'none';
            document.getElementById('editarSenha').style.display = 'none';
            
            // Exibe o formulário de edição específico
            if(campo == 'nome') {
                document.getElementById('editarNome').style.display = 'block';
            } else if(campo == 'email') {
                document.getElementById('editarEmail').style.display = 'block';
            } else if(campo == 'cpf') {
                document.getElementById('editarCpf').style.display = 'block';
            } else if(campo == 'telefone') {
                document.getElementById('editarTelefone').style.display = 'block';
            } else if(campo == 'cep') {
                document.getElementById('editarCep').style.display = 'block';
            } else if(campo == 'data_nascimento') {
                document.getElementById('editarDataNascimento').style.display = 'block';
            } else if(campo == 'senha') {
                document.getElementById('editarSenha').style.display = 'block';
            }
        }
    </script>
</body>
</html>

