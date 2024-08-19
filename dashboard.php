<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
    <title>Gerenciador de Cinema</title>
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
                <img src="img/filme.jpg" alt="Cadastrar Filme" class="card-image">
                <h3>FILMES</h3>
                <p>Adicione, edite ou remova filmes do catálogo.</p>
                <a href="gerenciar_filmes.php" class="button">Acessar</a>
            </div>
            <div class="card">
                <img src="img/sessao.jpg" alt="Cadastrar Sessão" class="card-image">
                <h3>SESSÃO</h3>
                <p>Adicione, edite ou remova sessões do cinema.</p>
                <a href="gerenciar_sessao.php" class="button">Acessar</a>
            </div>
            <div class="card">
                <img src="img/ingresso.jpg" alt="Ver Catálogo" class="card-image">
                <h3>INGRESSOS</h3>
                <p>Compre ingressos para clientes.</p>
                <a href="catalogo.php" class="button">Ver Catálogo</a>
            </div>
        </div>

        </div>
    </div>
</body>
</html>
