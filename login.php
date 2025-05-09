
<?php
    $email = isset($_COOKIE['email'])?$_COOKIE['email']:"";
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <title>Login - Cianman Imóveis</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Font Google para melhorar o design -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="src/css/stylelogin.css">

    <!-- Estilos Customizados -->
    
</head>
<style>
   .fa-arrow-left{
    color:#5e2b5c;
    font-size: 30px;
    text-align: left;
}

.fa-arrow-left:hover{
    color:#2e1b4e;
    font-size: 35px;
}

.arrow{
    text-align: left;
}
        

</style>

<body>
    
<script>
   function useCookieEmail(){
       const ultimoEmail = "<?=htmlspecialchars($email)?>";
       document.getElementById('email').value = ultimoEmail;
   }
</script>
    <main>
    <a class="arrow" href="index.php" aria-current="page">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
    </a>
        <div class="login-container">
       
            <h2>Bem-vindo à Cianman Imóveis</h2>
            <form method="POST" action="src/controller/loginController.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Insira seu email"
                    value="<?= htmlspecialchars($email)?>" required />
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" name="senha" id="senha" placeholder="Insira sua senha" required />
                </div>

                <input type="submit" class="btn btn-primary" value="Login"><br>
                
            </form>

            <?php
                if (isset($_REQUEST['cod'])){
                    if ($_REQUEST['cod'] == '171') {
                    echo '<div class="alert alert-warning" role="alert">Usuário ou senha não correspondem.</div>';
                    echo "<a href='cadastro.php'>Não tem cadastro? Cadastre-se aqui.</a>";
                }

                if ($_REQUEST['cod']=='300'){
                    echo '<div class="alert alert-success mt-3" role="alert">Login realizado com sucesso!</div>';
                    echo "<a href='index.php'>Voltar a página inicial</a>";
                }
            }
            else{
                    echo "<a href='cadastro.php'>Cadastre-se aqui.</a>";
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cianman Imóveis | <a href="#">Política de Privacidade</a></p>
    </footer>

    <!-- Bootstrap JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
