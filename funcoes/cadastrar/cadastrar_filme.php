<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Redireciona para a página de login se não estiver logado
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $direcao = $_POST['direcao'];
    $duracao = $_POST['duracao'];
    $sinopse = $_POST['sinopse'];

    // Tratamento do upload da imagem
    $target_dir = "catalogo/"; // Diretório onde a imagem será salva
    $target_file = $target_dir . basename($_FILES["capa"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verifica se o arquivo é uma imagem
    $check = getimagesize($_FILES["capa"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
    }

    // Verifica se o arquivo já existe
    if (file_exists($target_file)) {
        echo "Desculpe, o arquivo já existe.";
        $uploadOk = 0;
    }

    // Verifica o tamanho do arquivo (limite de 5MB)
    if ($_FILES["capa"]["size"] > 5000000) {
        echo "Desculpe, seu arquivo é muito grande.";
        $uploadOk = 0;
    }

    // Permite apenas certos formatos de arquivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        $uploadOk = 0;
    }

    // Verifica se $uploadOk é 0 por algum erro
    if ($uploadOk == 0) {
        echo "Desculpe, seu arquivo não foi enviado.";
    // Se tudo estiver ok, tenta fazer o upload do arquivo
    } else {
        if (move_uploaded_file($_FILES["capa"]["tmp_name"], $target_file)) {
            // Se o upload for bem-sucedido, insere os dados no banco de dados
            $servername = "localhost";
            $username = "root";
            $password_db = "";
            $dbname = "cinema_db";

            // Conectar ao banco de dados
            $conn = new mysqli($servername, $username, $password_db, $dbname);

            // Verificar conexão
            if ($conn->connect_error) {
                die("Falha na Conexão: " . $conn->connect_error);
            }

            // Inserir filme no banco de dados
            $stmt = $conn->prepare("INSERT INTO filmes (nome, genero, direcao, duracao, sinopse, capa) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nome, $genero, $direcao, $duracao, $sinopse, $target_file);

            if ($stmt->execute()) {
                // Mensagem de sucesso e redirecionamento
                echo "<script>
                    alert('Sessão cadastrada com sucesso!');
                    window.location.href = '../../dashboard.php';
                  </script>";

            } else {
                echo "Erro ao cadastrar filme: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Desculpe, houve um erro ao enviar seu arquivo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Filme</title>
    <link rel="stylesheet" href="css/form-filmes.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Filme</h2>
        <form action="cadastrar_filme.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome do Filme:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="genero">Gênero:</label>
                <input type="text" id="genero" name="genero" required>
            </div>
            <div class="form-group">
                <label for="direcao">Direção:</label>
                <input type="text" id="direcao" name="direcao" required>
            </div>
            <div class="form-group">
                <label for="duracao">Duração (minutos):</label>
                <input type="text" id="duracao" name="duracao" required>
            </div>
            <div class="form-group">
                <label for="sinopse">Sinopse:</label>
                <textarea id="sinopse" name="sinopse" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="capa">Capa do Filme:</label>
                <input type="file" id="capa" name="capa" required>
            </div>
            <button type="submit" class="button">CADASTRAR</button>
        </form>
    </div>
</body>
</html>