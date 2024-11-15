<?php
session_start();

// Destroi a sessão e redireciona para a página inicial
session_destroy();
header("Location:../../index.php");
exit();
?>
