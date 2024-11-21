<style>
    .btn-rosa {
    background-color: #ff69b4;  /* Cor rosa */
    color: white;
    border: none;
}

.btn-rosa:hover {
    background-color: #ff1493;  /* Cor rosa mais escuro para o hover */
}
</style>

<?php
// Configuração de conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$pwd = '12345';
$database = 'cianman';

// Conexão com o banco de dados
$mysqli = new mysqli($host, $user, $pwd, $database);

if ($mysqli->connect_error) {
    die("Erro ao conectar no banco de dados: " . $mysqli->connect_error);
}

// Verificando se o ID do imóvel foi enviado
// if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    if($_POST){
        $id = $_POST['id'];

   // var_dump($id);

    // Consulta para buscar os dados do imóvel
    $query = "SELECT * FROM imoveis WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $mysqli->error);
    }

    // $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $stmt->bind_param('i', $id); 


    // Verifica se o imóvel foi encontrado
    if ($result->num_rows > 0) {
        $imovel = $result->fetch_assoc();

        // Renderizando os dados do imóvel
        echo "<h1>Detalhes do Imóvel</h1>";

        //var_dump($imovel['url']);
        echo "<img src='" . htmlspecialchars($imovel['url']) . 
        "' alt='Foto do Imóvel' style='width: 400px; height: 300px; margin: 5px;'> 
        <form method='GET'>
        <button type='submit' class='btn btn-rosa' name='alterarImagem' value='1'>Alterar Imagem</button>
        </form>";

        if (isset($_GET['alterarImagem'])) {
            
            // <form method='POST'>
            //     <input type='text' name='novoBairro' id='novoBairro' placeholder='Insira aqui o nome do bairro' required />
            //     <button type='submit' class='btn btn-rosa'>Salvar</button>
            // </form>";
            $fotos = [];
            if (isset($_FILES['fotos']) && $_FILES['fotos']['error'][0] == 0) {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }


                foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                    $fileName = uniqid() . '_' . basename($_FILES['fotos']['name'][$index]);
                    $filePath = $uploadDir . $fileName;

                    $fileType = mime_content_type($tmpName);
                    if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                        if (move_uploaded_file($tmpName, $filePath)) {
                            $fotos[] = 'uploads/' . $fileName;
                            $fotosPath = implode(',', $fotos);

                        }
                    }
                }
            }
        }

        echo "Bairro: " . htmlspecialchars($imovel['bairro']) .
        "<form method='GET'>
        <button type='submit' class='btn btn-rosa' name='alterarBairro' value='1'>Alterar Bairro</button>
        </form>";

        if (isset($_GET['alterarBairro'])) {
            echo " <form method='POST'>
                <input type='text' name='novoBairro' id='novoBairro' placeholder='Insira aqui o nome do bairro' required />
                <button type='submit' class='btn btn-rosa'>Salvar</button>
            </form>";
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoBairro'])) {
            $novoBairro = $_POST['novoBairro'];
            $queryUpdate = "UPDATE imoveis SET bairro = ? WHERE id = ?";
            $stmtUpdate = $mysqli->prepare($queryUpdate);
        
            if ($stmtUpdate) {
                $stmtUpdate->bind_param('si', $novoBairro, $id);
                if ($stmtUpdate->execute()) {
                    echo "<p>Bairro atualizado com sucesso para: " . htmlspecialchars($novoBairro) . "</p>";
                } else {
                    echo "<p>Erro ao atualizar o bairro: " . $stmtUpdate->error . "</p>";
                }
            } else {
                echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
            }
        }
        
        echo "Cidade: " . htmlspecialchars($imovel['cidade']).
        "<form method='GET'>
            <button type='submit' class='btn btn-rosa' name='alterarCidade' value='3'>Alterar Cidade</button>
        </form>";

        if (isset($_GET['alterarCidade'])) {
            echo " <form method='POST'>
                <input type='text' name='novaCidade' id='novaCidade' placeholder='Insira aqui o nome da cidade' required />
                <button type='submit' class='btn btn-rosa'>Salvar</button>
            </form>";
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novaCidade'])) {
            $novaCidade = $_POST['novaCidade'];
            $queryUpdate = "UPDATE imoveis SET cidade = ? WHERE id = ?";
            $stmtUpdate = $mysqli->prepare($queryUpdate);
        
            if ($stmtUpdate) {
                $stmtUpdate->bind_param('si', $novaCidade, $id);
                if ($stmtUpdate->execute()) {
                    echo "<p>Cidade atualizada com sucesso para: " . htmlspecialchars($novaCidade) . "</p>";
                } else {
                    echo "<p>Erro ao atualizar a cidade: " . $stmtUpdate->error . "</p>";
                }
            } else {
                echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
            }
        }
         
        echo "CEP: " . htmlspecialchars($imovel['cep']) .  
        "<form method='GET'>
            <button type='submit' class='btn btn-rosa' name='alterarCep' value='4'>Alterar CEP</button>
        </form>";

    if (isset($_GET['alterarCep'])) {
        echo " <form method='POST'>
            <input type='number' name='novoCep' id='novoCep' placeholder='Insira aqui o Código de Endereçamento Postal, CEP' required />
            <button type='submit' class='btn btn-rosa'>Salvar</button>
        </form>";
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoCep'])) {
        $novoCep = $_POST['novoCep'];
        $queryUpdate = "UPDATE imoveis SET cep = ? WHERE id = ?";
        $stmtUpdate = $mysqli->prepare($queryUpdate);
    
        if ($stmtUpdate) {
            $stmtUpdate->bind_param('si', $novoCep, $id);
            if ($stmtUpdate->execute()) {
                echo "<p>CEP atualizada com sucesso para: " . htmlspecialchars($novoCep) . "</p>";
            } else {
                echo "<p>Erro ao atualizar o CEP: " . $stmtUpdate->error . "</p>";
            }
        } else {
            echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
        }
    }
        echo "Tamanho: " . htmlspecialchars($imovel['tamanho']) . " m²        
        <form method='GET'>
            <button type='submit' class='btn btn-rosa' name='alterarTamanho' value='5'>Alterar tamanho</button>
        </form>";

    if (isset($_GET['alterarTamanho'])) {
        echo " <form method='POST'>
            <input type='number' name='novoTamanho' id='novoTamanho' placeholder='Insira aqui o tamanho' required />
            <button type='submit' class='btn btn-rosa'>Salvar</button>
        </form>";
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoTamanho'])) {
        $novoTamanho = $_POST['novoTamanho'];
        $queryUpdate = "UPDATE imoveis SET tamanho = ? WHERE id = ?";
        $stmtUpdate = $mysqli->prepare($queryUpdate);
    
        if ($stmtUpdate) {
            $stmtUpdate->bind_param('si', $novoTamanho, $id);
            if ($stmtUpdate->execute()) {
                echo "<p>Tamanho atualizada com sucesso para: " . htmlspecialchars($novoTamanho) . "</p>";
            } else {
                echo "<p>Erro ao atualizar o tamanho: " . $stmtUpdate->error . "</p>";
            }
        } else {
            echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
}}
        
        echo "Quartos: " . htmlspecialchars($imovel['numQuartos']) . 
        "<form method='GET'>
            <button type='submit' class='btn btn-rosa' name='alterarQuartos' value='6'>Alterar nº de quartos</button>
        </form>";

