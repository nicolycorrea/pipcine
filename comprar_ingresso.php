<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
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

// Verifica se o `sessao_id` foi passado na URL
if (!isset($_GET['sessao_id'])) {
    echo "Sessão não encontrada.";
    exit();
}

$sessao_id = $_GET['sessao_id'];

// Busca as informações da sessão selecionada
$sql = "SELECT s.id AS sessao_id, s.hora, s.sala, s.assentos, s.valor_assento, f.nome AS filme_nome
        FROM sessoes s
        JOIN filmes f ON s.filme_id = f.id
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sessao_id);
$stmt->execute();
$result = $stmt->get_result();
$sessao = $result->fetch_assoc();

if (!$sessao) {
    echo "Sessão não encontrada.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Ingresso</title>
    <link rel="stylesheet" href="css/comprar_ingresso.css">
</head>
<body>
    <div class="compra-container">
        <h1>Comprar Ingresso para "<?php echo $sessao['filme_nome']; ?>"</h1>
        <p><strong>Hora:</strong> <?php echo $sessao['hora']; ?></p>
        <p><strong>Sala:</strong> <?php echo $sessao['sala']; ?></p>
        <p><strong>Assentos Disponíveis:</strong> <?php echo $sessao['assentos']; ?></p>
        <p><strong>Valor por Assento:</strong> R$ <?php echo number_format($sessao['valor_assento'], 2, ',', '.'); ?></p>

        <!-- Botão para abrir o modal -->
        <button id="comprarBtn" class="button-comprar">Comprar Ingresso</button>

        <!-- Modal para a compra de ingresso -->
        <div id="compraModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Detalhes da Compra</h2>
                <form action="enviar_comprovante.php" method="POST">
                    <input type="hidden" name="sessao_id" value="<?php echo $sessao['sessao_id']; ?>">
                    <div class="form-group">
                        <label for="nome_cliente">Nome do Cliente:</label>
                        <input type="text" id="nome_cliente" name="nome_cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="email_cliente">Email de Contato:</label>
                        <input type="email" id="email_cliente" name="email_cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade de Ingressos:</label>
                        <input type="number" id="quantidade" name="quantidade" min="1" max="<?php echo $sessao['assentos']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="valor_total">Valor Total (R$):</label>
                        <input type="text" id="valor_total" name="valor_total" readonly>
                    </div>
                    <button type="submit" class="button-enviar">Enviar Comprovante</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Abrir o modal
        var modal = document.getElementById("compraModal");
        var btn = document.getElementById("comprarBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Calcular o valor total da compra
        var quantidadeInput = document.getElementById("quantidade");
        var valorTotalInput = document.getElementById("valor_total");

        quantidadeInput.oninput = function() {
            var valorAssento = <?php echo $sessao['valor_assento']; ?>;
            var quantidade = parseInt(quantidadeInput.value);
            if (!isNaN(quantidade)) {
                valorTotalInput.value = (valorAssento * quantidade).toFixed(2).replace('.', ',');
            } else {
                valorTotalInput.value = '';
            }
        }
    </script>
</body>
</html>
