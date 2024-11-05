<!doctype html>
<html lang="en">
    <head>
        <title>Login - Cianman Imóveis</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <form method="POST" action="src/controller/loginController.php">
            <div class="mb-3">
                <label for="" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelpId" placeholder="Insira seu email."/>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Senha:</label>
                <input type="password" class="form-control" name="senha" id="senha" aria-describedby="emailHelpId" placeholder="Insira sua senha."/>
            </div>
            <input type="submit" class="btn btn-primary" value="Login">
            </form>

            <?php

   if (isset($_REQUEST['cod'])&&$_REQUEST['cod']=='171'){
       echo '<div class="alert alert-warning" role="alert">Usuário não encontrado</div>';
   }

?>
            
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>