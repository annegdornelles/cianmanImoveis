<?php
// Verifica se o ID do imóvel foi enviado via GET
if (isset($_GET['id'])) {
    $idImovel = $_GET['id']; // Recebe o ID do imóvel

    // Se o ID do imóvel não estiver vazio, registramos o favorito
    if ($idImovel) {
        $imovelCookie = "favoritos"; // Nome do cookie que armazenará os favoritos

        // Recupera a lista de favoritos do cookie, se existir
        $favoritos = isset($_COOKIE[$imovelCookie]) ? explode(',', $_COOKIE[$imovelCookie]) : [];

        // Verifica se o imóvel já foi adicionado aos favoritos
        if (!in_array($idImovel, $favoritos)) {
            // Adiciona o imóvel à lista de favoritos
            $favoritos[] = $idImovel;

            // Salva a lista de favoritos no cookie (expira em 30 dias)
            setcookie($imovelCookie, implode(',', $favoritos), time() + (30 * 24 * 60 * 60), "/");

            echo "Imóvel $idImovel foi adicionado aos favoritos!<br>";
        } else {
            echo "Este imóvel já está nos seus favoritos.<br>";
        }
    } else {
        echo "ID do imóvel inválido.<br>";
    }
}
?>

<!-- Formulário de Visualizar Lista de Favoritos -->
<a href="favoritos.php?visualizar=true">Visualizar Lista de Favoritos</a>

<?php
// Verifica se o usuário quer visualizar os favoritos
if (isset($_GET['visualizar']) && $_GET['visualizar'] == 'true') {
    // Nome do cookie onde a lista de favoritos está armazenada
    $imovelCookie = "favoritos";

    // Verifica se o cookie de favoritos existe
    if (isset($_COOKIE[$imovelCookie])) {
        // Recupera a lista de favoritos
        $favoritos = explode(',', $_COOKIE[$imovelCookie]);

        if (!empty($favoritos)) {
            echo "<h2>Lista de Favoritos:</h2>";
            echo "<ul>";
            foreach ($favoritos as $imovel) {
                echo "<li>Imóvel $imovel</li>";
            }
            echo "</ul>";
        } else {
            echo "Você não possui imóveis favoritos.";
        }
    } else {
        echo "Você não possui imóveis favoritos.";
    }
}
?>

<!--
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imóveis</title>
    <style>
        .imovel {
            margin: 10px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: inline-block;
        }

        .favoritar {
            cursor: pointer;
            color: blue;
            font-weight: bold;
        }

        .lista {
            align-items: center;
            background-color: palevioletred;
        }
    </style>
</head>

<body>

    <h1>Lista de Imóveis</h1>

    <div class="imovel">
        <p>Produto 1</p>
        <a href="favoritos.php?id=1">Favoritar</a>
    </div>

    <div class="imovel">
        <p>Produto 2</p>
        <a href="favoritos.php?id=2">Favoritar</a>
    </div>

    <div class="imovel">
        <p>Produto 3</p>
        <a href="favoritos.php?id=3">Favoritar</a>
    </div>

    <hr>
  <a href="favoritos.php?visualizar=true">Visualizar Lista de Favoritos</a>

</body>

</html>--> 

    <!-- Link para visualizar a lista de favoritos -->
 
