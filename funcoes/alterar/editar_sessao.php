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

// Função para buscar todas as sessões cadastradas
function getSessoes($conn) {
    $sql = "SELECT s.id, f.nome AS filme, s.hora, s.sala FROM sessoes s JOIN filmes f ON s.filme_id = f.id";
    $result = $conn->query($sql);
    return $result;
}

// Função para buscar detalhes de uma sessão específica
function getSessaoDetalhes($conn, $id) {
    $sql = "SELECT * FROM sessoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Verifica se o formulário de edição foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_sessao'])) {
    $id = $_POST['id'];
    $filme_id = $_POST['filme_id'];
    $hora = $_POST['hora'];
    $sala = $_POST['sala'];
    $assentos = $_POST['assentos'];

    // Atualizar as informações da sessão no banco de dados
    $stmt = $conn->prepare("UPDATE sessoes SET filme_id = ?, hora = ?, sala = ?, assentos = ? WHERE id = ?");
    $stmt->bind_param("issii", $filme_id, $hora, $sala, $assentos, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Sessão atualizada com sucesso!'); window.location.href = '../../dashboard.php';</script>";
    } else {
        echo "Erro ao atualizar a sessão: " . $stmt->error;
    }

    $stmt->close();
}

// Se uma sessão foi selecionada para edição
if (isset($_GET['id'])) {
    $sessaoDetalhes = getSessaoDetalhes($conn, $_GET['id']);
}

// Buscar filmes para o dropdown
$filmes = getFilmes($conn);
$sessoes = getSessoes($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sessão</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <img src="../../img/logo.png" alt="Cinema Logo" class="logo">
        </header>

        <div class="form-container">
            <h2>Editar Sessão</h2>
            <div class="sessao-list">
                <form action="editar_sessao.php" method="GET">
                    <label for="sessao">Selecione uma sessão para editar:</label>
                    <select name="id" id="sessao" onchange="this.form.submit()">
                        <option value="">Escolha uma sessão</option>
                        <?php while ($sessao = $sessoes->fetch_assoc()): ?>
                            <option value="<?php echo $sessao['id']; ?>" <?php if (isset($sessaoDetalhes) && $sessaoDetalhes['id'] == $sessao['id']) echo 'selected'; ?>>
                                <?php echo $sessao['filme'] . " - " . $sessao['hora'] . " - Sala " . $sessao['sala']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <?php if (isset($sessaoDetalhes)): ?>
                <form action="editar_sessao.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $sessaoDetalhes['id']; ?>">
                    <div class="form-group">
                        <label for="filme_id">Filme:</label>
                        <select id="filme_id" name="filme_id" required>
                            <?php while ($filme = $filmes->fetch_assoc()): ?>
                                <option value="<?php echo $filme['id']; ?>" <?php if ($filme['id'] == $sessaoDetalhes['filme_id']) echo 'selected'; ?>>
                                    <?php echo $filme['nome']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hora">Hora da Sessão:</label>
                        <input type="time" id="hora" name="hora" value="<?php echo $sessaoDetalhes['hora']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sala">Sala:</label>
                        <input type="text" id="sala" name="sala" value="<?php echo $sessaoDetalhes['sala']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="assentos">Quantidade de Assentos:</label>
                        <input type="number" id="assentos" name="assentos" value="<?php echo $sessaoDetalhes['assentos']; ?>" required>
                    </div>
                    <button type="submit" name="edit_sessao" class="button">ATUALIZAR SESSÃO</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
