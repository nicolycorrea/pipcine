<?php
session_start(); // Inicia a sessão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        die("Falha na Conexão: " . $conn->connect_error);
    }

    // Prepara e executa a consulta SQL
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows > 0) {
        // Armazenar dados do usuário na sessão
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        // Login bem-sucedido, redirecionar para a página principal
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.php?error=invalid");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
