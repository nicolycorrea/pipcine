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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Sessões</title>
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
                <h3>CADASTRAR SESSÃO</h3>
                <p>Adicione novas sessões ao cinema.</p>
                <a href="funcoes/cadastrar/cadastrar_sessao.php" class="button">Cadastrar</a>
            </div>
            <div class="card">
                <h3>EDITAR SESSÃO</h3>
                <p>Edite as sessões já cadastradas.</p>
                <a href="funcoes/alterar/editar_sessao.php" class="button">Editar</a>
            </div>
            <div class="card">
                <h3>REMOVER SESSÃO</h3>
                <p>Remova sessões já cadastradas do cinema.</p>
                <a href="funcoes/excluir/remover_sessao.php" class="button">Remover</a>
            </div>
        </div>
    </div>
</body>
</html>
