<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit(); // Redireciona para a página de login se não estiver logado
}

$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Filmes</title>
    <link rel="stylesheet" href="css/style-dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <img src="img/logo.png" alt="Cinema Logo" class="logo">
            <div class="user-info">
                <p>Bem-vindo, <strong><?php echo $user_name; ?></strong></p>
                <p><?php echo $user_email; ?></p>
                <form action="logout.php" method="POST">
                    <button type="submit" class="button-sair">Encerrar Sessão</button>
                </form>
            </div>
        </header>

        <div class="cards-container">
            <div class="card">
                <h3>CADASTRAR FILME</h3>
                <p>Adicione filmes ao catálogo.</p>
                <a href="funcoes/cadastrar/cadastrar_filme.php" class="button">Cadastrar</a>
            </div>
            <div class="card">
                <h3>EDITAR FILME</h3>
                <p>Edite os filmes já cadastrados.</p>
                <a href="funcoes/alterar/editar_filme.php" class="button">Editar</a>
            </div>
            <div class="card">
                <h3>REMOVER FILME</h3>
                <p>Remova filmes do catálogo.</p>
                <a href="funcoes/excluir/remover_filme.php" class="button">Remover</a>
            </div>
        </div>
    </div>
</body>
</html>
