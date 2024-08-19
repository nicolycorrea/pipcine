<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pipcine - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="background">
        <div class="overlay">
            <div class="header">
                <img src="img/logo.png" alt="Logo PIPCINE" class="logo">
            </div>
            <div class="content">
                <h1>Bem-vindo ao Pipcine!</h1>
                <p>Realize o seu login para ver catálogo.</p>

                <!-- Caixa de Alerta -->
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                    echo '
                    <div class="alert">
                        <span class="alert-icon">x</span>
                        Email ou senha inválidos! Tente novamente.
                        <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                    </div>';
                }
                ?>

                <form action="login.php" method="POST" class="login-form">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <button type="submit" class="button">Entrar</button>
                </form>
                <p class="sign-up-text">Não possui cadastro? <a href="register.php">Registre-se</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>