if (isset($_GET['alterarQuartos'])) {
    echo " <form method='POST'>
        <input type='number' name='novoNumQuartos' id='novoNumQuartos' placeholder='Insira aqui o número de quartos' required />
        <button type='submit' class='btn btn-rosa'>Salvar</button>
    </form>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoNumQuartos'])) {
    $novoNumQuartos = $_POST['novoNumQuartos'];
    $queryUpdate = "UPDATE imoveis SET numQuartos = ? WHERE id = ?";
    $stmtUpdate = $mysqli->prepare($queryUpdate);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param('si', $novoNumQuartos, $id);
        if ($stmtUpdate->execute()) {
            echo "<p>Número de quartos atualizado com sucesso para: " . htmlspecialchars($novoNumQuartos) . "</p>";
        } else {
            echo "<p>Erro ao atualizar o número de quartos: " . $stmtUpdate->error . "</p>";
        }
    } else {
        echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
}}
        echo "Tipo: " . htmlspecialchars($imovel['tipo']) . "   
        <form method='GET'>
            <button type='submit' class='btn btn-rosa' name='alterarTipo' value='7'>Alterar tipo</button>
        </form>";

    if (isset($_GET['alterarTipo'])) {
        echo " <form method='POST'>
            <input type='text' name='novoTipo' id='novoTipo' placeholder='Insira aqui o tipo (se é casa, apartamento ou outro)' required />
            <button type='submit' class='btn btn-rosa'>Salvar</button>
        </form>";
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoTipo'])) {
        $novoTipo = $_POST['novoTipoo'];
        $queryUpdate = "UPDATE imoveis SET tipo = ? WHERE id = ?";
        $stmtUpdate = $mysqli->prepare($queryUpdate);
    
        if ($stmtUpdate) {
            $stmtUpdate->bind_param('si', $novoTipo, $id);
            if ($stmtUpdate->execute()) {
                echo "<p>Tipo atualizada com sucesso para: " . htmlspecialchars($novoTipo) . "</p>";
            } else {
                echo "<p>Erro ao atualizar o tipo: " . $stmtUpdate->error . "</p>";
            }
        } else {
            echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
    }
} 

echo "Status: " . htmlspecialchars($imovel['compraAluga']) . "
<form method='GET'>
    <button type='submit' class='btn btn-rosa' name='alterarStatus' value='1'>Alterar Status</button>
</form>";

if (isset($_GET['alterarStatus'])) {
    echo "
    <form method='POST'>
        <input type='radio' name='compraAluga' value='Aluguel' id='aluguel' " . ($imovel['compraAluga'] == 'Aluguel' ? 'checked' : '') . ">
        <label for='aluguel'>Aluguel</label><br>

        <input type='radio' name='compraAluga' value='Compra' id='compra' " . ($imovel['compraAluga'] == 'Compra' ? 'checked' : '') . ">
        <label for='compra'>Compra</label><br>

        <button type='submit' class='btn btn-rosa'>Salvar</button>
    </form>";
}

