<?php
session_start();

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

// Função para buscar detalhes de um filme específico
function getFilmeDetalhes($conn, $id) {
    $sql = "SELECT * FROM filmes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Verifica se o formulário de edição foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_filme'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $direcao = $_POST['direcao'];
    $duracao = $_POST['duracao'];
    $sinopse = $_POST['sinopse'];

    // Atualizar as informações do filme no banco de dados
    $stmt = $conn->prepare("UPDATE filmes SET nome = ?, genero = ?, direcao = ?, duracao = ?, sinopse = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nome, $genero, $direcao, $duracao, $sinopse, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Filme atualizado com sucesso!'); 
        window.location.href = '/pipcine-project/dashboard.php';</script>";
    } else {
        echo "Erro ao atualizar o filme: " . $stmt->error;
    }

    $stmt->close();
}

// Se um filme foi selecionado para edição
if (isset($_GET['id'])) {
    $filmeDetalhes = getFilmeDetalhes($conn, $_GET['id']);
}

$filmes = getFilmes($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Filme</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <img src="../../img/logo.png" alt="Cinema Logo" class="logo">
        </header>

        <div class="form-container">
            <h2>Editar Filme</h2>
            <div class="filme-list">
                <form action="editar_filme.php" method="GET">
                    <label for="filme">Selecione um filme para editar:</label>
                    <select name="id" id="filme" onchange="this.form.submit()">
                        <option value="">Escolha um filme</option>
                        <?php while ($filme = $filmes->fetch_assoc()): ?>
                            <option value="<?php echo $filme['id']; ?>" <?php if (isset($filmeDetalhes) && $filmeDetalhes['id'] == $filme['id']) echo 'selected'; ?>>
                                <?php echo $filme['nome']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <?php if (isset($filmeDetalhes)): ?>
                <form action="editar_filme.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $filmeDetalhes['id']; ?>">
                    <div class="form-group">
                        <label for="nome">Nome do Filme:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo $filmeDetalhes['nome']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="genero">Gênero:</label>
                        <input type="text" id="genero" name="genero" value="<?php echo $filmeDetalhes['genero']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="direcao">Direção:</label>
                        <input type="text" id="direcao" name="direcao" value="<?php echo $filmeDetalhes['direcao']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="duracao">Duração (minutos):</label>
                        <input type="text" id="duracao" name="duracao" value="<?php echo $filmeDetalhes['duracao']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sinopse">Sinopse:</label>
                        <textarea id="sinopse" name="sinopse" rows="4" required><?php echo $filmeDetalhes['sinopse']; ?></textarea>
                    </div>
                    <button type="submit" name="edit_filme" class="button">ATUALIZAR FILME</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
