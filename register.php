<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="background">
        <div class="overlay">
            <div class="header">
                <img src="img/logo.png" alt="Cinema Logo" class="logo">
            </div>
            <div class="content">
                <h1>Crie sua conta</h1>
                <form action="register.php" method="POST" class="login-form">
                    <input type="text" name="name" placeholder="Nome" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <button type="submit" class="button">Registrar</button>
                    <p class="sign-up-text"><a href="index.php">Voltar para Login...</a></p>
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    // Configurações do banco de dados
                    $servername = "localhost"; // Ou "127.0.0.1"
                    $username = "root"; // Seu usuário do MySQL
                    $password_db = ""; // Sua senha do MySQL (geralmente vazia para localhost)
                    $dbname = "cinema_db"; // Nome do banco de dados

                    // Conectar ao banco de dados
                    $conn = new mysqli($servername, $username, $password_db, $dbname);

                    // Verificar conexão
                    if ($conn->connect_error) {
                        die("Falha na conexão: " . $conn->connect_error);
                    }

                    // Insere novo usuário
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $password);

                    if ($stmt->execute()) {
                        echo "<p style='color: green;'>Sucesso! Realize seu login <a href='index.php'>clicando aqui</a>.</p>";
                    } else {
                        echo "<p style='color: red;'>Erro: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