// Verifica e atualiza o status do imóvel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compraAluga'])) {
    $novoStatus = $_POST['compraAluga'];
    $queryUpdate = "UPDATE imoveis SET compraAluga = ? WHERE id = ?";
    $stmtUpdate = $mysqli->prepare($queryUpdate);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param('si', $novoStatus, $id);
        if ($stmtUpdate->execute()) {
            echo "<p>Status atualizado com sucesso para: " . htmlspecialchars($novoStatus) . "</p>";
        } else {
            echo "<p>Erro ao atualizar o status: " . $stmtUpdate->error . "</p>";
        }
    } else {
        echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
    }
}
        // echo "Compra/Aluguel: " . htmlspecialchars($imovel['compraAluga']);
        // if(isset($imovel['compraAluga'])){
        //     if($imovel(['compraAluga'] === 'aluguel')){
        //         echo "<form method='POST'>
        //                  <button type='submit' class='btn btn-rosa' name='alterarParaCompra' value='8'>Alterar para COMPRA</button>
        //             </form>";

        //             if (isset($_GET['alterarParaCompra'])) {
        //                 echo " <form method='POST'>
        //                     <input type='hidden' name='novaCompra' id='novaCompra' required />
        //                     <button type='submit' class='btn btn-rosa'>Salvar</button>
        //                 </form>";
        //             }
                    
        //             if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novaCompra'])) {
        //                 $novaCompra = $_POST['novaCompra'];
        //                 $queryUpdate = "UPDATE imoveis SET aluguel = compra where $id = id";
        //                 $stmtUpdate = $mysqli->prepare($queryUpdate);
                    
        //                 if ($stmtUpdate) {
        //                     $stmtUpdate->bind_param('si', $novaCompra, $id);
        //                     if ($stmtUpdate->execute()) {
        //                         echo "<p>Atualizada com sucesso para: " . htmlspecialchars($novaCompra) . "</p>";
        //                     } else {
        //                         echo "<p>Erro ao atualizar: " . $stmtUpdate->error . "</p>";
        //                     }
        //                 } else {
        //                     echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
        //         }}}
        //     if($imovel['compraAluga'] === 'compra'){
        //         echo "<form method='GET'>
        //                  <button type='submit' class='btn btn-rosa' name='alterarParaAluguel' value='8'>Alterar para ALUGUEL</button>
        //             </form>";

        //             if (isset($_GET['alterarParaAluguel'])) {
        //                 echo " <form method='POST'>
        //                     <input type='hidden' name='novoAluguel' id='novoAluguel' required />
        //                     <button type='submit' class='btn btn-rosa'>Salvar</button>
        //                 </form>";
        //             }
                    
        //             if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoAluguel'])) {
        //                 $novoAluguel = $_POST['novoAluguel'];
        //                 $queryUpdate = "UPDATE imoveis SET compra = 'aluguel' where id = $id";
        //                 $stmtUpdate = $mysqli->prepare($queryUpdate);
                    
        //                 if ($stmtUpdate) {
        //                     $stmtUpdate->bind_param('si', $novoAluguel, $id);
        //                     if ($stmtUpdate->execute()) {
        //                         echo "<p>Atualizada com sucesso para: " . htmlspecialchars($novoAluguel) . "</p>";
        //                     } else {
        //                         echo "<p>Erro ao atualizar: " . $stmtUpdate->error . "</p>";
        //                     }
        //                 } else {
        //                     echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
        //         }}}


                    echo "Valor: R$ " . number_format($imovel['valor'], 2, ',', '.') . "
                           <form method='GET'>
                            <button type='submit' class='btn btn-rosa' name='alterarValor' value='9'>Alterar valor</button>
                        </form>";
                
                    if (isset($_GET['alterarValor'])) {
                        echo " <form method='POST'>
                            <input type='text' name='novoValor' id='novoValor' placeholder='Insira aqui o valor' required />
                            <button type='submit' class='btn btn-rosa'>Salvar</button>
                        </form>";
                    }
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novoValor'])) {
                        $novaCidade = $_POST['novoValor'];
                        $queryUpdate = "UPDATE imoveis SET valor = ? WHERE id = ?";
                        $stmtUpdate = $mysqli->prepare($queryUpdate);
                    
                        if ($stmtUpdate) {
                            $stmtUpdate->bind_param('si', $novoValor, $id);
                            if ($stmtUpdate->execute()) {
                                echo "<p>Valor atualizada com sucesso para: " . htmlspecialchars($novoValor) . "</p>";
                            } else {
                                echo "<p>Erro ao atualizar o valor: " . $stmtUpdate->error . "</p>";
                            }
                        } else {
                            echo "<p>Erro ao preparar a consulta: " . $mysqli->error . "</p>";
                }}


            }
            $stmt->close();

        }

   
//      }}} else {
//     echo "ID do imóvel inválido.";
// }

    
$mysqli->close();

?>
