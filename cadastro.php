<!doctype html>
<html lang="pt-BR">

<head>
    <title>Cadastro - Cianman Imóveis</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Font Google para melhorar o design -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/css/stylecadastro.css">
    <!-- Estilos Customizados -->

</head>

<body>

    <main>
        <div class="form-container">
            <h2>Cadastro - Cianman Imóveis</h2>
            <form method="POST" action="src/controller/cadastroController.php">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Insira o seu nome" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Insira seu email" />
                </div>
                <div class="mb-3">
                    <select name="selecao">
                        <option value="cliente">cliente</option>
                        <option value="funcionário">funcionário</option>
                        <option value="corretor">corretor</option>
                        <?php if(isset($corretor)){
                               echo '<label for="creci" class="form-label"> CRECI</label>';
                               echo '<input type="number" class="form-control" name="creci" id="creci" placeholder="Insira seu CRECI"/>';
                                }
                           
                        ?>
                    </select>

                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Insira o seu CPF" onkeyup="this.value=formatarCPF(this.value)" />
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Insira seu telefone" onkeyup="this.value=formatarTelefone(this.value)" />
                </div>
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP:</label>
                    <input type="text" class="form-control" name="cep" id="cep" placeholder="Insira seu CEP" onkeyup="this.value=formatarCEP(this.value)" />
                </div>
                <div class="mb-3">
                    <label for="dataNasc" class="form-label">Data de Nascimento:</label>
                    <input type="date" class="form-control" name="dataNasc" id="dataNasc" />
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" name="senha" id="senha" placeholder="Insira sua senha" />
                </div>
                <input type="submit" class="btn btn-primary" value="Cadastrar" />
                <a id="voltar" value="voltar" href="index.php">Voltar</a>
            </form>
        </div>

    </main>

    <footer>
        <p>&copy; 2024 Cianman Imóveis | <a href="#">Política de Privacidade</a></p>
    </footer>

    <script>
        function formatarCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            return cpf;
        }

        function formatarTelefone(telefone) {
            telefone = telefone.replace(/\D/g, ''); // Remove tudo que não for número
            if (telefone.length <= 2) {
                telefone = telefone.replace(/^(\d{2})$/, '($1)');
            } else if (telefone.length <= 6) {
                telefone = telefone.replace(/^(\d{2})(\d{1})$/, '($1) $2');
            } else if (telefone.length <= 10) {
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})$/, '($1) $2 $3');
            } else if (telefone.length <= 11) {
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1) $2 $3-$4');
            }
            return telefone;
        }

        function formatarCEP(cep) {
            cep = cep.replace(/\D/g, '');
            cep = cep.replace(/(\d{5})(\d{3})$/, '$1-$2');
            return cep;
        }
    </script>

    <!-- Bootstrap JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-B

    