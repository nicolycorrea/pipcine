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
        die("Connection failed: " . $conn->connect_error);
    }

    // Insere novo usuário
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirecionar para a página de login
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
