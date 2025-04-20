<!doctype html>
<html lang="en">
<head>
    <title>Cianman Imóveis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="src/css/styleindex.css">

</head>


<body>

<header>
    <nav class="nav justify-content-between" style="display: flex; align-items: center;">
        <a class="nav-link" href="carrinho.php">
            <i class="fa-solid fa-cart-shopping fa-lg" style="color: white; width: 40px; text-align: center;"></i>
        </a>
        <a class="nav-link" href="listaFavoritos.php">
            <i class="fa-solid fa-heart fa-lg" style="color: white; width: 40px; text-align: center;"></i>
        </a>
        <?php if (isset($_SESSION['email'])){
        echo "<a class='nav-link' href='perfilUsuario.php'><i class='fa-solid fa-user fa-lg' style='color: white; width: 40px; text-align: center;'></i></a>";
        }
        ?>
          <a class="nav-link text-center" href="index.php">
            <img src="src/img/property.jpg" alt="Logo" style="width: 100px; height: auto; text-align:center;">
        </a>
        <div class="nav-right">
            <a class="nav-link" href="cadastro.php">Cadastro</a>
            <a class="nav-link" href="login.php">Login</a>
            <?php
            if (isset($_SESSION['email'])){
               echo "<a class='nav-link' href='src/controller/logoutController.php'>Logout</a>";
            }
            ?>
        </div>
    </nav>
</header>