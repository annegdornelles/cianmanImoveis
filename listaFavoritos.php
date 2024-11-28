<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista favoritos</title>
    <link rel="stylesheet" type="text/css" href="src/css/stylefavoritar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .fa-arrow-left {
            color: #5e2b5c;
            font-size: 30px;
        }

        .fa-arrow-left:hover {
            color: #2e1b4e;
            font-size: 35px;
        }

        .arrow {
            text-align: left;
            margin-bottom: 20px;
        }

        .container, .card {
            align-items: center;
        }
    </style>
</head>
<body>
    <main>
        <a class="arrow" href="index.php" aria-current="page">
            <i class="fa-solid fa-arrow-left fa-lg"></i>
        </a>
        <h1 style="text-align: center;">Lista favoritos</h1>

        <?php
        session_start(); 
        require_once __DIR__ . '\src\model\conexaomysql.php';

        if (!isset($_SESSION['email'])) {
            header('Location: login.php');
            exit;
        }

        $email = $_SESSION['email'];

        $queryCliente = "SELECT cpf FROM clientes WHERE email = ?";
        $stmtCliente = $mysqli->prepare($queryCliente);
        $stmtCliente->bind_param('s', $email);
        $stmtCliente->execute();
        $resultCliente = $stmtCliente->get_result();

        if ($resultCliente->num_rows > 0) {
            $cliente = $resultCliente->fetch_assoc();
            $clienteCpf = $cliente['cpf'];
        } else {
            echo "Cliente não encontrado.";
            exit;
        }

        $queryFavoritos = "
            SELECT i.id, i.tipo, i.valor, i.cidade, i.bairro, img.link 
            FROM favoritos f 
            JOIN imoveis i ON f.imovelId = i.id
            LEFT JOIN imagens img ON i.id = img.imovelId
            WHERE f.clienteCpf = ?
            GROUP BY i.id
        ";
        $stmtFavoritos = $mysqli->prepare($queryFavoritos);
        $stmtFavoritos->bind_param('s', $clienteCpf);
        $stmtFavoritos->execute();
        $resultFavoritos = $stmtFavoritos->get_result();

        if ($resultFavoritos->num_rows > 0) {
            echo '<div class="container">';
            while ($imovel = $resultFavoritos->fetch_assoc()) {
                $imageUrl = htmlspecialchars($imovel['link']) ?: 'src/img/default.jpg'; 
                echo '
                    <div class="card" style="display: inline-block; width: 300px; margin: 10px;">
                        <img src="' . $imageUrl . '" class="card-img-top" alt="Imagem do Imóvel">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($imovel['tipo']) . '</h5>
                            <p class="card-text">
                                <strong>Valor:</strong> R$' . number_format($imovel['valor'], 2, ',', '.') . '<br>
                                <strong>Cidade:</strong> ' . htmlspecialchars($imovel['cidade']) . '<br>
                                <strong>Bairro:</strong> ' . htmlspecialchars($imovel['bairro']) . '
                            </p>
                            <!-- Link para remover o imóvel dos favoritos -->
                            <a href="listaFavoritos.php?remover=true&id=' . $imovel['id'] . '" class="btn btn-danger">Remover</a>
                        </div>
                    </div>
                ';
            }
            echo '</div>';
        } else {
            echo "<p style='text-align: center;'>Você não possui imóveis favoritos.</p>";
        }

        // Lógica para remover imóveis dos favoritos
        if (isset($_GET['remover']) && isset($_GET['id'])) {
            $imovelId = (int) $_GET['id'];

            $queryRemover = "DELETE FROM favoritos WHERE clienteCpf = ? AND imovelId = ?";
            $stmtRemover = $mysqli->prepare($queryRemover);
            $stmtRemover->bind_param('si', $clienteCpf, $imovelId);

            if ($stmtRemover->execute()) {
                echo "Imóvel removido dos favoritos!";
            } else {
                echo "Erro ao remover dos favoritos.";
            }

            // Redireciona para atualizar a página
            header('Location: listaFavoritos.php');
            exit;
        }

        $mysqli->close();
        ?>
    </main>
</body>
</html>
