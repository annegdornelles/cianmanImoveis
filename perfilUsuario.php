<?php

require_once 'src/controller/editarPerfilController.php';

if (!isset($_SESSION['email'])) {
    die("Erro: Usuário não autenticado.");
}

$cliente = isset($_SESSION['cliente']) ? $_SESSION['cliente'] : $cliente;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/css/stylelogin.css">
    <title>Editar Perfil</title>
   
</head>
<body>

    <div class="login-container">
        <h2>Editar Perfil</h2>

        <form action="src/controller/editarPerfilController.php" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $cliente['nome']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $cliente['email']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $cliente['cpf']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $cliente['telefone']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" class="form-control" id="cep" name="cep" value="<?php echo $cliente['cep']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="dataNasc" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="dataNasc" name="dataNasc" value="<?php echo $cliente['dataNasc']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
</form>

            <!-- Alerta de erro ou sucesso -->
            <div class="alert alert-warning mt-3" role="alert">
                Atenção: Verifique seus dados antes de salvar.
            </div>
        </form>

    <?php

    if(isset($_REQUEST['cod'])){
        if ($_REQUEST['cod']=='300'){
            echo '<div class="alert alert-success mt-3" role="alert">
                    Alterações salvas.
                </div>
                <a href="index.php" class="btn btn-link">Voltar à página inicial</a>';
        }
    }

    ?>
    </div>

    <footer>
        <p>© 2024 Todos os direitos reservados.</p>
        <a href="#">Política de Privacidade</a> | <a href="#">Termos de Uso</a>
    </footer>

    <!-- Bootstrap JS (opcional para interatividade) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>