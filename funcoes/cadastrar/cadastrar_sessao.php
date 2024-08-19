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
    return $conn->query($sql);
}

$filmes = getFilmes($conn);

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filme_id = $_POST['filme_id'];
    $hora = $_POST['hora'];
    $sala = $_POST['sala'];
    $assentos = $_POST['assentos'];
    $valor_assento = $_POST['valor_assento'];

    // Inserir a sessão no banco de dados
    $stmt = $conn->prepare("INSERT INTO sessoes (filme_id, hora, sala, assentos, valor_assento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $filme_id, $hora, $sala, $assentos, $valor_assento);

    if ($stmt->execute()) {
        echo "<script>
                alert('Sessão cadastrada com sucesso!');
                window.location.href = '../../dashboard.php';
              </script>";
    } else {
        echo "Erro ao cadastrar sessão: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Sessão</title>
    <link rel="stylesheet" href="css/form-sessoes.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Sessão</h2>
        <form action="cadastrar_sessao.php" method="POST">
            <div class="form-group">
                <label for="filme_id">Filme:</label>
                <select id="filme_id" name="filme_id" required>
                    <option value="">Selecione um filme</option>
                    <?php while ($filme = $filmes->fetch_assoc()): ?>
                        <option value="<?php echo $filme['id']; ?>"><?php echo $filme['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora" required>
            </div>
            <div class="form-group">
                <label for="sala">Sala:</label>
                <input type="text" id="sala" name="sala" required>
            </div>
            <div class="form-group">
                <label for="assentos">Assentos Disponíveis:</label>
                <input type="number" id="assentos" name="assentos" min="1" required>
            </div>
            <div class="form-group">
                <label for="valor_assento">Valor por Assento (R$):</label>
                <input type="number" step="0.01" id="valor_assento" name="valor_assento" required>
            </div>
            <button type="submit" class="button">Cadastrar Sessão</button>
        </form>
    </div>
</body>
</html>
