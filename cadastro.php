<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <form method="POST" action="src/controller/cadastroController.php">
            <div class="mb-3">
                <label for="" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" id="nome" aria-describedby="emailHelpId" placeholder="Insira o seu nome." />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelpId" placeholder="Insira seu email." />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">CPF:</label>
                <input type="text" class="form-control" name="cpf" id="cpf" aria-describedby="emailHelpId" placeholder="Insira o seu CPF." onkeyup="this.value=formatarCPF(this.value)" />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Insira seu número de telefone:</label>
                <input type="text" class="form-control" name="telefone" id="telefone" aria-describedby="emailHelpId" placeholder="Insira o seu telefone" onkeyup="this.value=formatarTelefone(this.value)" />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Insira seu CEP:</label>
                <input type="text" class="form-control" name="cep" id="cep" aria-describedby="emailHelpId" placeholder="Insira o seu CEP." onkeyup="this.value=formatarCEP(this.value)"/>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Insira sua data de Nascimento:</label>
                <input type="date" class="form-control" name="dataNasc" id="dataNasc" aria-describedby="emailHelpId" placeholder="Insira a sua data de nascimento" />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Insira a senha que será utilizada:</label>
                <input type="password" class="form-control" name="senha" id="senha" aria-describedby="emailHelpId" placeholder="Insira sua senha" />
            </div>
            <input type="submit" name="" id="" class="btn btn-primary" href="#" value="Cadastrar">
        </form>

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

            function formatarCEP(cep){
                cep = cep.replace(/\D/g, '');
                cep = cep.replace(/(\d{5})(\d{3})$/, '$1-$2');
                return cep;
            }
        </script>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>
