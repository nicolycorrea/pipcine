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

// Função para buscar todas as sessões cadastradas
function getSessoes($conn) {
    $sql = "SELECT s.id, f.nome AS filme, s.hora, s.sala FROM sessoes s JOIN filmes f ON s.filme_id = f.id";
    $result = $conn->query($sql);
    return $result;
}

// Verifica se uma sessão foi selecionada para exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_sessao'])) {
    $id = $_POST['id'];

    // Remover a sessão do banco de dados
    $stmt = $conn->prepare("DELETE FROM sessoes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Sessão removida com sucesso!'); window.location.href = '../../dashboard.php';</script>";
    } else {
        echo "Erro ao remover a sessão: " . $stmt->error;
    }

    $stmt->close();
}

$sessoes = getSessoes($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Sessão</title>
    <link rel="stylesheet" href="css/rem.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <img src="../../img/logo.png" alt="Cinema Logo" class="logo">
        </header>

        <div class="form-container">
            <h2>Remover Sessão</h2>
            <div class="sessao-list">
                <form action="remover_sessao.php" method="POST">
                    <label for="sessao">Selecione uma sessão para remover:</label>
                    <select name="id" id="sessao" required>
                        <option value="">Escolha uma sessão</option>
                        <?php while ($sessao = $sessoes->fetch_assoc()): ?>
                            <option value="<?php echo $sessao['id']; ?>">
                                <?php echo $sessao['filme'] . " - " . $sessao['hora'] . " - Sala " . $sessao['sala']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="delete_sessao" class="button">REMOVER SESSÃO</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
