<?php
require_once 'src/controller/editarImovelController.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    die("Erro: Usuário não autenticado.");
}

// Obtém os dados do imóvel para edição
$imovel = $_SESSION['imovel'] ?? null;
if (!$imovel) {
    die("Erro: Dados do imóvel não encontrados.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/css/style.css">
    <title>Editar Imóvel</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Imóvel</h2>

        <form action="src/controller/editarImovelController.php" method="POST">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título do Imóvel</label>
                <input type="text" class="form-control" id="titulo" name="titulo" 
                       value="<?php echo htmlspecialchars($imovel['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($imovel['descricao']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" 
                       value="<?php echo htmlspecialchars($imovel['endereco']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco" 
                       value="<?php echo htmlspecialchars($imovel['preco']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo do Imóvel</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="apartamento" <?php echo $imovel['tipo'] == 'apartamento' ? 'selected' : ''; ?>>Apartamento</option>
                    <option value="casa" <?php echo $imovel['tipo'] == 'casa' ? 'selected' : ''; ?>>Casa</option>
                    <option value="terreno" <?php echo $imovel['tipo'] == 'terreno' ? 'selected' : ''; ?>>Terreno</option>
                    <option value="comercial" <?php echo $imovel['tipo'] == 'comercial' ? 'selected' : ''; ?>>Comercial</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="area" class="form-label">Área (m²)</label>
                <input type="number" class="form-control" id="area" name="area" 
                       value="<?php echo htmlspecialchars($imovel['area']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quartos" class="form-label">Número de Quartos</label>
                <input type="number" class="form-control" id="quartos" name="quartos" 
                       value="<?php echo htmlspecialchars($imovel['quartos']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="banheiros" class="form-label">Número de Banheiros</label>
                <input type="number" class="form-control" id="banheiros" name="banheiros" 
                       value="<?php echo htmlspecialchars($imovel['banheiros']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="garagem" class="form-label">Vagas na Garagem</label>
                <input type="number" class="form-control" id="garagem" name="garagem" 
                       value="<?php echo htmlspecialchars($imovel['garagem']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>

        <!-- Exibe mensagem de sucesso ou erro -->
        <?php if (isset($_REQUEST['cod']) && $_REQUEST['cod'] == '300'): ?>
            <div class="alert alert-success mt-3" role="alert">
                Alterações salvas com sucesso.
            </div>
            <a href="index.php" class="btn btn-link">Voltar à página inicial</a>
        <?php endif; ?>
    </div>

    <footer class="text-center mt-5">
        <p>© 2024 Todos os direitos reservados.</p>
        <a href="#">Política de Privacidade</a> | <a href="#">Termos de Uso</a>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
