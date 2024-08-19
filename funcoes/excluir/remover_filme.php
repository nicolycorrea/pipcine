<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit(); // Redireciona para a página de login se não estiver logado
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "cinema_db";

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na Conexão: " . $conn->connect_error);
}

// Função para buscar todos os filmes cadastrados
function getFilmes($conn) {
    $sql = "SELECT id, nome FROM filmes";
    $result = $conn->query($sql);
    return $result;
}

// Verifica se um filme foi selecionado para exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_filme'])) {
    $id = $_POST['id'];

    // Remover o filme do banco de dados
    $stmt = $conn->prepare("DELETE FROM filmes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Filme removido com sucesso!'); window.location.href = '../../dashboard.php';</script>";
    } else {
        echo "Erro ao remover o filme: " . $stmt->error;
    }

    $stmt->close();
}

$filmes = getFilmes($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Filme</title>
    <link rel="stylesheet" href="css/rem.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <img src="../../img/logo.png" alt="Cinema Logo" class="logo">
        </header>

        <div class="form-container">
            <h2>Remover Filme</h2>
            <div class="filme-list">
                <form action="remover_filme.php" method="POST">
                    <label for="filme">Selecione um filme para remover:</label>
                    <select name="id" id="filme" required>
                        <option value="">Escolha um filme</option>
                        <?php while ($filme = $filmes->fetch_assoc()): ?>
                            <option value="<?php echo $filme['id']; ?>">
                                <?php echo $filme['nome']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="delete_filme" class="button">REMOVER FILME</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
